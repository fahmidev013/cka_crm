<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    Ticket,
    TicketStatus
};
use DB;
use Auth;
use Helpers;
use Session;

class CustomerPanelTicketListDataTable extends DataTable
{
    public function ajax()
    {
        $ticketList   = $this->query();
        return datatables()
            ->of($ticketList)
            ->addColumn('ticket', function ($ticketList) {
                return '<a href='. url('customer-panel/support/reply/'. base64_encode($ticketList->id)) .'>'. $ticketList->id .'</a>';
            })
            ->addColumn('subject', function ($ticketList) {
                $priority = '<span class="badge priority-style" id="'. strtolower($ticketList->priority) .'-priority">' . $ticketList->priority . '</span>';
                return "<a href='". url("customer-panel/support/reply/". base64_encode($ticketList->id)) ."'>". $ticketList->subject ."</a><br/><span class='f-bold f-12 color_709A52'>".$ticketList->project_name."</span>".'<br>'. $priority;;
            })
            ->addColumn('department', function ($ticketList) {
                if (strlen($ticketList->department_name) > 12) {
                    return '<span data-toggle="tooltip" data-placement="right"  data-original-title="'. $ticketList->department_name .'">'.substr_replace($ticketList->department_name, "..", 12).'</span>';
                } else {
                    return $ticketList->department_name;
                }
            })
            ->addColumn('status', function ($ticketList) {
                $allstatus = '';
                if ($ticketList->ticket_status_id != 4 ) {
                    $ticketStatus = TicketStatus::getAll()->where('id', '!=', $ticketList->ticket_status_id);
                    $ticketStatus->where('id', 4);
                }
                if (!empty($ticketStatus)) {
                    foreach ($ticketStatus as $key => $value) {
                        $allstatus .= '<li class="properties"><a class="ticket_status_change f-14 color_black" ticket_id="'. $ticketList->id .'" data-id="'. $value->id .'" data-value="'. $value->name .'">'. $value->name .'</a></li>';
                    }
                    $top = '<div class="btn-group">
                    <button style="color:'. $ticketList->color .' !important" type="button" class="badge text-white f-12 dropdown-toggle task-status-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    '. $ticketList->status .'&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu w-150p task-priority-name" role="menu">';
                } else {
                    $top = '<div class="btn-group">
                    <button style="color:'. $ticketList->color .' !important" type="button" class="badge text-white f-12 task-status-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    '. $ticketList->status .'&nbsp;<span class="caret"></span>
                    </button>';
                }
                $last = '</ul></div>&nbsp';

                if ($ticketList->ticket_status_id == 4) {
                    return '<div class="btn-group">
                    <button style="color:'. $ticketList->color .' !important" type="button" class="badge text-white f-12 task-status-name">
                    '. $ticketList->status .'&nbsp;<span class="caret"></span>
                    </button></div>&nbsp';
                }
                return $top . $allstatus . $last;
            })
            ->addColumn('last_reply', function ($ticketList) {
                $ticketList->last_reply = isset($ticketList->last_reply) ? $ticketList->last_reply : null;
                return ($ticketList->last_reply && $ticketList->last_reply != $ticketList->date) ? timeZoneformatDate($ticketList->last_reply) .'<br>'. timeZonegetTime($ticketList->last_reply) : __('Not Replied Yet');
            })
            ->addColumn('date', function ($ticketList) {
                return $ticketList->date ?  timeZoneformatDate($ticketList->date) ."<br>". timeZonegetTime($ticketList->date)  :  __('Created At') ;
            })
            ->rawcolumns(['ticket', 'subject', 'department', 'status', 'last_reply', 'date'])
            ->make(true);
    }

    public function query()
    {
        $id = Auth::guard('customer')->user()->id;
        $from     = isset($_GET['from'])     ? $_GET['from']     : null;
        $to       = isset($_GET['to'])       ? $_GET['to']       : null;
        $status   = isset($_GET['status'])   ? $_GET['status']   : null;
        $project  = isset($_GET['project'])  ? $_GET['project']  : null;
        $departmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;

        $tickets = (new Ticket())->getAllTicketDT($from, $to, $status, $project, $departmentId, $id, 'customerPanel');
        return $this->applyScopes($tickets);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'project_name', 'name' => 'projects.name', 'visible' => false])
            ->addColumn(['data' => 'ticket', 'name' => 'tickets.id', 'title' => __('#')])
            ->addColumn(['data' => 'subject', 'name' => 'tickets.subject', 'title' => __('Subject')])
            ->addColumn(['data' => 'department', 'name' => 'departments.name', 'title' => __('Department') ])
            ->addColumn(['data' => 'status', 'name' => 'ticket_statuses.name', 'title' => __('Status')])
            ->addColumn(['data' => 'last_reply', 'name' => 'tickets.last_reply', 'title' =>__('Last Reply'), 'searchable' => false])
            ->addColumn(['data' => 'date', 'name' => 'tickets.date', 'title' =>__('Created At'), 'orderable' => false, 'searchable' => false])
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [6, 'desc']
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
        return 'customer_tickets' . time();
    }
}
