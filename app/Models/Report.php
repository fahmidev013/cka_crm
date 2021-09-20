<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Report extends Model
{
	
    public function Prev_getInventoryStockOnHand($type,$location)
    {
    	if ($type=='all' && $location=='all') {
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves GROUP BY item_id)sm
			ON sm.item_id = item.id
			
			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));
    	} else if ($type=='all' && $location !='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves WHERE location_id = '$location' GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));
    	} else if ($type !='all' && $location =='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type' AND inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));

    	}

		else if ($type !='all' && $location !='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type' AND inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves WHERE location_id = '$location' GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));

    	}

    	return $data;
    }

    //New Code Added by Aminul Islam starts here
    public function getInventoryStockOnHand($type,$location)
    {
    	$data = [];
    	if ($type=='all' && $location=='all') {
    	$data = DB::select(DB::raw("SELECT item.stock_id as item_id,item.name as description, COALESCE(sp.price,0) as retail_price, COALESCE(pp.price,0) as purchase_price, COALESCE(sm.quantity,0) as available_qty, sc.name as category_name, COALESCE(pod.tax_price_total,0) as cost_amount,COALESCE(pod.total_price,0) as total_amount,COALESCE(pod.total_qty,0) as received_qty
				FROM items as item

				LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
				ON sp.item_id = item.id

				LEFT JOIN(SELECT item_id, price FROM purchase_prices)pp
				ON pp.item_id = item.id

				LEFT JOIN(SELECT id, name FROM stock_categories)sc
				ON sc.id = item.stock_category_id

				LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves GROUP BY item_id)sm
				ON sm.item_id = item.id

				LEFT JOIN(select pod.item_id,SUM(pod.quantity_ordered) as total_qty,SUM(((pod.quantity_ordered*pod.unit_price) + (pod.quantity_ordered*pod.unit_price)*itt.tax_rate/100)) as tax_price_total,SUM(pod.quantity_ordered*pod.unit_price) as total_price from purchase_order_details as pod left join purchase_taxes as ipt on ipt.purchase_order_detail_id=pod.id left join tax_types as itt on ipt.tax_type_id=itt.id GROUP BY pod.item_id)pod
				ON pod.item_id = item.id 

				WHERE item.is_active = 1 AND item.is_stock_managed = 1 ORDER BY item.id DESC
    		"));
    	} else if ($type=='all' && $location !='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id as item_id,item.name as description, COALESCE(sp.price,0) as retail_price,  COALESCE(pp.price,0) as purchase_price, COALESCE(sm.quantity,0) as available_qty, sc.name as category_name, COALESCE(pod.tax_price_total,0) as cost_amount,COALESCE(pod.total_price,0) as total_amount,COALESCE(pod.total_qty,0) as received_qty
				FROM items as item

				LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
				ON sp.item_id = item.id

				LEFT JOIN(SELECT id, name FROM stock_categories)sc
				ON sc.id = item.stock_category_id

				LEFT JOIN(SELECT item_id, price FROM purchase_prices)pp
				ON pp.item_id = item.id

				LEFT JOIN(SELECT item_id,sum(quantity)as quantity, location_id FROM stock_moves where location_id='$location' GROUP BY item_id)sm
				ON sm.item_id = item.id

				LEFT JOIN(select pod.item_id,SUM(pod.quantity_ordered) as total_qty,SUM(((pod.quantity_ordered*pod.unit_price) + (pod.quantity_ordered*pod.unit_price)*itt.tax_rate/100)) as tax_price_total,SUM(pod.quantity_ordered*pod.unit_price) as total_price from purchase_order_details as pod left join purchase_taxes as ipt on ipt.purchase_order_detail_id=pod.id left join tax_types as itt on ipt.tax_type_id=itt.id GROUP BY pod.item_id)pod
				ON pod.item_id = item.id 


				WHERE item.is_active = 1 AND item.is_stock_managed = 1 AND sm.location_id = '$location' ORDER BY item.id DESC
    		"));
    	} else if ($type !='all' && $location =='all') {

    	$data = DB::select(DB::raw("SELECT item.stock_id as item_id,item.name as description, COALESCE(sp.price,0) as retail_price,  COALESCE(pp.price,0) as purchase_price, COALESCE(sm.quantity,0) as available_qty, sc.name as category_name, COALESCE(pod.tax_price_total,0) as cost_amount,COALESCE(pod.total_price,0) as total_amount,COALESCE(pod.total_qty,0) as received_qty
				FROM items as item

				LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
				ON sp.item_id = item.id

				LEFT JOIN(SELECT id, name FROM stock_categories)sc
				ON sc.id = item.stock_category_id

				LEFT JOIN(SELECT item_id, price FROM purchase_prices)pp
				ON pp.item_id = item.id

				LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves GROUP BY item_id)sm
				ON sm.item_id = item.id

				LEFT JOIN(select pod.item_id,SUM(pod.quantity_ordered) as total_qty,SUM(((pod.quantity_ordered*pod.unit_price) + (pod.quantity_ordered*pod.unit_price)*itt.tax_rate/100)) as tax_price_total,SUM(pod.quantity_ordered*pod.unit_price) as total_price from purchase_order_details as pod left join purchase_taxes as ipt on ipt.purchase_order_detail_id=pod.id left join tax_types as itt on ipt.tax_type_id=itt.id GROUP BY pod.item_id)pod
				ON pod.item_id = item.id

				WHERE item.stock_category_id = '$type' AND item.is_active = 1 AND item.is_stock_managed = 1 ORDER BY item.id DESC
    		"));
    	} else if ($type !='all' && $location !='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id as item_id,item.name as description, COALESCE(sp.price,0) as retail_price,  COALESCE(pp.price,0) as purchase_price, COALESCE(sm.quantity,0) as available_qty, sc.name as category_name, COALESCE(pod.tax_price_total,0) as cost_amount,COALESCE(pod.total_price,0) as total_amount,COALESCE(pod.total_qty,0) as received_qty
				FROM items as item

				LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
				ON sp.item_id = item.id

				LEFT JOIN(SELECT id, name FROM stock_categories)sc
				ON sc.id = item.stock_category_id

				LEFT JOIN(SELECT item_id, price FROM purchase_prices)pp
				ON pp.item_id = item.id

				LEFT JOIN(SELECT item_id,sum(quantity)as quantity, location_id FROM stock_moves WHERE location_id='$location' GROUP BY item_id)sm
				ON sm.item_id = item.id

				LEFT JOIN(select pod.item_id,SUM(pod.quantity_ordered) as total_qty,SUM(((pod.quantity_ordered*pod.unit_price) + (pod.quantity_ordered*pod.unit_price)*itt.tax_rate/100)) as tax_price_total,SUM(pod.quantity_ordered*pod.unit_price) as total_price from purchase_order_details as pod left join purchase_taxes as ipt on ipt.purchase_order_detail_id=pod.id left join tax_types as itt on ipt.tax_type_id=itt.id GROUP BY pod.item_id)pod
				ON pod.item_id = item.id 


				WHERE item.stock_category_id = '$type' AND item.is_active = 1 AND item.is_stock_managed = 1 AND sm.location_id = '$location' 
    		"));

    	}
    	
    	return collect($data);
    }
    //New Code Added by Aminul Islam ends here
    public function getInventoryStockOnHandPdf($type,$location)
    {

    	if ($type=='all' && $location=='all') {
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master)item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));
    	} else if ($type=='all' && $location !='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master)item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves WHERE location_id = '$location' GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));
    	} else if ($type !='all' && $location =='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type')item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));

    	} else if ($type !='all' && $location !='all') {
    	
    	$data = DB::select(DB::raw("SELECT item.id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.quantity,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type')item

			LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)sp
			ON sp.item_id = item.id

			LEFT JOIN(SELECT item_id,sum(quantity)as quantity FROM stock_moves WHERE location_id = '$location' GROUP BY item_id)sm
			ON sm.item_id = item.id

			LEFT JOIN(SELECT item_id,SUM(`unit_price`*`quantity_ordered`) as price,SUM(`quantity_ordered`) as received_qty FROM `purch_order_details` GROUP BY `item_id` )pod
			ON pod.item_id = item.id
    		"));

    	}

    	return $data;
    }


    public function getSalesReportDynamic($type, $from = null, $to = null) 
    {
    	$conditions = [];
        if (isset($_GET['location']) && ! empty($_GET['location']) && $_GET['location'] != 'all') {
            $conditions['sale_orders.location_id'] = $_GET['location'];
        }
        if (isset($_GET['customer']) && ! empty($_GET['customer']) && $_GET['customer'] != 'all') {
            $conditions['sale_orders.customer_id'] = $_GET['customer'];
        }
        if (isset($_GET['product']) && ! empty($_GET['product']) && $_GET['product'] != 'all') {
            $conditions['sale_order_details.item_id'] =  $_GET['product'];
        }

        $salesReport = DB::table('sale_orders')
        		->leftJoin('sale_order_details', 'sale_order_details.sale_order_id', '=', 'sale_orders.id');


    }

    public function getSalesHistoryReport($from,$to,$user){
    	$from = DbDateFormat($from);
        $to = DbDateFormat($to);

    	if ($user == 'all') {
    	        $data = DB::select(DB::raw("
				SELECT so.reference,so.order_reference_id,so.customer_id,so.order_date,dm.name,info_table.* FROM sale_orders as so
				
				LEFT JOIN (

				SELECT psd.sale_order_id, SUM(psd.quantity) as quantity,SUM(psd.sale_price_excl_tax) as sale_price_excl_tax,SUM(psd.sale_price_incl_tax) as sale_price_incl_tax,SUM(psd.purchase_price_excl_tax) as purchase_price_excl_tax,SUM(psd.purchase_price_incl_tax) as purchase_price_incl_tax  FROM(SELECT sd.*,(sd.quantity*pd.purchase_rate_incl_tax)as purchase_price_incl_tax,(sd.quantity*pd.purchase_rate_excl_tax)as purchase_price_excl_tax FROM(SELECT sod.sale_order_id,sod.item_id,sod.quantity,(sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_amount)/100) as sale_price_excl_tax,((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_amount)/100)+((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_amount)/100)*itt.tax_rate/100)) as sale_price_incl_tax

				FROM sale_order_details as sod
				LEFT JOIN sale_orders as saleOrder
				on sod.sale_order_id = saleOrder.id
				LEFT JOIN tax_types as itt
				ON itt.id = sod.tax_type_id
				WHERE saleOrder.transaction_type = 202)sd
				LEFT JOIN(SELECT pod.item_id,ROUND(SUM(pod.unit_price*pod.quantity_received)/SUM(pod.quantity_received),2) as purchase_rate_excl_tax,ROUND(SUM(pod.unit_price*pod.quantity_received+pod.unit_price*pod.quantity_received*itt.tax_rate/100)/SUM(pod.quantity_received),2) as purchase_rate_incl_tax FROM purchase_order_details as pod LEFT JOIN tax_types as itt ON itt.id = pod.tax_type_id GROUP BY pod.item_id)pd
				ON sd.item_id = pd.item_id)psd
				GROUP BY psd.sale_order_id

				)info_table
				ON so.id = info_table.sale_order_id
				LEFT JOIN customers as dm
				ON dm.id = so.customer_id
				WHERE so.transaction_type=202 AND  so.`order_date`>= '$from' AND so.`order_date`<= '$to'
				-- ORDER BY so.order_date DESC
				"));
			} else if ($user != 'all') {
    	        $data = DB::select(DB::raw("
				SELECT so.reference,so.order_reference_id,so.customer_id,so.order_date,dm.name,info_table.* FROM sale_orders as so
				
				LEFT JOIN (

				SELECT psd.sale_order_id, SUM(psd.quantity) as quantity,SUM(psd.sale_price_excl_tax) as sale_price_excl_tax,SUM(psd.sale_price_incl_tax) as sale_price_incl_tax,SUM(psd.purchase_price_excl_tax) as purchase_price_excl_tax,SUM(psd.purchase_price_incl_tax) as purchase_price_incl_tax  FROM(SELECT sd.*,(sd.quantity*pd.purchase_rate_incl_tax)as purchase_price_incl_tax,(sd.quantity*pd.purchase_rate_excl_tax)as purchase_price_excl_tax FROM(SELECT sod.sale_order_id,sod.item_id,sod.quantity,(sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_amount)/100) as sale_price_excl_tax,((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_amount)/100)+((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_amount)/100)*itt.tax_rate/100)) as sale_price_incl_tax

				FROM sale_order_details as sod
				LEFT JOIN sale_orders as saleOrder
				on sod.sale_order_id = saleOrder.id
				LEFT JOIN tax_types as itt
				ON itt.id = sod.tax_type_id
				WHERE saleOrder.transaction_type = 202)sd
				LEFT JOIN(SELECT pod.item_id,ROUND(SUM(pod.unit_price*pod.quantity_received)/SUM(pod.quantity_received),2) as purchase_rate_excl_tax,ROUND(SUM(pod.unit_price*pod.quantity_received+pod.unit_price*pod.quantity_received*itt.tax_rate/100)/SUM(pod.quantity_received),2) as purchase_rate_incl_tax FROM purchase_order_details as pod LEFT JOIN tax_types as itt ON itt.id = pod.tax_type_id GROUP BY pod.item_id)pd
				ON sd.item_id = pd.item_id)psd
				GROUP BY psd.sale_order_id

				)info_table
				ON so.id = info_table.sale_order_id
				LEFT JOIN customers as dm
				ON dm.id = so.customer_id
				WHERE so.transaction_type=202 AND so.`order_date`>= '$from' AND so.`order_date`<= '$to' AND so.customer_id = '$user'
				-- ORDER BY so.order_date DESC

				"));
			}
		
		return collect($data);
    }
    // Get profit and sale and cost for last 30 days
    public function getSalesCostProfit(){
    	$from = date('Y-m-d', strtotime('-30 days'));
    	$to = date('Y-m-d');
    	
		$data = DB::select(DB::raw("SELECT scp.* FROM(SELECT info_tbl.order_date, SUM(info_tbl.sales_price_total) as sale ,SUM(info_tbl.purch_price_amount) as cost,SUM(info_tbl.sales_price_total-info_tbl.purch_price_amount)as profit FROM(SELECT final_tbl.order_date,SUM(final_tbl.sales_price) as sales_price_total,SUM(final_tbl.purchase_price) as purch_price_amount FROM(SELECT sod.*,so.order_date,so.order_reference,so.customer_id,(sod.quantity*sod.unit_price-sod.quantity*sod.unit_price*sod.discount_amount/100) as sales_price,(sod.quantity*sod.unit_price*tax.tax_rate/100) as tax_amount,purchase_table.rate as purchase_unit_price,(sod.quantity*purchase_table.rate) as purchase_price FROM(SELECT sales_order_details.order_no,sales_order_details.item_id,sales_order_details.`quantity`,sales_order_details.`unit_price`,sales_order_details.`discount_amount`,sales_order_details.`tax_type_id` as tax_id FROM `sales_order_details` WHERE `trans_type`=202)sod
		LEFT JOIN tax_types as tax
		ON tax.id = sod.tax_id

		LEFT JOIN(SELECT purchase_info.*,(purchase_info.price/purchase_info.purchase_qty) as rate FROM(SELECT purch_tbl.item_id as item_id,SUM(purch_tbl.quantity_received) as purchase_qty,SUM(purch_tbl.price) as price FROM(SELECT pod.`item_id`,pod.`quantity_received`,pod.`unit_price`,(pod.`unit_price`*pod.`quantity_received`) as price FROM `purch_order_details` as pod)purch_tbl GROUP BY purch_tbl.item_id)purchase_info)purchase_table
		ON purchase_table.item_id = sod.item_id

		LEFT JOIN (SELECT * FROM sales_orders) as so
		ON so.order_no = sod.order_no)final_tbl
		GROUP BY final_tbl.order_no)info_tbl
        GROUP BY info_tbl.order_date)scp

	    WHERE scp.order_date BETWEEN '$from' AND '$to'
		"));
    	return $data;
    }

    public function orderToInvoiceList(){
      	$data = DB::select(DB::raw("SELECT so.*,dm.name,COALESCE(sm.quantity,0)as inv_qty FROM(SELECT so.order_no,so.reference,so.order_date,so.customer_id,SUM(sod.quantity)as ord_qty FROM `sales_orders` as so LEFT JOIN sales_order_details as sod ON sod.order_no = so.order_no WHERE so.`trans_type` = 201 GROUP BY sod.order_no )so
				LEFT JOIN(SELECT `order_no`,sum(quantity)as quantity FROM `stock_moves` WHERE `trans_type`=202 GROUP BY `order_no` )sm
				ON 
				sm.order_no = so.order_no
				LEFT JOIN customers as dm 
				ON
				dm.`id` = so.`customer_id`
				ORDER BY so.order_date DESC"
				));
       return $data;    	
    }
    public function orderToshipmentList(){
    	$data = DB::select(DB::raw("SELECT so.*,dm.name FROM(SELECT order_no,reference,order_date,customer_id FROM `sales_orders` WHERE `trans_type`=201 AND `order_reference_id` = 0)so LEFT JOIN(SELECT * FROM `shipment` WHERE status=1 GROUP BY order_no)sp ON sp.order_no = so.order_no LEFT JOIN customers as dm ON dm.`id` = so.`customer_id` WHERE sp.order_no IS NULL ORDER BY so.order_date DESC"));
    	return $data;
    }
    public function latestInvoicesList(){
    	$data = DB::table('sales_orders')
    			->leftjoin("customers",'customers.id','=','sales_orders.customer_id')
    			->where('sales_orders.trans_type', 'SALESINVOICE')
    			->orderBy('sales_orders.order_date','desc')
    			->select('sales_orders.order_reference_id as order_no','sales_orders.order_no as invoice_no','sales_orders.order_reference','sales_orders.reference','customers.name','sales_orders.total','sales_orders.order_date')
    			->take(5)
    			->get();

    	return $data;
    }
    public function latestPaymentList(){
        $data = DB::table('payment_history')
				->leftjoin('customers','customers.id','=','payment_history.customer_id')
				->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
				->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
				->select('payment_history.*','customers.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
				->orderBy('payment_date','DESC')
				->take(5)
				->get();
        return $data;
    }

}
