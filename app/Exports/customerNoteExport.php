<?php

namespace App\Exports;

use App\Models\Note;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class customerNoteExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        $to   = isset($_GET['to']) ? $_GET['to'] : null ;
        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $id   = isset($_GET['customerId']) ? $_GET['customerId'] : null;

        $data = (new Note)->getAllNoteByCustomerCSV($from, $to, $id);
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Subject',
            'Note',
            'Created At',
        ];
    }

    public function map($note): array
    {
        return [
            $note->subject,
            $note->content,
            timeZoneformatDate($note->created_at) . ' ' . timeZonegetTime($note->created_at)
        ];
    }
}
