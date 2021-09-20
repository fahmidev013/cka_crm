<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Report;

class InventoryStockOnHandDataTable  extends DataTable
{
    public function ajax()
    {
        $onHand = $this->query();
        return datatables()
            ->of($onHand)
            ->addColumn('description', function ($onHand) {
              return $onHand->description . ' (' . $onHand->item_id . ')' ."<br/> <span class='f-13 color-3c8dbc'>". $onHand->category_name . "</span>";
            })

            ->addColumn('purchase_price', function ($onHand) {
              return formatCurrencyAmount($onHand->purchase_price, $this->currency->symbol);
            })

            ->addColumn('retail_price', function ($onHand) {
                return formatCurrencyAmount($onHand->retail_price, $this->currency->symbol);
            })

            ->addColumn('in_value', function ($onHand) {
                $inValue = 0;
                if($onHand->available_qty != 0 ){
                  $inValue = $onHand->purchase_price * $onHand->available_qty;
                }
                return formatCurrencyAmount($inValue, $this->currency->symbol);
            })

            ->addColumn('retail_value', function ($onHand) {
                $retailValue = 0;
                if($onHand->available_qty != 0 ){
                  $retailValue = $onHand->retail_price * $onHand->available_qty;
                }
                return formatCurrencyAmount($retailValue, $this->currency->symbol);
            })

            ->addColumn('available_qty', function ($onHand) {
                return formatCurrencyAmount($onHand->available_qty);
            })

            ->addColumn('profit_value', function ($onHand) {
              $inValue = 0;
              $retailValue = 0;
              $profitValue = 0;
              $profitMargin = 0;
              if($onHand->available_qty != 0) {
                $retailValue = $onHand->retail_price * $onHand->available_qty;
                $inValue = $onHand->purchase_price * $onHand->available_qty;
              }
              
              $profitValue = ($retailValue - $inValue);
              if ($inValue != 0) {
                $profitMargin = ($profitValue*100/$inValue);
              }
              return formatCurrencyAmount($profitValue, $this->currency->symbol) . '<br>' . formatCurrencyAmount($profitMargin) . '%';
            })
          
          ->rawcolumns(['description', 'purchase_price', 'retail_price', 'in_value', 'retail_value', 'profit_value', 'available_qty'])
          ->make(true);
    }
 
    public function query()
    { 
      $type = isset($_GET['type']) ? $_GET['type'] :'all';
      $location = isset($_GET['location']) ? $_GET['location'] : 'all';
      $onHand = (new Report)->getInventoryStockOnHand($type, $location);
      return $this->applyScopes($onHand);
    }
    
    public function html()
    {
        return $this->builder()
            
            ->addColumn(['data' => 'description', 'name' => 'description', 'title' => __('Product').' ('. __('Stock id') .')'])

            ->addColumn(['data' => 'item_id', 'name' => 'item_id', 'title' => __('Stock id'), 'visible' => false])
            
            ->addColumn(['data' => 'available_qty', 'name' => 'available_qty', 'title' => __('In stock')])

            ->addColumn(['data' => 'purchase_price', 'name' => 'purchase_price', 'title' => __('Purchase price')])
           
            ->addColumn(['data' => 'retail_price', 'name' => 'retail_price', 'title' => __('Retail price')])

            ->addColumn(['data' => 'in_value', 'name' => 'in_value', 'title' => __('In value')])

            ->addColumn(['data' => 'retail_value', 'name' => 'retail_value', 'title' => __('Retail value')])

            ->addColumn(['data' => 'profit_value', 'name' => 'profit_value', 'title' => __('Profit value')])

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
