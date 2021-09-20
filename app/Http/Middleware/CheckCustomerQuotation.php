<?php

namespace App\Http\Middleware;
use Auth;
use DB;
use Session;
use Closure;

class CheckCustomerQuotation
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
        $uid=$request->id;
        if($uid!=Auth::guard('customer')->user()->id){
            abort(403);
        }else{
             return $next($request);
        }
       
    }
}
