<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserSignOut;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class LogoutController extends Controller
{
    protected $guard;
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    public function destroy(Request $request): LogoutResponse
    {
        $user = Auth::user();
        $user->update([
            'active_status' => 0,
            'last_active'   => carbon::now()
        ]);

        $active_status = $user->active_status;
        $datetime = $user->last_active_time;

        event(new UserSignOut($user->id, $active_status, $datetime));
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
