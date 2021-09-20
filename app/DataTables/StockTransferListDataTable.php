<?php
namespace App\DataTables;
use Auth;
use DB;
use Helpers;
use Session;
use App\Models\StockTransfer;
use Yajra\DataTables\Services\DataTable;

class StockTransferListDataTable extends DataTable
{
    public function ajax()
    {
        $stockTransfer = $this->query();
        return datatables()
        ->of($stockTransfer)
        ->addColumn('action', function ($stockTransfer) {
            $edit = Helpers::has_permission(Auth::user()->id, 'edit_stock_transfer') ? '<a title="Edit" class="btn btn-xs btn-info" href="'.url('stock_transfer/edit/'.$stockTransfer->sto_id).'"><span class="feather icon-edit"></span></a>&nbsp;': '';

            if(Helpers::has_permission(Auth::user()->id, 'delete_stock_transfer')){
                $delete ='  <form method="POST" action="'.url("stock_transfer/delete").'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$stockTransfer->sto_id.'">
                '.csrf_field().'
                <input type="hidden" name="id" value="'.$stockTransfer->sto_id.'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$stockTransfer->sto_id.'" data-target="#theModal" data-label="Delete" data-title="'. __('Delete transfer') .'" data-message="'. __('Are you sure to delete this stock transfer?') .'">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })
        ->addColumn('id', function ($stockTransfer) {
            return '<a href="'.url('stock_transfer/view-details/'.$stockTransfer->sto_id).'">'.sprintf("%04d", $stockTransfer->sto_id).'</a>';
        })

       ->addColumn('source', function ($stockTransfer) {
            return isset( $stockTransfer->sourceLocation->name ) ? $stockTransfer->sourceLocation->name : "" ;
        }) 

       ->addColumn('destination', function ($stockTransfer) {
            return isset( $stockTransfer->destinationLocation->name ) ? $stockTransfer->destinationLocation->name : "";
        })

       ->addColumn('qty', function ($stockTransfer) {
            return formatCurrencyAmount($stockTransfer->quantity);
        })
        
        ->addColumn('transfer_date', function ($stockTransfer) {
            return !empty($stockTransfer->transfer_date) ? formatDate($stockTransfer->transfer_date) : '';
        })
        ->rawcolumns(['id', 'action', 'source', 'destination', 'qty', 'transfer_date'])

        ->make(true);
    }

    public function query()
    {
        $source      = isset($_GET['source']) ? $_GET['source'] : null;
        $destination = isset($_GET['destination']) ? $_GET['destination'] : null;
        $from        = isset($_GET['from']) ? $_GET['from'] : null;
        $to          = isset($_GET['to']) ? $_GET['to'] : null;
        
        $stockTransfer   = StockTransfer::select('stock_transfers.id as sto_id', 'stock_transfers.source_location_id', 'stock_transfers.destination_location_id', 'stock_transfers.transfer_date', 'stock_transfers.quantity')->with(['sourceLocation:id,name', 'destinationLocation:id,name']);
        if ($from) {
             $stockTransfer->where('transfer_date', '>=', DbDateFormat($from));             
        }
        if ($to) {
             $stockTransfer->where('transfer_date', '<=', DbDateFormat($to));             
        }
        if ($source) {
            $stockTransfer->where('source_location_id', '=', $source);
        }
        if ($destination) {
            $stockTransfer->where('destination_location_id', '=', $destination);
        }

        if (Helpers::has_permission(Auth::user()->id, 'own_stock_transfer') && !Helpers::has_permission(Auth::user()->id, 'manage_stock_transfer')) {
            $id = Auth::user()->id;
            $stockTransfer->where('user_id', $id);
        }
        return $this->applyScopes($stockTransfer);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'stock_transfers.id', 'title' => __('S/N')])

        ->addColumn(['data' => 'source', 'name' => 'stock_transfers.source_location_id', 'title' => __('Source')])   

        ->addColumn(['data' => 'destination', 'name' => 'destinationLocation.name', 'visible' => false, 'orderable' => false])

        ->addColumn(['data' => 'source', 'name' => 'sourceLocation.name', 'visible' => false, 'orderable' => false])

        ->addColumn(['data' => 'destination', 'name' => 'stock_transfers.destination_location_id', 'title' => __('Destination')])

        ->addColumn(['data' => 'qty', 'name' => 'stock_transfers.quantity', 'title' => __('Quantity')])

        ->addColumn(['data' => 'transfer_date', 'name' => 'stock_transfers.transfer_date', 'title' => __('Date'), 'searchable' => false])

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
