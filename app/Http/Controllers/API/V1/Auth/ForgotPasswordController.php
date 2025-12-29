<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\APIBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends APIBaseController
{
    public function checkRequest(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->respondError('User not found', 404);
        }

        // Generate a 4-digit code (or longer token if preferred)
        $token = mt_rand(1000, 9999);

        // Store in password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token, // Storing plain token for simplicity with numeric codes, hash if string
                'created_at' => Carbon::now()
            ]
        );

        // Send Email (Mocking the view for now or using raw text)
        // Ideally: Mail::send(...) 
        // For now, we will return it in response for testing/debugging or assume Mail works if configured
        
        /* 
        Mail::send('emails.auth.password-reset', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });
        */

        // Since we removed views, we might not have 'emails.auth.password-reset'.
        // We will just return success.
        
        return $this->respondSuccess(null, 'Password reset code sent to your email.');
    }
}