<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Controllers\CustomerController;

class customerLedgerExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $balance = 0;
    public function collection() {

        $from = isset($_GET['from'])     ? $_GET['from']     : formatDate(date('Y-m-d', strtotime("-30 days")));
        $to   = isset($_GET['to'])       ? $_GET['to']       : formatDate(date('d-m-Y')) ;
        $id   = isset($_GET['id']) ? $_GET['id'] : null;
        
        $data = CustomerController::getCustomerLedger($from, $to, $id);
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Invoice No',
            'Paid Amount ',
            'Bill Amount',
            'Balance'
        ];
    }

    public function map($data): array
    {
        $customerData = Customer::find($data['customer_id']);
        $total = empty($data['total']) ? 0 : $data['total'];
        $amount = empty($data['amount']) ? 0 : $data['amount'];
        $this->balance += $total;
        $this->balance -= $amount;
        if (!empty($data["reference"])) {
            $reference = $data["reference"];
        } else if (isset($data['sale_order']['reference'])) {
            $reference = $data['sale_order']["reference"];
        }
        return [
            formatDate($data['transaction_date']),
            $reference,
            formatCurrencyAmount($amount, isset($customerData->currency->name) && !empty($customerData->currency->name) ? $customerData->currency->name : ''),
            formatCurrencyAmount($total, isset($customerData->currency->name) && !empty($customerData->currency->name) ? $customerData->currency->name : ''),
            formatCurrencyAmount($this->balance, isset($customerData->currency->name) && !empty($customerData->currency->name) ? $customerData->currency->name : '')
        ];
    }
}
