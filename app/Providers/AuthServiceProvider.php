<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        VerifyEmail::toMailUsing(function ($notifiable)
        {

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->greeting("Hello!")
                ->line('Your email verification code is: ' . $notifiable->confirmation_code)
                ->line("Thank you for using HOT POP app. We're a new developing community to match single parents with other like minded single parents. We have new users joining daily and are completely free for the first 500 subscribers!")
                ->line('Happy matching!')
                ->line('Regards,')
                ->salutation("The " . config('app.name') . " team");
        });
    }
}
