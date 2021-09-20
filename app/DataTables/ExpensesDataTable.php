<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Expense;
class ExpensesDataTable extends DataTable
{
    public function ajax()
    {
        $expenses = $this->query();
        return datatables()
        ->of($expenses)
        ->addColumn('action', function ($expenses) {
            $edit = Helpers::has_permission(Auth::user()->id, 'edit_expense') ? '<a title="'. __('Edit') .'" href="'.url('expense/edit-expense/'.$expenses->id).'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
            if(Helpers::has_permission(Auth::user()->id, 'delete_expense')){
                $delete= '<form method="post" action="'.url('expense/delete/'.$expenses->id).'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$expenses->id.'"> 
                '.csrf_field().'
                <button title="'. __('Delete') .'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$expenses->id.'"  data-target="#theModal" data-label="Delete" data-title="'. __('Delete expense').'" data-message="'. __('Are you sure to delete this expense?').'">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })

        ->addColumn('category', function ($expenses) {
            return $expenses->incomeExpenseCategory->name;
        })

        ->addColumn('paymentMethod', function ($expenses) {
            $paymentMethodName = isset($expenses->paymentMethod->name) ? $expenses->paymentMethod->name : '';
            $transactionAccountNumber = isset($expenses->transaction->account->account_number) ? '<br>'.$expenses->transaction->account->account_number : '';
            return $paymentMethodName.$transactionAccountNumber;
        })

        ->addColumn('note', function ($expenses) {
            $note = strip_tags($expenses->note);
            if (strlen($note) > 37) {
                return '<span data-toggle="tooltip" data-placement="right"  data-original-title="'.$note.'">'.substr_replace($note, "..", 37).'</span>';
            } else {
                return $note;
            }
        })

        ->addColumn('amount', function ($expenses) {
            return formatCurrencyAmount($expenses->amount) ;
        })


        ->addColumn('currency', function ($expenses) {
            return $expenses->incomeExpenseCurrency->name;
        })
        

         ->addColumn('transaction_date', function ($expenses) {
            return formatDate($expenses->transaction_date);
        })

         ->rawcolumns(['account_name','account_no','action','amount', 'note', 'transaction_date', 'paymentMethod'])

         ->make(true);
    }
        
    public function query()
    {

        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $categoryId = isset($_GET['categoryName']) ? $_GET['categoryName'] : null;
        $methodId = isset($_GET['methodName']) ? $_GET['methodName'] : null;    
        $expense = Expense::select('expenses.*');
         if (!empty($from)) {
           $expense->where('expenses.transaction_date', '>=', DbDateFormat($from));
         }
         if (!empty($to)) { 
           $expense->where('expenses.transaction_date', '<=', DbDateFormat($to));
         } 
         if ($categoryId && $categoryId != 'all') {
            $expense->where('expenses.income_expense_category_id', $categoryId);
         }
         if ($methodId && $methodId != 'all') {
            $expense->where('expenses.payment_method_id', $methodId);
         }
         if (Helpers::has_permission(Auth::user()->id, 'own_expense')) {
            $id = Auth::user()->id;
            $expense->where('expenses.user_id',$id);
        } 
        $expense->with(['transaction','transaction.account', 'incomeExpenseCategory', 'paymentMethod', 'incomeExpenseCurrency'])->get();  

        return $this->applyScopes($expense);
    }
    
    public function html()
    {
        return $this->builder()


        ->addColumn(['data' => 'category', 'name' => 'incomeExpenseCategory.name', 'title' => __('Category')])

        ->addColumn(['data' => 'paymentMethod', 'name' => 'paymentMethod.name', 'title' => __('Payment method')])

        ->addColumn(['data' => 'note', 'name' => 'expenses.note', 'title' => __('Description')])

        ->addColumn(['data' => 'amount', 'name' => 'expenses.amount', 'title' => __('Amount')])

        ->addColumn(['data' => 'currency', 'name' => 'incomeExpenseCurrency.name', 'title' => __('Currency')])

        ->addColumn(['data' => 'transaction_date', 'name' => 'expenses.transaction_date', 'title' => __('Date')])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [0, 'asc'],
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
