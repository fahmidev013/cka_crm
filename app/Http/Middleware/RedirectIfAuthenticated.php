<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard == "user" && Auth::guard('user')->check()) {
            return redirect('/dashboard');
        } elseif ($guard == "customer" && Auth::guard('customer')->check()) {
            return redirect('/customer/dashboard');
        }
        return $next($request);
    }
}
