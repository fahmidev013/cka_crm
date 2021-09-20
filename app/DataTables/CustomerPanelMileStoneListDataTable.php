<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\Milestone;
use App\Models\Task;
use Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;



class CustomerPanelMileStoneListDataTable extends DataTable{
    public function ajax()
    {
        $milestones = $this->query();

        return datatables()
            ->of($milestones)

           ->addColumn('milestone_name', function ($milestones) {
                $milestone_name = '<a href="'.url('customer-project/milestone-details/'.$milestones->project_id.'/'.$milestones->id).'"  >'.$milestones->milestone_name.'</a>'; 
                return $milestone_name;
            })

            ->addColumn('due_date', function ($milestones) {
                $due_date = formatDate( $milestones->due_date);
                return $due_date;
            })
             ->addColumn('created_at', function ($milestones) {
                $created_at = formatDate( $milestones->created_at);
                return $created_at;
            })

            ->rawColumns(['milestone_name','due_date','created_at'])

            ->make(true);
    }

    public function query()
    {
        $project_id = $this->project_id;
        $milestones = Milestone::where(['project_id'=> $project_id, 'visible_to_customer'=> 1]);  
        return $this->applyScopes($milestones);
    }

    public function html()
    {
        return $this->builder()

            ->addColumn(['data' => 'milestone_name', 'name' => 'milestone_name', 'title' => __('Name') ])

            ->addColumn(['data' => 'due_date', 'name' => 'due_date', 'title' => __('Due Date')])

            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => __('Created At') ])

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

