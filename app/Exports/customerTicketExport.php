<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class customerTicketExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $to           = isset($_GET['to']) ? $_GET['to'] : null ;
        $from         = isset($_GET['from']) ? $_GET['from'] : null ;
        $customer     = isset($_GET['customer']) ? $_GET['customer'] : null ;
        $status       = isset($_GET['status'])   ? $_GET['status']   : null;
        $project      = isset($_GET['project'])  ? $_GET['project']  : null;
        $departmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;
        $data         = (new Ticket())->getAllTicketDT($from, $to, $status, $project, $departmentId, $customer)->orderBy('id', 'desc');

        return $data;
    }

    public function headings(): array
    {
        return [
            'Subject',
            'Assignee',
            'Project Name',
            'Department',
            'Status',
            'Priority',
            'Last Reply',
            'Created at',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->subject,
            $ticket->assignee_name,
            $ticket->project_name,
            $ticket->department_name,
            $ticket->status,
            $ticket->priority,
            formatDate($ticket->last_reply).'   '.getTime($ticket->last_reply),
            formatDate($ticket->date).'   '.getTime($ticket->date)
        ];
    }
}
