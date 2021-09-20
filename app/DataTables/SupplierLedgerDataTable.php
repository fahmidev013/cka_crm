<?php
namespace App\DataTables;
use App\Models\Purchase;
use App\Models\SupplierTransaction;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\Datatables\Services\DataTable;
class SupplierLedgerDataTable extends DataTable
{
    public function ajax()
    {
        $supplier_ledger = $this->query();
        return $this->datatables
        ->of($supplier_ledger)
        
        ->addColumn('serial', function ($supplier_ledger) {

            return 1; 
        })

        ->addColumn('transaction_date', function ($supplier_ledger) {

            return formatDate($supplier_ledger->transaction_date); 
        })

        ->addColumn('paid_amount', function ($supplier_ledger) {

            return 100; 
        })

        ->addColumn('bill_amount', function ($supplier_ledger) {

            return 120; 
        })

        ->addColumn('bill_amount', function ($supplier_ledger) {

            return 0; 
        })

        
        ->make(true);
    }

    public function query()
    {
        $id = $this->supplier_id;
        $po = Purchase::where('supplier_id',$id)->select('reference','ord_date as transaction_date','total')->get()->toArray();
        $payment = SupplierTransaction::where('supplier_id',$id)->select('purch_order_reference','transaction_date','amount')->get()->toArray();
        $merge   = array_merge($po, $payment);
        usort($merge, "custom_sort");
        $supplier_ledger = $merge;
    
        return $this->applyScopes($supplier_ledger);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'serial', 'name' => 'serial', 'title' => __('S/N')])

        ->addColumn(['data' => 'transaction_date', 'name' => 'transaction_date', 'title' => __('Date')])

        ->addColumn(['data' => 'paid_amount', 'name' => 'paid_amount', 'title' => __('Paid amount')])

        ->addColumn(['data' => 'bill_amount', 'name' => 'bill_amount', 'title' => __('Bill amount')])

        ->addColumn(['data' => 'balance', 'name' => 'balance', 'title' => __('Balance')])

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

}
