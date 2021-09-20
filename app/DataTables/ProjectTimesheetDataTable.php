<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;
use Cache;


class ProjectTimesheetDataTable extends DataTable{
    public function ajax()
    {
        $timeSheet = $this->query();

        return datatables()
            ->of($timeSheet)
            ->addColumn('action', function ($timeSheet) {
                $delete='';

                    $delete = '<form method="post" action="' . url("project/task/timer/delete/$timeSheet->id") . '" class="display_inline" id="delete-item-'. $timeSheet->id .'">
                        ' . csrf_field() . '
                        <input name="project_id" value="'.$this->project_id.'" type="hidden">
                        <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $timeSheet->id .'" data-target="#theModal" data-label = "Delete" data-title="' . __('Delete timesheet') . '" data-message="' . __('Are you sure to delete this timesheet?') . '">
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
                return $timeSheet->name;
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
                $diff     = ($timeSheet->end_time > $timeSheet->start_time) ? ($timeSheet->end_time - $timeSheet->start_time) : null;
                $hours    = floor($diff / 3600) > 0 ? floor($diff / 3600) . 'h ' : '';
                $minutes  = floor(($diff / 60) % 60) > 0 ? floor(($diff / 60) % 60) . 'm ' : '';
                $seconds  = $diff % 60;
                $diffTime = $hours . $minutes . $seconds . 's';
                $spent_time = ($timeSheet->end_time ? $diffTime : '');

                return $spent_time;

                if ($timeSheet->end_time != "") {
                    $time_spent = $timeSheet->end_time - $timeSheet->start_time;
                    return $time_spent;
                }
                return "";
            })
            ->addColumn('note', function ($timeSheet) {
                return $timeSheet->note;
            })
            ->rawColumns(['action','assigner','task_name','start_time','end_time','time_spent','note'])

            ->make(true);
    }


    public function query()
    {
        $timerDetails = DB::table('task_timers')
            ->leftJoin('users', 'users.id', '=', 'task_timers.user_id')
            ->leftJoin('tasks', 'tasks.id', '=', 'task_timers.task_id')
            ->select('task_timers.*', 'users.id as user_id', 'users.full_name', 'tasks.name')
            ->where(['related_to_type' => 1, 'related_to_id' => $this->project_id]);

        return $this->applyScopes($timerDetails);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'assigner', 'name' => 'users.full_name', 'title' => __('Assignee')])

            ->addColumn(['data' => 'task_name', 'name' => 'tasks.name', 'title' => __('Task name')])
            
            ->addColumn(['data' => 'start_time', 'name' => 'task_timers.start_time', 'title' => __('Start time')])

            ->addColumn(['data' => 'end_time', 'name' => 'task_timers.end_time', 'title' => __('End time')])

            ->addColumn(['data' => 'time_spent', 'name' => 'task_timers.start_time', 'title' => __('Time spent')])

            ->addColumn(['data' => 'note', 'name' => 'task_timers.note', 'title' => __('Note')])

            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [2, 'desc']
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

