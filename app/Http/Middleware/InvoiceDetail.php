<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Session;

class InvoiceDetail
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
        $order_reference_id = $request->order_id;
        $order_id= $request->invoice_id;
        $user_id   = Auth::guard('customer')->user()->id;
        $sales_data = DB::table('sale_orders')->where(['id' => $order_id,'customer_id' => $user_id])->count();
        if ($sales_data == 0) {
            abort(403);
        }else{
             return $next($request);
        }
    }
}
