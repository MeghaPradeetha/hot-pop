<?php

namespace App\Http\Middleware;

use App\Entities\Auth\UsersRepository;
use App\Events\UserSignIn;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmailVerification
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (Auth()->user() != null) {

			// If the user has one of the specified roles, redirect to the dashboard
			if (auth()->user()->isA(['super-admins', 'admins', 'developers'])) {
				return redirect('/dashboard');
			}

			if (Auth()->user()->email_confirmed_at != null) {
				//when user loging active status become 1;

				if (Auth()->user()->profile_setup_step == 0) {
					return redirect('/profile-setup');
				} elseif (Auth()->user()->profile_setup_step == User::PROFILE_SETUP) {
					return redirect('/profile-setup2');
				} elseif (Auth()->user()->profile_setup_step == User::PROFILE_INTRO) {
					return redirect('/profile-setup2-1');
				} elseif (Auth()->user()->profile_setup_step == User::PROFILE_IMAGES) {
					return redirect('/profile-setup3');
				} elseif (Auth()->user()->profile_setup_step == User::PROFILE_PERSONALITY) {
					return redirect('/logged-in');
				} else {
					$user = User::find(Auth::user()->id);
					$user->update([
						'last_active'        => \Carbon\Carbon::now(),
						'profile_setup_step' => User::LOGGED_IN_PAGE
					]);

					if (!$user->hasSubscription) {
						return redirect('/subscription');
					}


					// return redirect('/home');
					return $next($request);
				}

			} else {
				return redirect('/verification');
			}
		} else {

			return $next($request);
		}
	}
}
