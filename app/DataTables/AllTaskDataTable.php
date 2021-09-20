<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    Task,
    Project,
    Priority,
    TaskStatus
};
use Auth;
use DB;
use Session;
use Helpers;
use Cache;


class AllTaskDataTable extends DataTable
{
    public function ajax()
    {
        $tasks = $this->query();

        return datatables()
            ->of($tasks)
            ->addColumn('action', function ($tasks) {
                $project_id = $tasks->related_to_type == 1 ? $tasks->related_to_id : null;
                $edit = (Helpers::has_permission(Auth::user()->id, 'edit_task')) ? '<a href="' . url("task/edit/". $tasks->id) . '" title="'. __('Edit') .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                $delete = (Helpers::has_permission(Auth::user()->id, 'delete_task')) ? '
                        <form method="post" action="'. url("task/delete") .'" class="display_inline" id="delete-item-'. $tasks->id .'">
                        ' . csrf_field() . '
                        <input type="hidden" name="task_id" value="'. $tasks->id .'">
                        <input type="hidden" name="project_id" value="'. $project_id .'">
                        <button title="'. __('Delete') .'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $tasks->id .'" data-target="#theModal" data-label = "Delete" data-title="' . __('Delete task') . '" data-message="' . __('Are you sure to delete this task?') . '">
                                        <i class="feather icon-trash-2"></i>
                                    </button>
                        </form>&nbsp;
                        ' : '';

                return $edit . $delete;
            })
            ->addColumn('start_date', function ($tasks) {
                return isset($tasks->start_date) ? formatDate($tasks->start_date) : '';
            })
            ->addColumn('name', function ($tasks) {
                $priority = '<span class="badge priority-style" id="'. strtolower($tasks->priority_name) .'-priority">' . $tasks->priority_name . '</span>';
                if ($tasks->related_to_type == 1) {
                    $project = Project::getAll()->where('id', $tasks->related_to_id)->first();
                    $projectName = !empty($project->name) ? $project->name : '';
                    $id = Helpers::has_permission(Auth::user()->id, 'own_task') ? '<a href="'. url('task/v/'. $tasks->id) .'" class="task_class"  data-id="'. $tasks->id .'" data-priority-id= "'. $tasks->priorityId .'"  project_id = "'. $tasks->related_to_id .'" data-status-id= "'. $tasks->status_id .'"  type="button"  data-toggle="modal" data-target="#task-modal">'. $tasks->name .'</a><br/><a href="'. url("project/details/". $tasks->related_to_id) .'" class="f-12 customer-task">'. $projectName .'</a>' : '<span>'. $tasks->name .'</span><br/><a href="'. url("project/details/". $tasks->related_to_id) .'" class="f-12 customer-task">'. $projectName .'</a>';
                } else if ($tasks->related_to_type == 2) {
                    $customer = (new Task())->taskCustomerName($tasks->related_to_id);
                    $customer_name = !empty($customer->first_name) ? $customer->first_name .' '. $customer->last_name : '';
                    $id = Helpers::has_permission(Auth::user()->id, 'own_task') ?  '<a href="" class="task_class"  data-id="'. $tasks->id .'" data-priority-id= "'. $tasks->priorityId .'"  project_id = "'. $tasks->related_to_id .'" data-status-id= "'. $tasks->status_id .'"  type="button"  data-toggle="modal" data-target="#task-modal">'. $tasks->name .'</a><br/><a href="'. url("customer/edit/". $tasks->related_to_id) .'" class="f-12 customer-task">'. $customer_name .'</a>' : '<span>'. $tasks->name .'</span><br/><a href="'. url("customer/edit/". $tasks->related_to_id) .'" class="f-12 color: #777">'. $customer_name .'</a>';
                } else if($tasks->related_to_type == 3) {
                    $ticket = (new Task())->taskTicketSubject($tasks->related_to_id);
                    $ticketSubject = !empty($ticket->subject) ? $ticket->subject : '';
                    $id = Helpers::has_permission(Auth::user()->id, 'own_task') ? '<a href="" class="task_class"  data-id="'. $tasks->id .'" data-priority-id= "'. $tasks->priorityId .'"  project_id = "'. $tasks->related_to_id .'" data-status-id= "'. $tasks->status_id .'"  type="button"  data-toggle="modal" data-target="#task-modal">'.$tasks->name .'</a><br/><a href="'. url("ticket/reply/". base64_encode($tasks->related_to_id)) .'" class="f-12 customer-task">'. $ticketSubject .'</a>' : '<span>'. $tasks->name .'</span><br/><a href="'. url("ticket/reply/". base64_encode($tasks->related_to_id)) .'" class="f-12 customer-task">'. $ticketSubject .'</a>';
                } else {
                     $id = Helpers::has_permission(Auth::user()->id, 'own_task') ? '<a href="" class="task_class"  data-id="'. $tasks->id .'" data-priority-id= "'. $tasks->priorityId .'"  project_id = "'. $tasks->related_to_id .'" data-status-id= "'. $tasks->status_id .'"  type="button"  data-toggle="modal" data-target="#task-modal">'. $tasks->name .'</a>' : '<span>'. $tasks->name .'</span>';
                }

                if (isset($tasks->not_end) && ($tasks->not_end != null)) {
                   $timer = '<i class="feather icon-clock color_red"></i>&nbsp&nbsp';
                } else {
                   $timer = '';
                }

                return $timer . $id .'<br>'. $priority;
            })
            ->addColumn('assigne', function ($tasks) {
                $assigne = (new Task())->taskAssignsList($tasks->id);
                $assign = '';
                foreach($assigne as $assigne) {
                    if (Helpers::has_permission(Auth::user()->id, 'edit_team_member')) {
                        $assign .= '<a href="'. url('user/team-member-profile/'. $assigne->user_id) .'">'. $assigne->user_name .'</a><br>';
                    } else {
                        $assign .= $assigne->user_name;
                    }
                }
                return  $assign;
            })
            ->addColumn('due_date', function ($tasks) {
                return $tasks->due_date ? timeZoneformatDate($tasks->due_date) : '-';
            })
            ->addColumn('status_name', function ($tasks) {
                $allstatus = $status = $complete = '';
                $taskStatus = TaskStatus::getAll()->where('id', '!=', $tasks->status_id)->where('id', '!=', 6);
                $statusReopen = TaskStatus::getAll()->whereNotIn('id', [$tasks->status_id, 1, 2]);
                foreach ($taskStatus as $key => $value) {
                    $allstatus .= '<li class="status-styles"><a class="status_change f-14 color_black" project_id="'. $tasks->related_to_id .'" data-id="'. $value->id .'" data-value="'. $value->name .'" task_id="'.$tasks->id.'" >'. $value->name .'</a></li>';
                }
                if ($tasks->status_name == "Complete") {
                    $complete .= '<li class="status-styles"><a class="status_change f-14 color_black" project_id="'. $tasks->related_to_id .'" data-id="6" data-value="Re-open" task_id="'. $tasks->id .'">' . __('Re-open') . '</a></li>';
                } else if ($tasks->status_name == "Re-open") {
                    foreach ($statusReopen as $key => $value) {
                        $status .= '<li class="status-styles"><a class="status_change f-14 color_black" project_id="'. $tasks->related_to_id .'" data-id="'. $value->id .'" data-value="'. $value->name .'" task_id="'. $tasks->id .'" >'. $value->name .'</a></li>';
                    }
                }
                $top = '<div class="btn-group">
                <button style="color:'. $tasks->color .' !important" type="button" class="badge text-white f-12 dropdown-toggle task-status-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                '. $tasks->status_name .'&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu scrollable-menu status_change task-priority-name w-150p" role="menu">';
                $last = '</ul></div>&nbsp';

                if ($tasks->status_name == "Re-open") {
                    return $top . $status . $last;
                } else if ($tasks->status_name == "Complete") {
                    return $top . $complete . $last;
                } else {
                    return $top . $allstatus . $last;
                }
            })
            ->rawColumns(['action','start_date','name','assigne','due_date','priority_name','status_name'])
            ->make(true);
    }


