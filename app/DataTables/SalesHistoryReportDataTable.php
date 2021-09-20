<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Report;

class SalesHistoryReportDataTable  extends DataTable
{
    public function ajax()
    {
        $salesHistory = $this->query();
        return datatables()
            ->of($salesHistory)
            ->addColumn('ord_date', function ($salesHistory) {
                return formatDate($salesHistory->ord_date);
            })
            ->addColumn('reference', function ($salesHistory) {
                return '<a href="'.url('invoice/view-detail-invoice/' . $salesHistory->sales_order_id).'">'.$salesHistory->reference.'</a>';
            })

            ->addColumn('name', function ($salesHistory) {
              return '<a href="'.url('customer/edit/'.$salesHistory->customer_id).'">'.$salesHistory->name.'</a>';

            })

             ->addColumn('cost_value', function ($salesHistory) {
                $cost_value=$salesHistory->purchase_price_incl_tax;
                return $this->currency->symbol.' '.number_format($cost_value,2,'.',',') ;

            })

            ->addColumn('sales_value', function ($salesHistory) {
                $sales_value=$salesHistory->sale_price_excl_tax;
                return $this->currency->symbol.' '.number_format($sales_value,2,'.',',') ;

            })

            ->addColumn('tax', function ($salesHistory) {
                $tax=$salesHistory->sale_price_incl_tax-$salesHistory->sale_price_excl_tax;
                return $this->currency->symbol.' '.number_format($tax,2,'.',',') ;

            })

             ->addColumn('profit', function ($salesHistory) {
                $profit = ($salesHistory->sale_price_excl_tax-$salesHistory->purchase_price_incl_tax);
             return $this->currency->symbol.' '.number_format($profit,2,'.',',') ;

             })

             ->addColumn('profit_margin', function ($salesHistory) {
              $qty = 0;
              $sales_price = 0;
              $purchase_price = 0;
              $tax = 0;
              $total_profit = 0;

              $profit = ($salesHistory->sale_price_excl_tax-$salesHistory->purchase_price_incl_tax);

              if($salesHistory->purchase_price_incl_tax<=0){
                  $profit_margin = 100;
              }else{
                $profit_margin = ($profit*100)/$salesHistory->purchase_price_incl_tax;
            }
            return $this->currency->symbol.' '.number_format($profit_margin,2,'.',',').'%';

            })

           ->rawcolumns(['reference','name'])

            ->make(true);
    }
 
    public function query()
    { 

        if (isset($_GET['btn']))
        {  
            $user = $_GET['customer'];
            $from = $_GET['from'];
            $to   = $_GET['to'];

        }else{ 
            
            $user = 'all';
            $from = formatDate(date('Y-m-d', strtotime("-30 days")));
            $to   = formatDate(date('d-m-Y'));
        }
        $salesHistory = (new Report)->getSalesHistoryReport($from,$to,$user);
        return $this->applyScopes($salesHistory);
    }
    
    public function html()
    {
        return $this->builder()
            
            ->addColumn(['data' => 'ord_date', 'name' => 'ord_date', 'title' => __('Date')])

            ->addColumn(['data' => 'reference', 'name' => 'reference', 'title' => __('Order no')])
            
            ->addColumn(['data' => 'name', 'name' => 'customers.name', 'title' => __('Customer')])
           
            ->addColumn(['data' => 'quantity', 'name' => 'quantity', 'title' => __('Quantity')])

            ->addColumn(['data' => 'sales_value', 'name' => 'sale_price_excl_tax', 'title' => __('Sales value').' '.'('.$this->currency->symbol.')'])

            ->addColumn(['data' => 'cost_value', 'name' => 'cost_value', 'title' => __('Cost').' '.'('.$this->currency->symbol.')'])
            
            ->addColumn(['data' => 'tax', 'name' => 'tax', 'title' => __('Tax').' '.'('.$this->currency->symbol.')'])

            ->addColumn(['data' => 'profit', 'name' => 'profit', 'title' => __('Profit').' '.'('.$this->currency->symbol.')'])

            ->addColumn(['data' => 'profit_margin', 'name' => 'profit_margin', 'title' => __('Profit margin').'%'])
            
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [1, 'asc']
            ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'customers_' . time();
    }
}
