<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        // Check if the user has the specified role
        if (Auth::check() && Auth::user()->is($role)) {
            // Redirect to the appropriate route based on the role
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home-profile'); // Modify this route name as needed
            }
        }

        return $next($request);
    }

}
