<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\TaskTimer;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;
use Cache;


class TimesheetDataTable extends DataTable{
    public function ajax()
    {
        $timeSheet = $this->query();

        return datatables()
            ->of($timeSheet)
            ->addColumn('action', function ($timeSheet) {

                $delete = '<form method="post" action="' . url("timesheet/delete/$timeSheet->id") . '" class="display_inline" id="delete-item-'. $timeSheet->id .'">
                    ' . csrf_field() . '
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger  ml-2" type="button" data-toggle="modal" data-id="'. $timeSheet->id .'" data-target="#theModal" data-label = "Delete" data-title="' . __('Delete timesheet') . '" data-message="' . __('Are you sure to delete this timesheet?') . '">
                                    <i class="feather icon-trash-2"></i> 
                                </button>
                    </form>';
                return $delete;
            })
            ->addColumn('assigner', function ($timeSheet) {
                $assigner ='<a href="'.url('user/team-member-profile/'.$timeSheet->user_id).'">'.$timeSheet->full_name.'</a><br>';
                return $assigner;
            })
            ->addColumn('task_name', function ($timeSheet) {
                $project = $timeSheet->related_to_type == 1 ? "<a href='".url('project/details/'.$timeSheet->project_id)."'><p class='f-12 color_709A52'>".$timeSheet->name."<p></a>" : '';

                $task_name = !empty($timeSheet->taskName) ? "<a href='".url('task/v/'.$timeSheet->task_id)."'>".$timeSheet->taskName. "</a>".$project : '';
                return $task_name;
            })
            ->addColumn('start_time', function ($timeSheet) {
                $startDate = timeZoneformatDate(date("m/d/Y h:i:s A T", $timeSheet->start_time));
                $start_time = timeZonegetTime(date("m/d/Y h:i:s A T", $timeSheet->start_time));
                return $startDate."<br>".$start_time;
            })
            ->addColumn('end_time', function ($timeSheet) {
                if ($timeSheet->end_time != "") {
                    $endDate = timeZoneformatDate(date("m/d/Y h:i:s A T", $timeSheet->end_time));
                    $end_time = timeZonegetTime(date("m/d/Y h:i:s A T", $timeSheet->end_time));
                    return $endDate."<br>".$end_time;
                }
                return  "";
            })
            ->addColumn('time_spent', function ($timeSheet) {
                if (!empty($timeSheet->end_time)) {
                    $diff = ($timeSheet->end_time > $timeSheet->start_time) ? ($timeSheet->end_time - $timeSheet->start_time) : null;
                } else {
                    $timeSheet->start_time = (int) $timeSheet->start_time;
                    $diff = (time() > $timeSheet->start_time) ?  time() - $timeSheet->start_time : null;
                }
                $hours    = floor($diff / 3600) > 0 ? floor($diff / 3600) . 'h ' : '';
                $minutes  = floor(($diff / 60) % 60) > 0 ? floor(($diff / 60) % 60) . 'm ' : '';
                $seconds  = $diff % 60;
                $diffTime = $hours . $minutes . $seconds . 's';

                return $diffTime;
            })
            ->addColumn('note', function ($timeSheet) {
                if (mb_strlen($timeSheet->note) > 20) {
                    $note = mb_substr($timeSheet->note, 0, 20).'..';
                    return '<span data-toggle="tooltip" data-placement="right" data-original-title="'.$timeSheet->note.'">'.$note.'</span>';
                } else {
                    $note = $timeSheet->note;
                    return $note;
                }
            })
            ->rawColumns(['action','assigner','task_name','start_time','end_time','time_spent','note'])

            ->make(true);
    }


    public function query()
    {
        $taskTimer = new TaskTimer();
        $from      = isset($_GET['from']) ? $_GET['from'] : null;
        $to        = isset($_GET['to']) ? $_GET['to'] : null;
        $project   = isset($_GET['project']) ? $_GET['project'] : null;
        $assignee  = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $running   = isset($_GET['running']) ? $_GET['running'] : null;

        $timerDetails = $taskTimer->getTaskTime(['from' => $from, 'to' => $to, 'assinee_id' => $assignee, 'project_id' => $project, 'running' => $running])->select('task_timers.*', 'users.id as user_id', 'users.full_name', 'tasks.name as taskName', 'projects.name', 'tasks.related_to_type', 'projects.id as project_id');
        return $this->applyScopes($timerDetails);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'task_timers.id', 'title' => 'id', 'visible' => false ])

            ->addColumn(['data' => 'assigner', 'name' => 'users.full_name', 'title' => __('Assignee')])

            ->addColumn(['data' => 'task_name', 'name' => 'tasks.name', 'title' => __('Task name')])
            
            ->addColumn(['data' => 'start_time', 'name' => 'start_time', 'title' => __('Start time')])

            ->addColumn(['data' => 'end_time', 'name' => 'end_time', 'title' => __('End time')])

            ->addColumn(['data' => 'time_spent', 'name' => 'task_timers.start_time', 'title' => __('Time spent')])

            ->addColumn(['data' => 'note', 'name' => 'note', 'title' => __('Note')])

            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
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

