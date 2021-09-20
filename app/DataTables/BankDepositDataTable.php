<?php
namespace App\DataTables;

use App\Models\Deposit;
use Auth;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class BankDepositDataTable extends DataTable
{
    public function ajax()
    {
        $bankDeposit = $this->query();
        return datatables()
            ->of($bankDeposit)
            ->addColumn('action', function ($bankDeposit)
            {
                $edit = Helpers::has_permission(Auth::user()->id, 'edit_deposit') ? '<a href="' . url('deposit/edit-deposit/' . $bankDeposit->id) . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                if (Helpers::has_permission(Auth::user()->id, 'delete_deposit'))
                {
                    $delete= '<form method="post" action="'.url('deposit/delete/'.$bankDeposit->id).'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$bankDeposit->id.'"> 
                        '.csrf_field().'
                        <input type="hidden" name="id" value="' . $bankDeposit->id . '">
                        <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$bankDeposit->id.'" data-target="#theModal" data-label="Delete" data-title="' . __('Delete deposit').'" data-message="' . __('Are you sure to delete this deposit?').'">
                        <i class="feather icon-trash-2"></i> 
                        </button>
                        </form>';

                }
            else
            {
                    $delete = '';
                }
                return $edit . $delete;
            })

            ->addColumn('account_name', function ($bankDeposit)
            {
                return '<a href="' . url('bank/edit-account/transaction/' . $bankDeposit->account_id) . '">' . $bankDeposit->account->name . '</a>';
            })

            ->addColumn('description', function ($bankDeposit) {
                if (strlen($bankDeposit->description) > 12) {
                    return '<span data-toggle="tooltip" data-placement="right"  data-original-title="'.$bankDeposit->description.'">'.substr_replace($bankDeposit->description, "..", 12).'</span>';
                } else {
                    return $bankDeposit->description;
                }
            })

            ->addColumn('transaction_date', function ($bankDeposit)
            {
                return formatDate($bankDeposit->transaction_date);
            })

            ->addColumn('account_no', function ($bankDeposit)
            {
                if (!empty($bankDeposit->account) && strlen($bankDeposit->account->account_number) > 15) {
                    return '<span data-toggle="tooltip" data-placement="right"  data-original-title="'.$bankDeposit->account->account_number.'">'.substr_replace($bankDeposit->account->account_number, "..", 12).'</span>';
                } else {
                    return $bankDeposit->account->account_number;
                }
            })
            ->addColumn('amount', function ($bankDeposit)
            {
                return formatCurrencyAmount($bankDeposit->amount);
            })

            ->addColumn('currency', function ($bankDeposit)
            {
                return isset($bankDeposit->account->currency) ? $bankDeposit->account->currency->name : '';
            })

            ->rawcolumns(['action','account_name', 'account_no', 'amount', 'transaction_date', 'description', 'currency'])

            ->make(true);
    }

    public function query()
    {

         $from       = isset($_GET['from']) ? $_GET['from'] : null;
         $to         = isset($_GET['to']) ? $_GET['to'] : null;
         $account_no = isset($_GET['account_no']) ? $_GET['account_no'] : null;

        $bankDeposit = Deposit::with('account')->select('deposits.*');
        if (!empty($from)) {
            $bankDeposit->where('transaction_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
            $bankDeposit->where('transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($account_no) && $account_no != 'all') {
            $bankDeposit->where('account_id', '=', $account_no);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_deposit')) {
            $id = Auth::user()->id;
            $bankDeposit->where('user_id',$id);
        } 
        return $this->applyScopes($bankDeposit);
    }

    public function html()
    {
        return $this->builder()

            ->addColumn(['data' => 'account_name', 'name' => 'account.name', 'title' => __('A/c Name')])

            ->addColumn(['data' => 'account_no', 'name' => 'account.account_number', 'title' => __('A/c Number')])

            ->addColumn(['data' => 'description', 'name' => 'deposits.description', 'title' => __('Description')])

            ->addColumn(['data' => 'amount', 'name' => 'deposits.amount', 'title' => __('Amount')])
            
            ->addColumn(['data' => 'currency', 'name' => 'account.currency_id', 'title' => __('Currency')])

            ->addColumn(['data' => 'transaction_date', 'name' => 'deposits.transaction_date', 'title' => __('Date')])

            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order'      => [5, 'desc'],
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
