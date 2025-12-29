<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use EMedia\Api\Docs\Param;
use App\Events\AcceptEvent;
use App\Events\IncomingCall;
use EMedia\Api\Docs\APICall;
use Illuminate\Http\Request;
use App\Services\AgoraService;
use App\Services\FirebaseService;
use App\Http\Controllers\Controller;
use App\Notifications\VoipPushNotification;
use EMedia\Devices\Auth\DeviceAuthenticator;

class CallAPIController extends Controller
{
    protected $agoraTokenService;

    public function __construct(AgoraService $agoraTokenService)
    {
        $this->agoraTokenService = $agoraTokenService;
    }

    public function getToken(Request $request)
    {
        document(function ()
        {
            return (new APICall)
                ->setGroup('Token')
                ->setName('Genrate Token')
                ->setParams([
                        // (new Param('channel_name', 'String', 'channel name'))->optional(),
                    (new Param('reciver_id', 'String', 'user id')),
                    (new Param('call_type', 'String', 'audio/video')),
                ])
                ->setErrorExample('{
                    "payload": null,
                    "message": "Validation success",
                    "result": true
                }');
        });

        $authorizedUser = \Illuminate\Support\Facades\Auth::user();
        $user = User::find($request->reciver_id);
        if (!$user) {
            return response()->apiError('Receive User not found', 404);
        }

        $uid = $request->input('uid', 0);
        // $role = $request->input('role', 1); // 1 for Publisher
        // $expireTimeInSeconds = $request->input('expiry', 86400);
        $callType = $request->call_type ?? 'audio';


        // $channelName = $request->input('channel_name');
        $channelName = "call-$user->id-$authorizedUser->id";
        $token = $this->agoraTokenService->getToken($uid, $channelName);

        // trigger web with pusher
        event(new IncomingCall($callType, $channelName, $user->id, $authorizedUser->name, $authorizedUser->avatar2, $authorizedUser->id, ));

        // send push token to mobile
        $user->notify(new VoipPushNotification($authorizedUser->name, $authorizedUser->avatar_url, $callType, $channelName, $token, $authorizedUser));

        FirebaseService::updateUserStatus($user, User::CALL_INCOMING, $callType, $channelName, $authorizedUser, $token);

        // $name = $user->name;
        // $status = 'initiated';
        // $roomType = 'single';
        // $profilePic = $user->avatar_url;
        // $channelName = $request->channel_name;
        // $user = $user->id;
        // if ($request->room_type == 'group') {
        //     $roomType = 'group';
        // }
        // // else{
        // // 	$reciver = User::find($request->reciver_id);
        // // }
        // if ($request->has('reciver_id')) {
        //     $ids = explode(',', $request->reciver_id);
        //     foreach ($ids as $id) {
        //         // $reciver = User::find($id);
        //         // $reciver->notify(new VoipPushNotification($name, $status, $roomType, $profilePic, $callType, $channelName, $token, $user));
        //     }

        // }
        // // $reciver->notify(new VoipPushNotification($name, $status, $roomType, $profilePic, $callType,$channelName,$token,$user));


        return response()->apiSuccess([
            'token'        => $token,
            'channel_name' => $channelName,
        ]);

    }

    /**
     * Answer Call
     * 
     */
    public function answer(Request $request)
    {
        document(function ()
        {
            return (new APICall)
                ->setGroup('Call')
                ->setName('Answer Call')
                ->setParams([
                    (new Param('reciver_id', 'String', 'user id comma seprated array')),
                    (new Param('is_accept', 'String', '0/1')),
                ])
                ->setErrorExample('{
                    "payload": null,
                    "message": "Validation success",
                    "result": true
                }');
        });

        $auth_user = \Illuminate\Support\Facades\Auth::user();
        $notify_user_id = $request->reciver_id;
        $is_accept = $request->is_accept == '1' ? 'true' : 'false';

        event(new AcceptEvent($is_accept, $auth_user->id, $notify_user_id));

        FirebaseService::updateUserStatus($auth_user, $is_accept ? User::CALL_INCALL : User::CALL_DECLINED);

        return response()->json([
            'success' => true
        ]);
    }
}
