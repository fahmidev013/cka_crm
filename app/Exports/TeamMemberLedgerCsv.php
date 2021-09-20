<?php

namespace App\Exports;

use App\Models\User;
use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Http\Controllers\DateTime;

class TeamMemberLedgerCsv implements FromCollection, WithHeadings, WithMapping 
{    
    public function collection()
    {
        $from = ($_GET['from'] == "" || $_GET['from'] == null) ? DbDateFormat($_GET['from']) : $_GET['from'];
        $to = ($_GET['to'] == "" || $_GET['to'] == null) ? DbDateFormat(date('Y-m-d')) : $_GET['to'];        
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : 'all';
        $location = isset($_GET['location']) ? $_GET['location'] : 'all';
        $team_member = isset($_GET['team_member']) ? $_GET['team_member'] : 'all';
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $purchData = (new PurchaseOrder)->getAllPurchOrderByUserId($from, $to, $supplier, $location, $team_member, $status)->orderBy('purchase_orders.id','DESC')->get();
        return collect($purchData);
    }

    public function headings(): array
    {
        return[
            __('Purchase Number'),
            __('Supplier Name'),
            __('Total'),
            "Paid Amount",
            __('Currency'),
            "Status",
            __('Purchase Date')
        ];
    }

    public function map($purchData): array
    {
        if ($purchData->paid <= 0 && $purchData->total != 0) {
          $status = 'Unpaid';
        } else if ($purchData->paid > 0 && $purchData->total > $purchData->paid) {
          $status = 'Partially Paid';
        } else if ($purchData->total <= $purchData->paid) {
          $status = 'Paid';
        }
        return[
            $purchData->reference,
            $purchData->name,
            formatCurrencyAmount($purchData->total),
            formatCurrencyAmount($purchData->paid),
            $purchData->currency_name,
            $status,
            formatDate($purchData->order_date)
        ];
    }
}
