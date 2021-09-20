<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;
use Carbon;

class CustomerPanelTaskListDataTable extends DataTable{
    public function ajax()
    {
        $tasks = $this->query();

        return datatables()
            ->of($tasks)

            ->addColumn('name', function ($tasks) {
                if(Helpers::project_permission($tasks->retated_to_id, 'customer_view_task')){
                   return '<a href="'.url('customer-project/task-details/'.$tasks->retated_to_id.'/'.$tasks->id).'"  >'.$tasks->name.'</a>'; 
               }else{
                 return '<a >'.$tasks->name.'</a>';
               }
                

            })

            ->addColumn('start_date', function ($tasks) {
                $start_date = formatDate($tasks->start_date);
                return $start_date;
            })

             ->addColumn('due_date', function ($tasks) {
                $due_date = $tasks->due_date ? formatDate($tasks->due_date) : '-';
                return $due_date;
            })

            ->rawColumns(['name', 'start_date', 'due_date'])


            ->make(true);
    }

    public function query()
    {
        $project_id = $this->project_id;
        $tasks=(new Task())->customerPanleTaskList($project_id);
        return $this->applyScopes($tasks);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Name') ])

            ->addColumn(['data' => 'start_date', 'name' => 'start_date', 'title' => __('Start Date')])

            ->addColumn(['data' => 'due_date', 'name' => 'due_date', 'title' => __('Due Date') ])

            ->addColumn(['data' => 'priority', 'name' => 'priorities.name', 'title' => __('Priority') ])

            ->addColumn(['data' => 'status_name', 'name' => 'project_status.name', 'title' => __('Status')])

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

