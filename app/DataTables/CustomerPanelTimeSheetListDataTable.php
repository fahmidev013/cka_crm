<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;
use Carbon;

class CustomerPanelTimeSheetListDataTable extends DataTable{
    public function ajax()
    {
        $tasks = $this->query();

        return datatables()
            ->of($tasks)

            ->addColumn('start_time', function ($tasks) {
                $start_time = timeZoneformatDate(date("m/d/Y h:i:s A T", $tasks->start_time)).' '.timeZonegetTime(date("m/d/Y h:i:s A T", $tasks->start_time));
                return $start_time;
            })

            ->addColumn('end_time', function ($tasks) {
                $end_time = timeZoneformatDate(date("m/d/Y h:i:s A T", $tasks->end_time)).' '.timeZonegetTime(date("m/d/Y h:i:s A T", $tasks->end_time));
                return $end_time;
            })

            ->addColumn('spent_time', function ($tasks) {
                $diff     = ($tasks->end_time > $tasks->start_time) ? ($tasks->end_time - $tasks->start_time) : null;

                $hours    = floor($diff / 3600) > 0 ? floor($diff / 3600) . 'h ' : '';
                $minutes  = floor(($diff / 60) % 60) > 0 ? floor(($diff / 60) % 60) . 'm ' : '';
                $seconds  = $diff % 60;
                $diffTime = $hours . $minutes . $seconds . 's';
                
                return $diffTime;
            })


            ->rawColumns(['start_time','end_time','spent_time'])


            ->make(true);
    }

    public function query()
    {
        $project_id = $this->project_id;
        $timesheet=(new Task())->customerPanleTimeSheet($project_id);
        return $this->applyScopes($timesheet);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'full_name', 'name' => 'users.full_name', 'title' => __('Assignee') ])

            ->addColumn(['data' => 'name', 'name' => 'tasks.name', 'title' => __('Task Name') ])

            ->addColumn(['data' => 'start_time', 'name' => 'task_timer.start_time', 'title' => __('Start Time')])

            ->addColumn(['data' => 'end_time', 'name' => 'task_timer.end_time', 'title' => __('End Time') ])

            ->addColumn(['data' => 'spent_time', 'name' => 'spent_time', 'title' => __('Time Spent') ])

            ->addColumn(['data' => 'note', 'name' => 'task_timer.note', 'title' => __('Note')])

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

