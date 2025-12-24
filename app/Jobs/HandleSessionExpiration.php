<?php

namespace App\Jobs;

use App\Models\User;
use App\Events\UserSignOut;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HandleSessionExpiration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $threshold = now()->subMinutes(config('session.time_inactive'));

        // Get users whose last activity was more than the session lifetime ago
        $expiredUsers = User::where('active_status', 1)->where('last_active', '<', $threshold)->get();

        foreach ($expiredUsers as $user) {

            event(new UserSignOut($user->id, 0, $user->last_active_time));
            $user->update([
                'active_status' => 0
            ]);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
