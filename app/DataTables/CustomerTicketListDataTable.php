<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    Ticket,
    TicketStatus
};
use Auth;
use DB;
use Session;
use Helpers;
use Carbon;

class CustomerTicketListDataTable extends DataTable{
    public function ajax()
    {
        $tickets = $this->query();

        return datatables()
            ->of($tickets)
            ->addColumn('action', function ($tickets) {
                $edit = (Helpers::has_permission(Auth::user()->id, 'edit_ticket')) ? '<a href="' . url("ticket/edit/" . $tickets->id) . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                $delete = (Helpers::has_permission(Auth::user()->id, 'delete_ticket')) ? '
                <form method="post" action="' . url("ticket/delete") . '" id="delete-ticket-'. $tickets->id .'" class="display_inline_block">
                ' . csrf_field() . '
                <input type="hidden" name="ticket_id" value="'. $tickets->id .'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='. $tickets->id .' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete ticket') . '" data-message="' . __('Are you sure to delete this ticket?') . '">
                                <i class="feather icon-trash-2"></i>
                            </button>
                </form>
                ' : '';
                return $edit . $delete;
            })
            ->addColumn('id', function ($tickets) {
                return "<a href='" . url("ticket/reply/". base64_encode($tickets->id)) . "'>". $tickets->id ."</a>";
            })
            ->addColumn('subject', function ($tickets) {
                $priority = '<span class="badge priority-style" id="'. strtolower($tickets->priority) .'-priority">' . $tickets->priority . '</span>';
                if ($tickets->project_name) {
                    $id = "<a href='". url("ticket/reply/". base64_encode($tickets->id)) ."'>". $tickets->subject ."</a><br/><a href='" . url("project/details/". $tickets->project_table_id) . "' class='project-name'>". $tickets->project_name ."</a>";
                } else {
                    $id = "<a href='". url("ticket/reply/". base64_encode($tickets->id)) ."'>". $tickets->subject ."</a>";
                }
                return $id .'<br>'. $priority;

            })
            ->addColumn('assignee', function ($tickets) {
                $assign = '';
                if (Helpers::has_permission(Auth::user()->id, 'edit_team_member')) {
                    $assign .= '<a href="'. url("user/team-member-profile/". $tickets->assignee_id) .'">'. $tickets->assignee_name .'</a>';
                } else {
                    $assign .= $tickets->assignee_name;
                }
                return  $assign;
            })
            ->addColumn('status', function ($tickets) {
                $allstatus = '';
                $ticketStatus = TicketStatus::getAll()->where('id', '!=', $tickets->ticket_status_id);
                foreach ($ticketStatus as $key => $value) {
                    $allstatus .= '<li class="status-styles"><a class="ticket_status_change f-14 color_black" ticket_id="'. $tickets->id .'" data-id="'. $value->id .'" data-value="'. $value->name .'">'. $value->name .'</a></li>';
                }
                $top = '<div class="btn-group">
                <button style="color:'. $tickets->color .' !important" type="button" class="badge text-white f-12 dropdown-toggle task-status-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                '. $tickets->status .'&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu scrollable-menu task-priority-name w-150p" role="menu">';
                $last = '</ul></div>&nbsp';

                return $top . $allstatus . $last;
            })
            ->addColumn('last_reply', function ($tickets) {
                return $tickets->last_reply ?  formatDate($tickets->last_reply) .'<br>'. getTime($tickets->last_reply)  :  __('Not Replied Yet') ;
            })
            ->addColumn('date', function ($tickets) {
                return formatDate($tickets->date) .'<br>'. getTime($tickets->date);
            })
            ->addColumn('department', function ($tickets) {
                if (strlen($tickets->department_name) > 12) {
                    return '<span data-toggle="tooltip" data-placement="right"  data-original-title="'. $tickets->department_name .'">'. substr_replace($tickets->department_name, "..", 12) .'</span>';
                } else {
                    return $tickets->department_name;
                }
            })
            ->rawcolumns(['action', 'id', 'assignee', 'subject', 'status', 'last_reply', 'date', 'department', 'priority_name'])
            ->make(true);
    }

    public function query()
    {
        $id = $this->customer_id;
        $from     = isset($_GET['from'])     ? $_GET['from']     : null;
        $to       = isset($_GET['to'])       ? $_GET['to']       : null;
        $status   = isset($_GET['status'])   ? $_GET['status']   : null;
        $project  = isset($_GET['project'])  ? $_GET['project']  : null;
        $departmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;

        $tickets = (new Ticket())->getAllTicketDT($from, $to, $status, $project, $departmentId, $id);
        return $this->applyScopes($tickets);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => __('#')])
            ->addColumn(['data' => 'subject', 'name' => 'tickets.subject', 'title' => __('Subject')])
            ->addColumn(['data' => 'assignee', 'name' => 'users.full_name', 'title' => __('Assignee')])
            ->addColumn(['data' => 'department', 'name' => 'departments.name', 'title' => __('Department') ])
            ->addColumn(['data' => 'status', 'name' => 'ticket_statuses.name', 'title' => __('Status')])
            ->addColumn(['data' => 'last_reply', 'name' => 'tickets.last_reply', 'title' => __('Last Reply')])
            ->addColumn(['data' => 'date', 'name' => 'date', 'title' => __('Created At')])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            ->parameters([
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
}
