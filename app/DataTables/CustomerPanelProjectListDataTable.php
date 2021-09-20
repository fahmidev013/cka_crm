<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Project;

class CustomerPanelProjectListDataTable extends DataTable
{
    public function ajax()
    {
        $customerProject   = $this->query();
        return datatables()
            ->of($customerProject)

            ->addColumn('project_name', function ($customerProject) {
                $project_name = "<a href='" . url("customer-project/detail/$customerProject->id") . "'>$customerProject->name</a>";
                return $project_name;
            })          
           ->addColumn('charge_type', function ($customerProject) {
                if ($customerProject->charge_type == '1') {
                    $type = __('Fixed Rate');
                }elseif ($customerProject->charge_type == '2') {
                    $type = __('Project Hours');
                }elseif ($customerProject->charge_type == '3') {
                    $type = __('Task Hours');
                }
                return $type;
            })             
            ->addColumn('project_due_date', function ($customerProject) {
                $last_reply = $customerProject->due_date ?  formatDate($customerProject->due_date)  :  '-' ;
                return $last_reply;
            })
            ->addColumn('project_begin_date', function ($customerProject) {
                $date = formatDate($customerProject->begin_date);
                return $date;
            })
            ->addColumn('status_name', function ($customerProject) {
                if (!empty($customerProject->projectStatuses)) {
                    if ($customerProject->projectStatuses->id == 1) {
                        return '<span class="badge badge-pill badge-primary f-12">'. $customerProject->projectStatuses->name .'</span>';
                    } else if ($customerProject->projectStatuses->id == 2) {
                        return '<span class="badge badge-pill badge-secondary f-12">'. $customerProject->projectStatuses->name .'</span>';
                    } else if ($customerProject->projectStatuses->id == 3) {
                        return '<span class="badge badge-pill badge-info f-12">'. $customerProject->projectStatuses->name .'</span>';
                    } else if ($customerProject->projectStatuses->id == 4) {
                        return '<span class="badge badge-pill badge-warning f-12">'. $customerProject->projectStatuses->name .'</span>';
                    } else {
                        return '<span class="badge badge-pill badge-success f-12">'. $customerProject->projectStatuses->name .'</span>';
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('id', function ($customerProject) {
                return "<a href='" . url( "customer-project/detail/" . $customerProject->id) . "'>" . $customerProject->id . "</a>";
            })
            ->rawColumns(['project_name','charge_type','project_due_date','project_begin_date', 'status_name', 'id'])

            ->make(true);
    }

    public function query()
    {
        $id = $this->customer_id;
        $from    = isset($_GET['from']) ? ($_GET['from']) : null ;
        $to      = isset($_GET['to']) ? ($_GET['to']) : null ;
        $status =  isset($_GET['status']) ? $_GET['status'] : null; 
        $query = Project::with(['customer', 'projectStatuses'])
                ->where('customer_id', '=', $id);
        if (!empty($status)) {
            $query->where('project_status_id', $status);
        } else {
            $query->where('project_status_id', '!=', 6);
        }
        if (!empty($from)) {
            $query->where('begin_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
            $query->where('begin_date', '<=', DbDateFormat($to));
        }
        
        $customerProject = $query;
        return $this->applyScopes($customerProject);
    }
    
    public function html()
    {
        return $this->builder()

            ->addColumn(['data' => 'id', 'name' => 'projects.id', 'title' => '#'])

            ->addColumn(['data' => 'project_name', 'name' => 'projects.name', 'title' => __('Project Name') ])
        
            ->addColumn(['data' => 'project_begin_date', 'name' => 'projects.begin_date', 'title' => __('Start Date') ])

            ->addColumn(['data' => 'project_due_date', 'name' => 'projects.due_date', 'title' => __('Deadline') ])


            ->addColumn(['data' => 'charge_type', 'name' => 'projects.charge_type', 'title' => __('Billing Type') ])

            ->addColumn(['data' => 'status_name', 'name' => 'project_status_id', 'title' => __('Status') ])

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
