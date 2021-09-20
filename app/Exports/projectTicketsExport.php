<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class projectTicketsExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        $from     = isset($_GET['from'])     ? $_GET['from']     : null;
        $to       = isset($_GET['to'])       ? $_GET['to']       : null;
        $status   = isset($_GET['status'])   ? $_GET['status']   : null; 
        $project  = isset($_GET['project'])  ? $_GET['project']  : null; 
        $departmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;
        $assigneeId = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $ticketList = (new Ticket())->getAllTicketDT($from, $to, $status, $project, $departmentId, null, null, $assigneeId)->orderBy('last_reply','desc');
        return $ticketList;
    }

    public function headings(): array
    {
        return [
            'Subject',
            'Assignee',
            'Project Name',
            'Department',
            'Customers',
            'Status',
            'Priority',
            'Last Reply',
            'Created At'
        ];
    }
    
    public function map($ticket): array
    {  
        return [
            $ticket->subject,
            $ticket->assignee_name,
            $ticket->project_name,
            $ticket->department_name,
            $ticket->name,
            $ticket->status,
            $ticket->priority,
            $ticket->last_reply && $ticket->last_reply != $ticket->date ?  timeZoneformatDate($ticket->last_reply).'  '.timeZonegetTime($ticket->last_reply)  :  __('Not Reply Yet'),
            timeZoneformatDate($ticket->date).'   '.timeZonegetTime($ticket->date)
        ];
    }
}
