<?php

namespace App\Exports;

use App\Models\StockAdjustment;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class stockAdjustmentExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $trans_type = isset( $_GET['trans_type'] ) ? $_GET['trans_type'] : null;
        $location   = isset( $_GET['location']) ? $_GET['location'] : null;
        $from     = isset( $_GET['from']) ? $_GET['from'] : null;
        $to       = isset( $_GET['to']) ? $_GET['to'] : null;        
        
        $stockAdjustment = StockAdjustment::with('location:id,name');
        if ($from) {
             $stockAdjustment->where('transaction_date', '>=', DbDateFormat($from));             
        }
        if ($to) {
             $stockAdjustment->where('transaction_date', '<=', DbDateFormat($to));             
        }
        if ($trans_type) {
            $stockAdjustment->where('transaction_type', '=', $trans_type);
        }
        if ($location) {
            $stockAdjustment->where('location_id', '=', $location);
        }
        return $stockAdjustment->orderBy('id');
    }

    public function headings(): array
    {
        return [
            __('S/N'),
            __('Transaction Type'),
            __('Location'),
            __('Quantity'),
            __('Note'),
            __('Date')
        ];
    }
    
    public function map($adjustment): array
    {
        return [
            sprintf("%04d", $adjustment->id),
            $adjustment->transaction_type == 'STOCKIN' ? "Stock In" : "Stock Out",
            isset( $adjustment->location->name ) ? $adjustment->location->name : "N/A",
            formatCurrencyAmount($adjustment->total_quantity),
            $adjustment->note,
            timeZoneformatDate($adjustment->transaction_date)
        ];
    }
}
