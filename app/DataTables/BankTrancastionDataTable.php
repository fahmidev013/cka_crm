<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    Transaction
};
use DB;
use Auth;
use Helpers;
use Session;

class BankTrancastionDataTable extends DataTable
{
    public function ajax()
    {
        $bankTrancastion = $this->query();
        return datatables()
        ->of($bankTrancastion)
        ->addColumn('account_name', function ($bankTrancastion) {
            return $bankTrancastion->payment_method_id == 2 ? $bankTrancastion->payment_method .'<br>'. '<a href="'. url('bank/edit-account/transaction/'. $bankTrancastion->account_id) .'">'. $bankTrancastion->name .'</a>' : $bankTrancastion->payment_method;
        })
         ->addColumn('trans_date', function ($bankTrancastion) {
            return formatDate($bankTrancastion->transaction_date);
        })
        ->addColumn('debit', function($bankTrancastion){
            if ($bankTrancastion->amount > 0) {
                return formatCurrencyAmount($bankTrancastion->amount);
            } else {
                return formatCurrencyAmount(0);
            }
        })
        ->addColumn('credit', function($bankTrancastion){
            if ($bankTrancastion->amount < 0) {
                return formatCurrencyAmount(abs($bankTrancastion->amount));
            } else {
                return formatCurrencyAmount(0);
            }
        })
        ->addColumn('trans_type',function($bankTrancastion){
            return ucwords(str_replace ('_', ' ', strtolower($bankTrancastion->transaction_method))) ;
        })
        ->addColumn('reference',function($bankTrancastion){
            return '<a href="'. url('transaction/details/'. $bankTrancastion->id) .'">'. ucfirst($bankTrancastion->reference) .'</a>';
        })
        ->rawcolumns(['account_name', 'description', 'reference'])
        ->make(true);
    }


    public function query()
    {
        $method  = isset($_GET['method']) ? $_GET['method'] : null ;
        $from  = isset($_GET['from']) ? $_GET['from'] : null ;
        $to  = isset($_GET['to']) ? $_GET['to'] : null ;
        $mode  = isset($_GET['mode']) ? $_GET['mode'] : null ;
        $type  = isset($_GET['type']) ? $_GET['type'] : null ;

        $bankTransaction = (new Transaction)->getAllTransactionCollection($from, $to, $method, $mode, $type);

        return $this->applyScopes($bankTransaction);
    }

    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'visible' => false])
        ->addColumn(['data' => 'reference', 'name' => 'transaction_references.code', 'title' => __('Reference')])
        ->addColumn(['data' => 'account_name', 'name' => 'accounts.name', 'title' => __('Payment Method')])
        ->addColumn(['data' => 'trans_type', 'name' => 'transaction_type', 'title' => __('Type')])
        ->addColumn(['data' => 'debit', 'name' => 'amount', 'title' => __('Cash in (Debit)')])
        ->addColumn(['data' => 'credit', 'name' => 'amount', 'title' => __('Cash out (Credit)')])
        ->addColumn(['data' => 'currency_name', 'name' => 'currencies.name', 'title' => __('Currency')])
        ->addColumn(['data' => 'trans_date', 'name' => 'transaction_date', 'title' => __('Date')])
        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [7, 'desc']
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
