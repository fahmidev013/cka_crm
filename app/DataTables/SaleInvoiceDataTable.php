<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
use App\Models\Preference;
use App\Models\Currency;

class SaleInvoiceDataTable extends DataTable
{
    public function ajax()
    {
        $saleInvoice = $this->query();
        return  datatables()
        ->of($saleInvoice)
        ->addColumn('reference', function ($saleInvoice) {
             return '<a href="' . url('invoice/view-detail-invoice/' . $saleInvoice->so_id) . '">' . $saleInvoice->reference . '</a>';
        })
        ->addColumn('name', function ($saleInvoice) {
            if (Helpers::has_permission(Auth::user()->id, 'edit_customer')) {
                if (isset($saleInvoice->customer->name)) {
                    $name = '<a href="' . url('customer/edit/' . $saleInvoice->customer_id) . '">' . $saleInvoice->customer->name . '</a> (' . $saleInvoice->currency->name . ')';
                    if (isset($saleInvoice->project->name) && mb_strlen($saleInvoice->project->name) > 20) {
                        $saleInvoice->project->name = mb_substr($saleInvoice->project->name, 0, 20) . '...';
                    }
                    $project = isset($saleInvoice->project->name) ? "<br><a href='". url('project/details/' . $saleInvoice->project_id) ."'>" . $saleInvoice->project->name . "</a>" : "";
                    return $name . $project;
                }
            } else if (isset($saleInvoice->customer->name)) {
                return !empty($saleInvoice->customer->name) ? $saleInvoice->customer->name : "";
            } else if (isset($saleInvoice->currency->name)) {
                return "<strong>". __('Walking customer') ."</strong>(" . !empty($saleInvoice->currency->name) ? $saleInvoice->currency->name : "". ")";
            }
        })
        ->addColumn('total', function ($saleInvoice) {
            return formatCurrencyAmount($saleInvoice->total);
        })
        ->addColumn('paid', function ($saleInvoice) {
            return formatCurrencyAmount($saleInvoice->paid);
        })
        ->addColumn('status', function ($saleInvoice) {
            if ($saleInvoice->paid == 0  && $saleInvoice->total != 0) {
                return '<span class="badge text-white f-12 customer-invoice color-f44236">' . __('Unpaid') . '</span>';
            } else if ($saleInvoice->paid > 0 && $saleInvoice->total > $saleInvoice->paid) {
                return '<span class="badge text-white f-12 customer-invoice color-04a9f5">' . __('Partially paid') . '</span>';
            } else if ($saleInvoice->paid <= $saleInvoice->paid || $saleInvoice->paid == 0) {
                return '<span class="badge text-white f-12 customer-invoice color-04a9f5">' . __('Paid'). '</span>';
            }
        })
        ->addColumn('order_date', function ($saleInvoice) {
            return isset($saleInvoice->order_date) ? formatDate($saleInvoice->order_date) : '';
        })
        ->addColumn('location', function ($saleInvoice) {
            return isset($saleInvoice->location->name) ? $saleInvoice->location->name : '';
        })
        ->addColumn('action', function ($saleInvoice) {
            $edit = $delete = "";
            if ($saleInvoice->transaction_type != "POSINVOICE") {
                $edit = Helpers::has_permission(Auth::user()->id, 'edit_invoice') ? '<a title="' . __("Edit") . '" href="' . url('invoice/edit/' . $saleInvoice->so_id) . '" class="btn btn-xs btn-primary float-right invoice-edit"><i class="feather icon-edit"></i></a>&nbsp;' : '';
            }
            if (Helpers::has_permission(Auth::user()->id, 'delete_invoice')) {
                $delete = '<form method="post" action="' . url('invoice/delete/' . $saleInvoice->so_id) . '" accept-charset="UTF-8" class="display_inline" id="delete-item-' . $saleInvoice->so_id . '">
                    ' . csrf_field() . '
                    <button  title="' . __("Delete") . '"class="btn btn-xs btn-danger float-right ml-2 invoice-delete" type="button" data-toggle="modal" data-id="' . $saleInvoice->so_id . '" data-target="#theModal" data-label="Delete" data-title="' . __('Delete invoice') . '" data-message="' . __('Are you sure to delete this invoice? This will delete all related transaction data.') . '">
                    <i class="feather icon-trash-2"></i>
                    </button>
                    </form>';
            }

            return $delete . $edit;
        })
        ->rawColumns(['action', 'reference', 'name', 'status', 'total', 'paid', 'order_date', 'location'])
        ->make(true);
    }

    public function query()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $transactionType  = isset($_GET['transactionType']) ? $_GET['transactionType'] : NULL;
        $saleInvoice = (new SaleOrder)->getAllInvoices($from, $to, $customer, $location, $currency, $status);
        if (!empty($transactionType)) {
            $saleInvoice = $saleInvoice->where('transaction_type', $transactionType);
        }
        return $this->applyScopes($saleInvoice);
    }

    public function html()
    {
        return $this->builder()


        ->addColumn(['data' => 'reference', 'name' => 'sale_orders.reference', 'title' => __('Invoice no')])

        ->addColumn(['data' => 'name', 'name' => 'customer.name', 'title' => __('Customer name')])

        ->addColumn(['data' => 'location', 'name' => 'location.name', 'title' => __('Location')])

        ->addColumn(['data' => 'total', 'name' => 'sale_orders.total', 'title' => __('Total price')])

        ->addColumn(['data' => 'paid', 'name' => 'sale_orders.paid', 'title' => __('Paid amount')])

        ->addColumn(['data' => 'currency', 'name' => 'currency.name', 'title' => __('Currency'), 'visible' => false])

        ->addColumn(['data' => 'status', 'name' => 'paid', 'title' => __('Status'), 'orderable' => false, 'searchable' => false])

        ->addColumn(['data' => 'order_date', 'name' => 'sale_orders.order_date', 'title' => __('Date'), 'searchable' => false])

        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

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
        return 'customers_' . time();
    }
}
