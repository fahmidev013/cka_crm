<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userId  = !empty(Auth::guard('user')->user()) ? Auth::guard('user')->user()->id : null;
        $customerId  =  !empty(Auth::guard('customer')->user()) ? Auth::guard('customer')->user()->id : null;

        if (!$userId && !$customerId) {
            return redirect('/');
        }

        return $next($request);
    }
}
