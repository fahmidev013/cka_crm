<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Account;
class BankListDataTable extends DataTable
{
    public function ajax()
    {
        $banks = $this->query();
        return datatables()
            ->of($banks)
            ->addColumn('action', function ($banks) {
                $edit = $delete = '';
                $edit = Helpers::has_permission(Auth::user()->id, 'edit_bank_account') ? '<a href="'.url('bank/edit-account/edit/'.$banks->id).'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
                if(Helpers::has_permission(Auth::user()->id, 'delete_bank_account')) {
                    $delete = $banks->is_default == 1 ? '' : '<form method="post" action="'.url('bank/delete/'.$banks->id).'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$banks->id.'"> 
                    '.csrf_field().'
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$banks->id.'" data-target="#theModal" data-label="Delete" data-title="' . __('Bank account delete').'" data-message="' . __('Are you sure to delete this account?').'">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
                }
             return $edit.$delete;
            })

           ->addColumn('balance', function ($banks) {
                $balance =  $banks->transactions->sum('amount') ;

                return  formatCurrencyAmount($balance) ;
            })
            ->addColumn('bank_address', function ($banks) {
                return $banks->bank_address;
            })

            ->addColumn('account_name', function ($banks) {
                return '<a href="'.url('bank/edit-account/transaction/'.$banks->id).'">'.$banks->name.'</a><br>( '. $banks->accountType['name'] .' )';
            })

            ->addColumn('bank_name', function ($banks) {
                
                return $banks->bank_name;
            })

            ->addColumn('account_number', function ($banks) {
                if (strlen($banks->account_number) > 12) {
                    return '<span data-toggle="tooltip" data-placement="right"  data-original-title="'.$banks->account_number.'">'.substr_replace($banks->account_number, "..", 12).'</span>';
                } else {
                    return $banks->account_number;
                }

            })

            ->rawcolumns(['account_name','action', 'balance', 'bank_address', 'account_type_name', 'bank_name', 'account_number'])

            ->make(true);
    }
 
    public function query()
    {
        $banks = Account::with([
                           'accountType' => function ($query) {
                            $query->select('id','name');
                            },
                            'currency' => function ($query) {
                            $query->select('id','name');
                            }, 'transactions'
                          ])->where('accounts.is_deleted','!=',1)->select('accounts.*');
        return $this->applyScopes($banks);
    }
    
    public function html()
    {
        return $this->builder()
                        
            ->addColumn(['data' => 'id', 'name' => 'id', "visible"=> false])
           
            ->addColumn(['data' => 'account_name', 'name' => 'name', 'title' =>  __('A/c Name')])
            
            ->addColumn(['data' => 'account_number', 'name' => 'accounts.account_number', 'title' => __('A/c Number')])
           
            ->addColumn(['data' => 'bank_name', 'name' => 'accounts.bank_name', 'title' => __('Bank Name')])

            ->addColumn(['data' => 'currency.name', 'name' => 'currency.name', 'title' => __('Currency')])

            ->addColumn(['data' => 'bank_address', 'name' => 'accounts.bank_address', 'title' => __('Bank Address')])

             ->addColumn(['data' => 'balance', 'name' => 'balance', 'title' => __('Balance'), 'orderable' => false])

            ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            
            ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
             'order' => [0, 'desc']
            ]);
    }
}
