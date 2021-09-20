<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    Project,
    Task
};
use DB;
use Auth;
use Helpers;
use Session;
use Carbon;

class ProjectDataTable extends DataTable{
    public function ajax()
    {
        $projects   = $this->query();
        return datatables()
            ->of($projects)
            ->addColumn('action', function ($projects) {
                $edit = (Helpers::has_permission(Auth::user()->id, 'edit_project')) ? '<a href="' . url("project/edit/". $projects->project_id) . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                $delete = (Helpers::has_permission(Auth::user()->id, 'delete_project')) ? '
                <form method="post" action="' . url("project/delete") . '" class="display_inline" id="delete-item-' . $projects->project_id . '">
                ' . csrf_field() . '
                <input type="hidden" name="project_id" value="'. $projects->project_id .'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $projects->project_id .'" data-target="#theModal" data-label="Delete" data-title="' . __('Delete project') . '" data-message="' . __('Are you sure to delete this project?') . '">
                                <i class="feather icon-trash-2"></i>
                            </button>
                </form>
                ' : '';
                return $edit . $delete;
            })
            ->addColumn('project_name', function ($projects) {
                $projectName = $projects->name;
                if (mb_strlen($projectName) > 30) {
                   $projectName = '<span data-toggle="tooltip" data-placement="right"  data-original-title="'. $projectName .'">'. mb_substr($projectName, 0, 30) .'..</span>';
                }

                $projectType = $projects->project_type != 'customer' ? str_replace('_', ' ', ucwords($projects->project_type, '_')) : '';
                $fullProjectColumn = "<a href='" . url( "project/details/" . $projects->project_id) . "'>". $projectName ."</a><br/><span class='f-12 customer-task'>" . $projectType . "</span>";
                return $fullProjectColumn;
            })
            ->addColumn('customer_name', function ($projects) {
                $customerName = $projects->first_name . ' ' . $projects->last_name;
                if (mb_strlen($customerName) > 20) {
                    $customerName = '<span data-toggle="tooltip" data-placement="right"  data-original-title="'. $customerName .'">'. mb_substr($customerName, 0, 20) .'..</span>';
                }
                return "<a href='" . url("customer/edit/" . $projects->customer_id) . "'>" . $customerName . "</a>";
            })
            ->addColumn('totalTask', function ($projects) {
                $projectRelatedTask = Task::where(['related_to_id' => $projects->project_id, 'related_to_type' => 1]);
                $totalTask = $projectRelatedTask->count();
                $completedTask = $projectRelatedTask->where('task_status_id', 4)->count();

                return !empty($completedTask) ? "<a href='" . url( "task/list") . '?from=&to=&project=' . $projects->project_id . '&assignee=&status=&priority=&btn='  . "' >" . $totalTask . ' ' . '/' . ' ' . "<span class='text-success font-weight-bold'>" . $completedTask  . "</span></a>" : $totalTask;
            })
            ->addColumn('project_due_date', function ($projects) {
                return $projects->due_date ?  formatDate($projects->due_date)  :  '-' ;
            })
            ->addColumn('project_begin_date', function ($projects) {
                return formatDate($projects->begin_date);
            })
            ->addColumn('id', function ($projects) {
                return "<a href='" . url( "project/details/" . $projects->project_id) . "'>" . $projects->project_id . "</a>";
            })
            ->rawColumns(['action', 'project_name', 'customer_name', 'totalTask', 'project_due_date', 'project_begin_date', 'id'])
            ->make(true);
    }

    public function query()
    {
        $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $projects = (new Project())->getAllProjectDT($from, $to, $status, $project_type);
        return $this->applyScopes($projects);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'projects.id', 'title' => '#', 'orderable' => true ])
            ->addColumn(['data' => 'project_name', 'name' => 'projects.name', 'title' => __('Project name'), 'orderable' => true ])
            ->addColumn(['data' => 'project_name', 'name' => 'projects.project_type', 'visible' => false, 'orderable' => true ])
            ->addColumn(['data' => 'customer_name', 'name' => 'customers.first_name', 'title' => __('Customer'), 'orderable' => true ])
            ->addColumn(['data' => 'project_begin_date', 'name' => 'begin_date', 'title' => __('Start date'), 'orderable' => true ])
            ->addColumn(['data' => 'project_due_date', 'name' => 'due_date', 'title' => __('Deadline'), 'orderable' => true ])
            ->addColumn(['data' => 'totalTask', 'name' => 'totalTask', 'title' => __('Total Task'), 'orderable' => true ])
            ->addColumn(['data' => 'status_name', 'name' => 'project_statuses.name', 'title' => __('Status'), 'orderable' => true ])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [[ 0, 'desc' ]]
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
