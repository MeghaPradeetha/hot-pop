<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\APIBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends APIBaseController
{
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required', // The code
            'password' => 'required|confirmed|min:8',
        ]);

        $resetRecord = DB::table('password_resets')
                            ->where('email', $request->email)
                            ->where('token', $request->token)
                            ->first();

        if (!$resetRecord) {
            return $this->respondError('Invalid token or email.', 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->respondError('User not found.', 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return $this->respondSuccess($user, 'Password reset successfully.');
    }
}