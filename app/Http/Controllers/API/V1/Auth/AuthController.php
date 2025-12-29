<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\APIBaseController;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
// use App\Entities\Auth\UsersRepository; // Removed
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Models\Device;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends APIBaseController
{

    public function __construct()
    {
    }

    /**
     * Register a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|confirmed|min:8',
            'device_id'   => 'required',
            'device_type' => 'required',
        ]);

        $data = $request->only('email');
        $data['password'] = Hash::make($request->password);
        $data['confirmation_code'] = mt_rand(1000, 9999);
        $data['profile_setup_step'] = 0; // Default

        $user = User::create($data);

        // Handle Device
        $deviceData = $request->only(['device_id', 'device_type', 'device_push_token', 'apn_key_token']);
        $deviceData['access_token'] = Str::random(60); // Simple token generation
        $deviceData['user_id'] = $user->id;

        $device = Device::updateOrCreate(
            ['device_id' => $request->device_id, 'device_type' => $request->device_type],
            $deviceData
        );

        $responseData = $user->refresh()->toArray();
        $responseData['access_token'] = $device->access_token;

        event(new Registered($user));
        $user->email_confirmation_sent_at = now()->toDateTimeString();
        $user->save();

        FirebaseService::addNewUser($user);

        return $this->respondSuccess($responseData);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'device_id'   => 'required',
            'device_type' => 'required',
            'email'       => 'required|email',
            'password'    => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'), true)) {
            return $this->respondUnauthorized(trans('auth.failed'));
        }

        $user = auth()->user();
        $response = $user->toArray();
        
        // Find or Create Device
        // Logic: Try to find by user and device_id.
        $device = Device::where('user_id', $user->id)
                        ->where('device_id', $request->device_id)
                        ->first();

        if ($device) {
            // Update tokens
            $updates = [];
            if ($request->device_push_token && ($device->device_push_token !== $request->device_push_token)) {
                $updates['device_push_token'] = $request->device_push_token;
            }
            if ($request->apn_key_token && ($device->apn_key_token !== $request->apn_key_token)) {
                $updates['apn_key_token'] = $request->apn_key_token;
            }
            // Refresh access token logic - just generating new one for now as we don't have Sanctum details
            $updates['access_token'] = Str::random(60);
            
            $device->update($updates);
        } else {
            // Create New Device
             $device = Device::create([
                'user_id' => $user->id,
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
                'device_push_token' => $request->device_push_token,
                'apn_key_token' => $request->apn_key_token,
                'access_token' => Str::random(60)
            ]);
        }

        $response['access_token'] = $device->access_token;

        return $this->respondSuccess($response);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->confirmation_code == $request->code) {
            $user->update(['email_confirmed_at' => now()->toDateTimeString()]);
        } else {
            return $this->respondError('Invalid verification code, Resend and try again');
        }

        return $this->respondSuccess($user);
    }

    public function resendCode()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $user->confirmation_code = mt_rand(1000, 9999);
        $user->email_confirmation_sent_at = now()->toDateTimeString();
        $user->save();

        // Send Email verification code
        event(new Registered($user));

        return $this->respondSuccess(null, 'A verification code has been sent to your email.');
    }

    public function pushTokenUpdate(Request $request)
    {
        // This used DeviceAuthenticator::findDeviceByToken($accessToken);
        // We will assume header X-Access-Token is used.
        $accessToken = request()->header('X-Access-Token');
        
        if (!$accessToken) {
             return $this->respondUnauthorized('Token not provided');
        }

        $device = Device::where('access_token', $accessToken)->first();

        if (!$device) {
            return $this->respondError('Device not found');
        }

        if ($request->device_push_token && ($device->device_push_token !== $request->device_push_token)) {
            $device->device_push_token = $request->device_push_token;
        }
        if ($request->apn_key_token && ($device->apn_key_token !== $request->apn_key_token)) {
            $device->apn_key_token = $request->apn_key_token;
        }

        $device->save();

        return $this->respondSuccess(null, 'success');
    }
}
