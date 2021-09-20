<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Project;
class CustomerProjectListDataTable extends DataTable
{
    public function ajax()
    {
        $projects   = $this->query();
        return datatables()
            ->of($projects)
            ->addColumn('action', function ($projects) {
                $edit=$delete='';
                    $edit = (Helpers::has_permission(Auth::user()->id, 'edit_project')) ? '<a href="' . url("project/edit/$projects->id") . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';

                    $delete = (Helpers::has_permission(Auth::user()->id, 'delete_ticket')) ? '
                <form method="post" action="' . url("project/delete") . '" id="delete-project-'.$projects->id.'" class="display_inline_block">
                ' . csrf_field() . '
                <input type="hidden" name="project_id" value="'.$projects->id.'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id='.$projects->id.' data-label="Delete" data-target="#confirmDelete" data-title="' . __('Delete project') . '" data-message="' . __('Are you sure to delete this project?') . '">
                                <i class="feather icon-trash-2"></i> 
                            </button>
                </form>
                ' : '';
                return $edit.$delete;
            })

            ->addColumn('project_name', function ($projects) {
                $project_name = "<a href='" . url("project/details/$projects->id") . "'>$projects->name</a>";
                return $project_name;
            })
           
           ->addColumn('charge_type', function ($projects) {
                if ($projects->charge_type == '1') {
                    $type = __('Fixed Rate');
                }elseif ($projects->charge_type == '2') {
                    $type = __('Project Hours');
                }elseif ($projects->charge_type == '3') {
                    $type = __('Task Hours');
                }
                return $type;
            })
    
             
            ->addColumn('project_due_date', function ($projects) {
                $last_reply = isset($projects->due_date) ?  formatDate($projects->due_date)  :  '-' ;
                return $last_reply;
            })

             ->addColumn('project_begin_date', function ($projects) {
                $date = isset($projects->begin_date) ? formatDate($projects->begin_date) : '-' ;
                return $date;
            })

           ->addColumn('status_name', function ($customerProject) {
                $myResult = !empty($customerProject->projectStatuses) ? $customerProject->projectStatuses->name : '' ;
                return $myResult;
            })

            ->addColumn('id', function ($projects) {
                return "<a href='" . url( "project/details/" . $projects->id) . "'>" . $projects->id . "</a>";
            })

            ->rawColumns(['action','project_name', 'charge_type', 'project_due_date', 'project_begin_date', 'status_name', 'id'])
            ->make(true);
    }

    public function query()
    {
        $id = $this->customer_id;
        $from    = isset($_GET['from']) ? ($_GET['from']) : null ;
        $to      = isset($_GET['to']) ? ($_GET['to']) : null ;
        $status =  !empty($_GET['status']) ? $_GET['status'] : null; 
        $match = !empty($status) ? [['projects.project_status_id','=', $status]] : [['projects.project_status_id','!=', 6]];
        if (!empty($from) && !empty($to)) {
            $match = [['begin_date','>=', DbDateFormat($from)],['begin_date','<=', DbDateFormat($to)],['projects.customer_id','=',$id]];
        }
        $result = Project::with(['customer', 'projectStatuses'])
                ->where($match)->where('customer_id', $id);
        $customerProject = $result->get();
        return $this->applyScopes($customerProject);
    }
    
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'id', 'title' => '#'])

        ->addColumn(['data' => 'project_name', 'name' => 'name', 'title' => __('Project Name') ])


            ->addColumn(['data' => 'project_begin_date', 'name' => 'begin_date', 'title' => __('Start Date') ])

            ->addColumn(['data' => 'project_due_date', 'name' => 'due_date', 'title' => __('Deadline') ])


            ->addColumn(['data' => 'charge_type', 'name' => 'charge_type', 'title' => __('Billing Type') ])

            ->addColumn(['data' => 'status_name', 'name' => 'project_statuses.name', 'title' => __('Status') ])

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

    protected function filename()
    {
        return 'customers_' . time();
    }
}
