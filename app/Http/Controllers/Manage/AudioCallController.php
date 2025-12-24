<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Events\AcceptEvent;
use App\Events\IncomingCall;
use Illuminate\Http\Request;
use App\Services\AgoraService;
use App\Services\FirebaseService;
use App\Http\Controllers\Controller;
use App\Notifications\VoipPushNotification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AudioCallController extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
	 * For Ring the call
	 * 
	 */
	public function calling(Request $request)
	{
		$userId = $request->get('userId');
		$type = $request->get('type');
		$user = User::find($userId);
		$authorizedUser = auth()->user();

		if (!$user) {
			return response()->json(['error' => 'user is required'], 400);
		}

		$channelName = "call-$user->id-$authorizedUser->id";

		event(new IncomingCall($type, $channelName, $user->id, $authorizedUser->name, $authorizedUser->avatar2, $authorizedUser->id, ));

		$uid = rand(1, 999999);
		$token = (new AgoraService)->getToken($uid, $channelName);
		// send push token to mobile
		$user->notify(new VoipPushNotification($authorizedUser->name, $authorizedUser->avatar_url, $type, $channelName, $token, $authorizedUser));

		FirebaseService::updateUserStatus($user, User::CALL_INCOMING, $type, $channelName, $authorizedUser, $token);
		// FirebaseService::updateUserStatus($authorizedUser, User::CALL_OUTGOING, $type, $channelName, $user, $token);

		return response()->json([
			'channel_name' => $channelName
		]);
	}

	/**
	 * For Answer The Call
	 * 
	 */
	public function acceptEvent(Request $request)
	{
		$notify_user_id = $request->caller_id;
		$is_accept = $request->boolean('type');
		$receiver = User::findOrFail($notify_user_id);
		$Auth_user = Auth()->user();

		event(new AcceptEvent($is_accept, $Auth_user->id, $notify_user_id));

		FirebaseService::updateUserStatus($Auth_user, $is_accept ? User::CALL_INCALL : User::CALL_DECLINED);
		FirebaseService::updateUserStatus($receiver, $is_accept ? User::CALL_INCALL : User::CALL_DECLINED);

		return response()->json([
			'success' => true
		]);
	}

	/**
	 * Go to Audio Call Page
	 * 
	 */
	public function onCall(Request $request)
	{
		$callerId = $request->caller;
		$channel = $request->channel;
		$auth_user = Auth()->user()->id;

		$caller = User::find($callerId);

		if (!$channel)
			return redirect()->back();
		// You can use authenticated user ID here if needed
		$uid = rand(1, 999999);

		$token = (new AgoraService)->getToken($uid, $channel);

		return view('pages.chat.audio_call', compact('caller', 'token', 'channel', 'uid'));
	}
}
