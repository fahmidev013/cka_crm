<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Session;


class PaymentDetail
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
        $payment_id= $request->id;
        $user_id   = Auth::guard('customer')->user()->id;
        $payment_data =DB::table('customer_transactions')->where(['id'=>$payment_id,'customer_id'=>$user_id])->count();
        if($payment_data==0){
            abort(403);
        }else{
             return $next($request);
        }
    }
}
