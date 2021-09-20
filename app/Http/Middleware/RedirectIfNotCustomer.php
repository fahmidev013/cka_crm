<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use DB;
class RedirectIfNotCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = 'customer')
    {
      if (!Auth::guard($guard)->check()) {
            return redirect('customer');
        }else{
            $customer_id = Auth::guard('customer')->user()->id;
            $customer = DB::table('customers')->where('id',$customer_id)->first();
            if($customer->is_active == 0){
                Auth::guard($guard)->logout();
                return redirect('customer');
            }            
        }
        return $next($request);
    }
}
