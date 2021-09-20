<?php
namespace App\DataTables;

use App\Models\Transfer;
use Auth;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class BankTransferDataTable extends DataTable
{
    public function ajax()
    {
        $bankTransfer = $this->query();
        return datatables()
            ->of($bankTransfer)
            ->addColumn('action', function ($bankTransfer) {
                if (Helpers::has_permission(Auth::user()->id, 'delete_balance_transfer')) {
                    $delete = '<form method="post" action="' . url('transfer/delete/' . $bankTransfer->id) . '" accept-charset="UTF-8" class="display_inline_block" id="delete-item-' . $bankTransfer->id . '">
                    ' . csrf_field() . '
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="' . $bankTransfer->id . '" data-target="#theModal" data-label="Delete" data-title="' . __('Delete transfer') . '" data-message="' . __('Are you sure to delete this transfer?') . '">
                    <i class="feather icon-trash-2"></i>
                    </button>
                    </form>';
                } else {
                    $delete = '';
                }
                return $delete;
            })

            ->addColumn('from_account', function ($bankTransfer) {
                return '<a href="' . url('bank/edit-account/transaction/' . $bankTransfer->from_account_id) . '">' . $bankTransfer->fromBank->name . '</a><br>' . $bankTransfer->fromBank->currency->name ;
            })

            ->addColumn('to_account', function ($bankTransfer) {
                return '<a href="' . url('bank/edit-account/transaction/' . $bankTransfer->to_account_id) . '">' . $bankTransfer->toBank->name . '</a><br>' . $bankTransfer->toBank->currency->name;
            })

            ->addColumn('transaction_date', function ($bankTransfer) {
                return formatDate($bankTransfer->transaction_date);
            })

            ->addColumn('amount', function ($bankTransfer) {
                $txt = formatCurrencyAmount($bankTransfer->amount) . '<br>' . $bankTransfer->fromBank->currency->name ;
                return $txt;

            })

            ->addColumn('incoming_amount', function ($bankTransfer) {
                if ($bankTransfer->incoming_amount > 0) {
                    return formatCurrencyAmount($bankTransfer->incoming_amount) . '<br>' . $bankTransfer->toBank->currency->name ;
                }
                return formatCurrencyAmount($bankTransfer->incoming_amount);
            })

            ->addColumn('reference',function($bankTransfer) {
                return '<a href="'.url('transfer/details/'.$bankTransfer->id).'">'.ucfirst($bankTransfer->transactionReference->code).'</a>';
            })


            ->rawcolumns(['to_account', 'amount', 'from_account', 'action', 'incoming_amount', 'reference'])

            ->make(true);
    }

    public function query()
    {

        $from_bank_id = isset($_GET['from_bank_id']) ? $_GET['from_bank_id'] : null ;
        $to_bank_id   = isset($_GET['to_bank_id']) ? $_GET['to_bank_id'] : null ;
        $from         = isset($_GET['from']) ? $_GET['from'] : null ;
        $to           = isset($_GET['to']) ? $_GET['to'] : null ;

        $bankTransfer = Transfer::with('fromBank', 'toBank', 'currency', 'transactionReference')->select('transfers.*');
        if (!empty($from)) {
            $bankTransfer->where('transaction_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
            $bankTransfer->where('transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($from_bank_id)) {
            $bankTransfer->where('from_account_id', '=', $from_bank_id);
        }
        if (!empty($to_bank_id)) {
            $bankTransfer->where('to_account_id', '=', $to_bank_id);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_balance_transfer')) {
            $id = Auth::user()->id;
            $bankTransfer->where('user_id',$id);
        } 
        
        return $this->applyScopes($bankTransfer);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'reference', 'name' => 'transactionReference.code', 'title' => __('Reference')])

            ->addColumn(['data' => 'from_account', 'name' => 'fromBank.name', 'title' => __('From Bank A/c')])

            ->addColumn(['data' => 'to_account', 'name' => 'toBank.name', 'title' => __('To Bank A/c')])

            ->addColumn(['data' => 'amount', 'name' => 'transfers.amount', 'title' => __('Transfer Amount')])

            ->addColumn(['data' => 'incoming_amount', 'name' => 'transfers.incoming_amount', 'title' => __('Incoming Amount')])

            ->addColumn(['data' => 'transaction_date', 'name' => 'transfers.transaction_date', 'title' => __('Date')])

            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order'      => [0, 'desc'],
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
