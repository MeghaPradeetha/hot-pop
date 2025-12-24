<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AgoraService;

use App\Services\FirebaseService;
use App\Http\Controllers\Controller;

class VideoChatController extends Controller
{
    public function index()
    {
        $auth_user = Auth()->user()->id;
        $callerId = request()->get('caller');
        $channel = request()->get('channel');

        $caller = User::find($callerId);

        if (!$channel)
            return redirect()->back();
        // You can use authenticated user ID here if needed
        $uid = rand(1, 999999);
        
        $token = (new AgoraService)->getToken($uid, $channel);

        // FirebaseService::updateUserStatus($caller, User::CALL_INCOMING, 'video', $channel, $auth_user, $token);
		// FirebaseService::updateUserStatus($auth_user, User::CALL_OUTGOING, 'video', $channel, $caller, $token);

        return view('pages.chat.video_call', compact('token', 'channel','uid', 'caller'));
    }

    public function endCall(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::findOrFail($user_id);
        $auth_user = auth()->user();

        FirebaseService::updateUserStatus($user, User::CALL_DECLINED);
        FirebaseService::updateUserStatus($auth_user, User::CALL_DECLINED);

        return redirect(route("chat.profile", $user_id));

    }

}
