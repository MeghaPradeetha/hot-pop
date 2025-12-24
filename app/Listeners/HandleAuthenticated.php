<?php

namespace App\Listeners;

use App\Events\UserSignIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleAuthenticated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        $user = $event->user;

        // Fire the UserSignIn event with the required parameters
        if (!$user->active_status) {
            $user->update([
                'active_status' => '1',
            ]);
            event(new UserSignIn($user->id, $user->active_status));
        }
    }
}
