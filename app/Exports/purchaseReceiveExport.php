<?php

namespace App\Exports;

use App\Models\ReceivedOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class purchaseReceiveExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $to     = $_GET['to'];
        $from   = $_GET['from'];
        $supplier = $_GET['supplier'];

        $receiveList = (new ReceivedOrder)->getAllPurchaseReceiveOrder($from,$to,$supplier)->latest('id');
        return $receiveList;
    }

    public function headings(): array
    {
        return [
            'Receive No',
            'Purchase No',
            'Order Receive No',
            'Supplier Name',
            'Receive Quantity',
            'Receive Date',
        ];
    }

    public function map($purchase): array
    {
        $total_receive = "0";
        if ($purchase->receivedOrderDetails->sum('quantity') > 0) {
            $total_receive = $purchase->receivedOrderDetails->sum('quantity');
        }
        return [
            $purchase->ro_id,
            $purchase->reference,
            isset($purchase->order_receive_no) && !empty($purchase->order_receive_no) ? $purchase->order_receive_no : '-',
            $purchase->supplier->name,
            formatCurrencyAmount($total_receive),
            formatDate($purchase->receive_date),
        ];
    }
}
