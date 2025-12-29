<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        $otp = rand(1000, 9999);

        $user = User::create([
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profile_setup_step' => 0,
            'confirmation_code' => $otp,
        ]);

        // Assign default role
        Bouncer::assign('users')->to($user);

        // Ideally send email here
        // Mail::to($user)->send(new VerificationEmail($otp));

        Auth::login($user);

        return redirect('/verification');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|array|min:4',
            'code.*' => 'required|numeric',
        ]);

        $inputCode = implode('', $request->code);
        $user = Auth::user();

        if ($user->confirmation_code == $inputCode) {
            $user->forceFill([
                'email_confirmed_at' => now(), // Using email_confirmed_at based on User model
                'confirmation_code' => null,
            ])->save();

            return redirect(RouteServiceProvider::HOME)->with('success', 'Email verified successfully!');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function resendOTP()
    {
        $user = Auth::user();
        $otp = rand(1000, 9999);

        $user->forceFill([
            'confirmation_code' => $otp
        ])->save();

        // Ideally send email here
        // Mail::to($user)->send(new VerificationEmail($otp));

        return back()->with('success', 'A new verification code has been sent.');
    }
}
