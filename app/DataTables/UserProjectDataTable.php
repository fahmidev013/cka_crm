<?php
namespace App\DataTables;
use App\Models\ProjectMember;
use App\Models\Project;
use App\Models\Task;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class UserProjectDataTable extends DataTable
{
    public function ajax()
    {
        $projects   = $this->query();
        return datatables()
            ->of($projects)
            ->addColumn('action', function ($projects) {
                $edit = $delete = '';
                $edit = (Helpers::has_permission(Auth::user()->id, 'edit_project')) ? '<a href="' . url("project/edit/" . $projects->project_id) . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                $delete = (Helpers::has_permission(Auth::user()->id, 'delete_ticket')) ? '
                <form method="post" action="' . url("project/delete") . '"  id="delete-projects-'. $projects->project_id .'" class="display_inline">
                ' . csrf_field() . '
                <input type="hidden" name="project_id" value="'. $projects->project_id .'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='. $projects->project_id .' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete project') . '" data-message="' . __('Are you sure to delete this project?') . '">
                                <i class="feather icon-trash-2"></i> 
                            </button>
                </form>
                ' : '';
                return $edit.$delete;
            })
            ->addColumn('project_name', function ($projects) {
                $project_name = "<a href='" . url("project/details/". $projects->project_id) . "'>". $projects->name ."</a><br/><span class='customer-task'>". $projects->project_type ."</span>";
                return $project_name;
            })            
            ->addColumn('customer_name', function ($projects) {
                $customer = $projects->first_name ? "<a href='". url("customer/edit/". $projects->customer_id) ."'>". $projects->first_name . ' ' . $projects->last_name . "</a>" : '-';
                return $customer;
            })           
            ->addColumn('totalTask', function ($projects) {
                $projectRelatedTask = Task::where(['related_to_id' => $projects->project_id, 'related_to_type' => 1]);
                $totalTask = $projectRelatedTask->count();
                $completedTask = $projectRelatedTask->where('task_status_id', 4)->count();

                return !empty($completedTask) ? "<a href='" . url( "task/list") .'?from=&to=&project='. $projects->project_id .'&assignee=&status=&priority=&btn='  . "' >".$totalTask .' '. '/'. ' ' . "<span class='text-success font-weight-bold'>" . $completedTask  . "</span></a>" : $totalTask;
                
            })            
            ->addColumn('project_due_date', function ($projects) {
                $last_reply = $projects->due_date ?  formatDate($projects->due_date)  :  '-' ;
                return $last_reply;
            })
             ->addColumn('project_begin_date', function ($projects) {
                $date = formatDate($projects->begin_date);
                return $date;
            })
            ->addColumn('id', function ($projects) {
                return "<a href='" . url( "project/details/" . $projects->project_id) . "'>" . $projects->project_id . "</a>";
            })

            ->rawColumns(['action', 'customer_name', 'totalTask', 'project_name', 'project_due_date', 'project_begin_date', 'id'])

            ->make(true);
    }

    public function query()
    {
        $id = $this->user_id;

        $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : null; 
        $status = isset($_GET['status']) ? $_GET['status'] : null; 
        $from = isset($_GET['from']) ? $_GET['from'] : null; 
        $to = isset($_GET['to']) ? $_GET['to'] : null; 
        $userProject = (new Project())->getAllProjectByUser($from, $to, $id, $status, $project_type)->get(); 
        return $this->applyScopes($userProject);
    }
    
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'id', 'title' => '#'])
        ->addColumn(['data' => 'project_name', 'name' => 'project_name', 'title' => __('Project name') ])
        ->addColumn(['data' => 'customer_name', 'name' => 'customer_name', 'title' => __('Customer') ])
        ->addColumn(['data' => 'project_begin_date', 'name' => 'project_begin_date', 'title' => __('Start date') ])
        ->addColumn(['data' => 'project_due_date', 'name' => 'project_due_date', 'title' => __('Deadline') ])
        ->addColumn(['data' => 'totalTask', 'name' => 'charge_type', 'title' => __('Total Task') ])
        ->addColumn(['data' => 'status_name', 'name' => 'status_name', 'title' => __('Status') ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'. config('app.locale') .'.json'),
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
