<?php
namespace App\DataTables;

use App\Models\Location;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class StockAdjustmentDataTable extends DataTable
{
    private $i = 1;
    public function ajax()
    {
        $stockAdjustment = $this->query();
        return datatables()
        ->of($stockAdjustment)
        ->addColumn('action', function ($stockAdjustment) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_stock_adjustment')){
                $edit = '<a title="Edit" class="btn btn-xs btn-info" href="' . url('adjustment/edit/' . $stockAdjustment->id) . '"><span class="feather icon-edit"></span></a>&nbsp;';
            }else{
                $edit='';
            }

            if(Helpers::has_permission(Auth::user()->id, 'delete_stock_adjustment')){
                $delete ='  <form method="POST" action="' . url("adjustment/delete") . '" accept-charset="UTF-8" class="display_inline" id="delete-item-' . $stockAdjustment->id . '">
                ' . csrf_field() . '
                <input type="hidden" name="id" value="' . $stockAdjustment->id . '">
                <input type="hidden" name="trans_type" value="' . $stockAdjustment->transaction_type . '">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="' . $stockAdjustment->id . '" data-target="#theModal" data-label="Delete" data-title="' . __('Delete adjustment') . '" data-message="' . __('Are you sure to delete this stock adjustment?') . '">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })
        ->addColumn('id', function ($stockAdjustment) {
            return '<a href="' . url('adjustment/view-details/' . $stockAdjustment->id) . '">' . sprintf("%04d", $stockAdjustment->id) . '</a>';
        })
       ->addColumn('trans_type', function ($stockAdjustment) {
            if($stockAdjustment->transaction_type == 'STOCKIN'){
                return "Stock In";
            }elseif($stockAdjustment->transaction_type == 'STOCKOUT'){
                return "Stock Out";
            }
        }) 

       ->addColumn('location', function ($stockAdjustment) {
            return Location::getLocationName($stockAdjustment->location_id);
        })

       ->addColumn('total', function ($stockAdjustment) {
            return formatCurrencyAmount($stockAdjustment->total_quantity);
        })
        
        ->addColumn('date', function ($stockAdjustment) {
            return !empty($stockAdjustment->transaction_date) ? formatDate($stockAdjustment->transaction_date) : '';
        })
        
        ->rawcolumns(['trans_type', 'id', 'action', 'location', 'total', 'date'])

        ->make(true);
    }

    public function query()
    {
        if (isset($_GET['btn']))
        {  
            $trans_type = $_GET['trans_type'];
            $location   =  $_GET['destination'];
            $from     = $_GET['from'];
            $to       = $_GET['to'];

        }else{ 
            $trans_type = null;
            $location   = null;
            $from     = null;
            $to       = null;
        }
        $stockAdjustment = DB::table('stock_adjustments')
                        ->leftjoin('locations','locations.id','=','stock_adjustments.location_id')
                        ->select('stock_adjustments.*','locations.name');
        if ($from) {
            
             $stockAdjustment->where('transaction_date', '>=', DbDateFormat($from));             
        }
        if ($to) {
             $stockAdjustment->where('transaction_date', '<=', DbDateFormat($to));             
        }
        if($trans_type){
            $stockAdjustment->where('transaction_type', '=', $trans_type);
        }
        if($location){
            $stockAdjustment->where('location_id', '=', $location);
        } 

        if (Helpers::has_permission(Auth::user()->id, 'own_stock_adjustment') && !Helpers::has_permission(Auth::user()->id, 'manage_stock_adjustment')) {
            $id = Auth::user()->id;
            $stockAdjustment->where('user_id', $id);
        }   
        
        return $this->applyScopes($stockAdjustment);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'stock_adjustments.id', 'title' => __('S/N')])

        ->addColumn(['data' => 'trans_type', 'name' => 'stock_adjustments.transaction_type', 'title' => __('Transaction type')])

        ->addColumn(['data' => 'location', 'name' => 'locations.name', 'title' => __('Location')])

        ->addColumn(['data' => 'total', 'name' => 'stock_adjustments.total_quantity', 'title' => __('Quantity')])

        ->addColumn(['data' => 'date', 'name' => 'stock_adjustments.transaction_date', 'title' => __('Date'), 'orderable' => false])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [0, 'desc']
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
        return 'stocks_' . time();
    }
}
