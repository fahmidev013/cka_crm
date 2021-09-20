<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\GeneralLedger;
use App\Models\Project;
use Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;


class GlTransactionDataTable extends DataTable{
    public function ajax()
    {
        $general_ledgers   = $this->query();
        return datatables()
            ->of($general_ledgers)

            ->addColumn('reference_id', function ($general_ledgers) {

                return $general_ledgers->reference;
            })

            ->addColumn('gl_account_id', function ($general_ledgers) {

                return $general_ledgers->name;
            })
            
             ->addColumn('debit', function ($general_ledgers) {
                if ($general_ledgers->amount > 0) {
                    $debit = $general_ledgers->amount;
                }else{
                    $debit = 0.00;
                }
                return number_format($debit,2,'.',',');
                
            })

             ->addColumn('credit', function ($general_ledgers) {
                if ($general_ledgers->amount <= 0) {
                    $credit = abs($general_ledgers->amount);
                }else{
                    $credit = 0.00;
                }

                 return number_format($credit,2,'.',',');
                
            }) 
           


            ->addColumn('transaction_date', function ($general_ledgers) {
                $date = formatDate($general_ledgers->transaction_date);
                return $date;
            })

            ->make(true);

        
    }

    public function query()
    {
        if (isset($_GET['btn']))
        {
            $gl_account_no = $_GET['gl_account_no'];
            $from          = $_GET['from'];
            $to            = $_GET['to'];

        }
        else
        {
            $gl_account_no = NULL;
            $from          = NULL;
            $to            = NULL;
        }

        $general_ledgers = DB::table('general_ledger_transactions')
                                ->leftJoin('reference', 'general_ledger_transactions.reference_id', 'reference.id')
                                ->leftJoin('income_expense_categories', 'general_ledger_transactions.gl_account_id', 'income_expense_categories.id');
        if($from){
          $general_ledgers->where('transaction_date', '>=', DbDateFormat($from));
        }
        if($to){
          $general_ledgers->where('transaction_date', '<=', DbDateFormat($to));
        }
        if($gl_account_no){
          $general_ledgers->where('gl_account_id', '=', $gl_account_no);
        }
        $general_ledgers->select('general_ledger_transactions.*', 'reference.reference', 'income_expense_categories.name');
        return $this->applyScopes($general_ledgers);
    }

    public function html()
    {
        return $this->builder()
            
            ->addColumn(['data' => 'reference_id', 'name' => 'reference.reference', 'title' => __('Reference') ])

            ->addColumn(['data' => 'gl_account_id', 'name' => 'income_expense_categories.name', 'title' => __('GL Account') ])

            ->addColumn(['data' => 'comment', 'name' => 'comment', 'title' => __('Note') ])


            ->addColumn(['data' => 'debit', 'name' => 'amount', 'title' => __('Debit') ])

            ->addColumn(['data' => 'credit', 'name' => 'amount', 'title' => __('Credit') ])

            ->addColumn(['data' => 'transaction_date', 'name' => 'general_ledger_transactions.transaction_date', 'title' => __('Date') ])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [5, 'desc']
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