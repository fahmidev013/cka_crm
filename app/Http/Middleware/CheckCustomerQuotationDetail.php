<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Session;

class CheckCustomerQuotationDetail
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
        $request_id = $request->id;
        $user_id   = Auth::guard('customer')->user()->id;
        $sales_data =DB::table('sale_orders')->where([
                                            'order_type'=> 'Direct Order', 
                                            'customer_id' => $user_id, 
                                            'id'=> $request_id
                                            ])->count();
        if($sales_data == 0){
            abort(403);
        }else{
             return $next($request);
        }
    }
}
