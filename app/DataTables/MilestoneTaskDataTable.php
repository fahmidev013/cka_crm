<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;
use Carbon;

class MilestoneTaskDataTable extends DataTable{
    public function ajax()
    {
        $tasks = $this->query();

        return datatables()
            ->of($tasks)
        
            ->addColumn('name', function ($tasks) {
               if($tasks->name){
                $id = '<a href="" class="task_class taskName"  data-id="'.$tasks->id.'" data-priority-id= "'.$tasks->project_priority.'"  project_id = "'.$tasks->related_to_id.'" data-status-id= "'.$tasks->status_id.'"  type="button" data-toggle="modal" data-target="#modal-default">'.$tasks->name.'</a><br/><a href="'.url("project/details/$tasks->related_to_id").$tasks->name.'</a>'; 

                } else {
                 $id = '<a href="" class="task_class"  data-id="'.$tasks->id.'" data-priority-id= "'.$tasks->project_priority.'"  project_id = "'.$tasks->related_to_id.'" data-status-id= "'.$tasks->status_id.'"  type="button" data-toggle="modal" data-target="#modal-default">'.$tasks->name.'</a>'; 
                }

                if(isset($tasks->not_end) && ($tasks->not_end != null) ){
                   $timer = '<i class="fa fa-clock-o color_red"></i>&nbsp&nbsp';
                }else{
                   $timer = ''; 
                }

                return $timer.$id;
            })

         

             ->addColumn('priority_name', function ($tasks) {
                $allpriorities='';
                $priorities = DB::table('priorities')->where('id','!=',$tasks->project_priority)->get();
                foreach ($priorities as $key => $value) {
                      $allpriorities .= '<li class="priorityName"><a class="priority_change f-14 color_black"  project_id="'.$tasks->related_to_id.'" data-id="'. $value->id .'" data-value="'. $value->name .'"  task_id="'.$tasks->id.'">'.$value->name.'</a></li>';
                }

                $top='<div class="btn-group">
                <button type="button" style="color:'.(($tasks->priority_name == 'High')?'#099909':'#367fa9').';"  class="badge f-12 dropdown-toggle topPriority" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                '.$tasks->priority_name.'&nbsp;<span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu scrollable-menu status_change dropDown" role="menu">';
                $view='<li>'.$allpriorities.'</li>';
                $last='</ul></div>&nbsp';
                return $top.$view.$last;
            })

             ->addColumn('status_name', function ($tasks) {
                 $allstatus='';
                $priorities = DB::table('task_statuses')->where('id', '!=', $tasks->status_id)->get();
                foreach ($priorities as $key => $value) {
                   $allstatus .= '<li class="statusName"><a class="status_change f-14 color_black" project_id="'.$tasks->related_to_id.'" data-id="'. $value->id .'" data-value="'. $value->name .'" task_id="'.$tasks->id.'" >'.$value->name.'</a></li>';
                }
                if ($tasks->status_name == "Complete") {
                    $allstatus .= '<li class="completeStatus"><a class="status_change f-14  color_black" project_id="'.$tasks->related_to_id.'" data-id="-1" data-value="Re-open" task_id="'.$tasks->id.'">'. __('Re-open') .'</a></li>';
                }
                $top='<div class="btn-group px-0">
                <button style="color:'.$tasks->color.' !important" type="button" class="badge text-white text-center f-12 dropdown-toggle topStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                '.$tasks->status_name.'&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu scrollable-menu status_change" role="menu">';
                $last='</ul></div>&nbsp';

                
                return $top.$allstatus.$last;
            })

            

            ->rawColumns(['name','priority_name','status_name'])

            ->make(true);
    }

    public function query()
    {
        $milestone_id = $this->milestone_id;

        $result = DB::table('tasks')
                 ->leftjoin('task_statuses','task_statuses.id','=','tasks.task_status_id')
                 ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                 ->leftjoin('task_assigns','task_assigns.task_id','=','tasks.id')
                 ->leftjoin('projects','projects.id','=','tasks.related_to_id')
                 ->select('tasks.*','priorities.name as priority_name','priorities.id as project_priority','task_statuses.name as status_name','task_statuses.id as status_id','task_statuses.color','task_assigns.user_id as task_user','projects.name as project_name', DB::raw("(SELECT task_id FROM task_timers WHERE task_timers.task_id = tasks.id AND task_timers.end_time = '' LIMIT 1) as not_end"))
                 ->where('tasks.milestone_id', $milestone_id) 
                 ->groupBy('tasks.id');                
       
        return $this->applyScopes($result);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Name'), 'orderable' => false ])
          
            ->addColumn(['data' => 'priority_name', 'name' => 'priorities.name', 'title' => __('Priority'), 'orderable' => false ])

            ->addColumn(['data' => 'status_name', 'name' => 'project_statuses.name', 'title' => __('Status'), 'orderable' => false])


            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [0, 'desc'],
                'searching'=> false, 
                'paging'=> false,
                'info'=> false,
                'orderable' => false
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

