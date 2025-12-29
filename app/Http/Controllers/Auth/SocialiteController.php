<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialiteController extends Controller
{
    public function __construct()
    {
    }

    public function loginSocial(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($request);

        return Socialite::driver($provider)->setScopes(['openid', 'email'])->redirect();
    }

    public function callbackSocial(Request $request, string $provider)
    {
        $this->validateProvider($request);

        // $response = Socialite::driver($provider)->user();
        try {
            $response = Socialite::driver($provider)->user();
        } catch (InvalidStateException $e) {
            $response = Socialite::driver($provider)->setScopes(['openid', 'email'])->stateless()->user();
            // $response = Socialite::driver($provider)->stateless()->user();
        }

        // dump($response);
        $user = User::firstOrCreate(
            ['email' => $response->getEmail()],
            ['password' => Str::password()]
        );
        // $data = [$provider . '_id' => $response->getId()]; // Todo

        if ($user->wasRecentlyCreated) {
            $user->name = $response->getName() ?? $response->getNickname();
            $user->avatar_url = $response->getAvatar();
            $user->email_confirmed_at = now();
            $user->save();
            // event(new Registered($user));

            // $this->usersRepo->addFreeTrial($user);
        }

        // $user->update($data);

        Auth::login($user, remember: true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    protected function validateProvider(Request $request): array
    {
        return $this->getValidationFactory()->make(
            $request->route()->parameters(),
            ['provider' => 'in:facebook,google,tiktok,instagram']
        )->validate();
    }
}