    public function query()
    {
        $from      = isset($_GET['from'])     ? $_GET['from']      : null;
        $to        = isset($_GET['to'])       ? $_GET['to']        : null;
        $status    = isset($_GET['status'])   ? $_GET['status']    : null;
        $project   = isset($_GET['project'])  ? $_GET['project']   : null;
        $assignee  = isset($_GET['assignee']) ? $_GET['assignee']  : Auth::user()->id;

        if (isset($_GET['reset_btn'])) {
            $from = $to = $status = $project = $assignee = $priority = null;
        }

        $tasks = (new Task())->getAllTaskForDT($from, $to, $status, $project, $assignee, null);
        return $this->applyScopes($tasks);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'tasks.id', 'visible' => false,  'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'name', 'name' => 'tasks.name', 'title' => __('Name')])
            ->addColumn(['data' => 'assigne', 'name' => 'task_assigns.user_id', 'orderable' => false, 'searchable' => false, 'title' => __('Assignee')])
            ->addColumn(['data' => 'start_date', 'name' => 'tasks.start_date', 'title' => __('Start Date')])
            ->addColumn(['data' => 'due_date', 'name' => 'tasks.due_date', 'title' => __('Due Date')])
            ->addColumn(['data' => 'status_name', 'name' => 'task_statuses.name', 'title' => __('Status')])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [3, 'desc']
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

