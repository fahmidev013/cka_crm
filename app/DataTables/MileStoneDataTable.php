<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;
use Carbon;


class MileStoneDataTable extends DataTable{
    public function ajax()
    {
        $milestones = $this->query();

        return datatables()
            ->of($milestones)
            
            ->addColumn('action', function ($milestones) {
               $edit=$delete='';
 

                    $edit = (Helpers::has_permission(Auth::user()->id, 'edit_milestone')) ? '<a href="' . url("project/milestones/edit/$milestones->id") . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';

                    $delete = (Helpers::has_permission(Auth::user()->id, 'delete_milestone')) ? '
                        <form method="post" action="'.url("project/milestone/delete").'" class="display_inline"  id="delete-item-'. $milestones->id .'">
                        ' . csrf_field() . '
                            <input type="hidden" name="milestone_id" value="'.$milestones->id.'">
                            <input type="hidden" name="milestone_name" value="'.$milestones->name.'">
                            <input type="hidden" name="project_id" value="'.$milestones->project_id.'">
                            <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $milestones->id .'" data-target="#theModal" data-label="Delete" data-title="' . __('Delete Milestone') . '" data-message="' . __('Are you sure to delete this Milestone?') . '">
                                    <i class="feather icon-trash-2"></i> </button>
                        </form>
                        ' : '';
                return $edit.$delete;
            })
           
            ->addColumn('milestone_name', function ($milestones) {
                $milestone_name = "<a href='" . url("projects/milestones/view/$milestones->project_id/$milestones->id") . "'>$milestones->name</a>";
                return $milestone_name;
            })

            ->addColumn('due_date', function ($milestones) {
                $due_date = $milestones->due_date ? formatDate($milestones->due_date) : '-';
                return $due_date;
            })

            ->addColumn('created_at', function ($milestones) {
                $created_at =  formatDate($milestones->created_at) ;
                return $created_at;
            })

             ->rawColumns(['action','milestone_name','due_date','created_at'])

            ->make(true);
    }

    public function query()
    {
        $project_id = $this->project_id;
        $milestones = DB::table('milestones')->where('milestones.project_id', $project_id);

        return $this->applyScopes($milestones);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'milestone_name', 'name' => 'name', 'title' => __('Name') ])

            ->addColumn(['data' => 'due_date', 'name' => 'due_date', 'title' => __('Due Date') ])

            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => __('Created At') ])
        
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

