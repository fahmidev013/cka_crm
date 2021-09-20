<?php

namespace App\Exports;

use App\Models\StockTransfer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class stockTransferExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        $source        = isset($_GET['source']) ? $_GET['source'] : null;
        $destination   = isset($_GET['destination']) ? $_GET['destination'] : null;
        $from          = isset($_GET['from']) ? $_GET['from'] : null;
        $to            = isset($_GET['to']) ? $_GET['to'] : null;
        
        $stockTransfer = StockTransfer::with(['sourceLocation:id,name', 'destinationLocation:id,name']);
        if ($from) {
            $stockTransfer->where('transfer_date', '>=', DbDateFormat($from));             
        }
        if ($to) {
            $stockTransfer->where('transfer_date', '<=', DbDateFormat($to));             
        }
        if ($source) {
            $stockTransfer->where('source_location_id', '=', $source);
        }
        if ($destination) {
            $stockTransfer->where('destination_location_id', '=', $destination);
        }
        return $stockTransfer->orderBy('id','desc');
    }

    public function headings(): array
    {
        return [
            'Transfer No',
            'Source',
            'Destination',
            'Note',
            'Quantity',
            'Date'
        ];
    }
    
    public function map($stock): array
    {
        return [
            sprintf("%04d", $stock->id),
            isset( $stock->sourceLocation->name ) ? $stock->sourceLocation->name : "" ,
            isset( $stock->destinationLocation->name ) ? $stock->destinationLocation->name : "",
            $stock->note,
            formatCurrencyAmount($stock->quantity),
            timeZoneformatDate($stock->transfer_date)
        ];
    }
}
