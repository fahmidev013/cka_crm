<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if(\Auth::guard($role)->check()){
            return $next($request);
        }else{
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Unauthorized'), 401);
            } else {
                return redirect()->guest('admin');
            }
        }
    }
}
