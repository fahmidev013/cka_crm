<?php

namespace App\Models;
use App\Http\Start\Helpers;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    public $timestamps = false;
    //Relation Starts
    public function projectStatus()
    {
        return $this->belongsTo('App\Models\ProjectStatus','status');
    }

    public function priority()
    {
        return $this->belongsTo('App\Models\Priority','priority_id');
    }

    public function taskStatus()
    {
        return $this->belongsTo('App\Models\TaskStatus','task_status_id');
    }

    public function taskAssigns()
    {
        return $this->hasMany('App\Models\TaskAssign', 'task_id');
    }
    //Relation Ends

    public function getTaskStat($from = null, $to = null)
    {
        $results = [];
        $taskStat = [];
        if (!empty($from) && !empty($to)) {
            $results = DB::select(DB::raw("SELECT COUNT(t.id) as taskCount, ts.name FROM task_statuses as ts LEFT JOIN `tasks` as t on(t.task_status_id = ts.id) WHERE t.created_at BETWEEN '" . $from . "' AND '" . $to . "' GROUP BY ts.name"));
        } else {
            $results = DB::select(DB::raw("SELECT COUNT(t.id) as taskCount, ts.name FROM task_statuses as ts LEFT JOIN `tasks` as t on(t.task_status_id = ts.id) WHERE 1 GROUP BY ts.name"));
        }
        foreach ($results as $key => $value) {
            $taskStat[$value->name] = $value->taskCount;
        }

        return $taskStat;
    }

    public function getLatestTask($from = null, $to = null)
    {
        $data = [];
        $query = [];
        if (!empty($from) && !empty($to)) {
            $data = DB::table('tasks')
                  ->leftjoin('task_statuses','task_statuses.id','=','tasks.task_status_id')
                  ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                  ->whereBetween('tasks.start_date', [$from, $to])
                  ->select('tasks.*','priorities.name as priority','priorities.id as priority_id','task_statuses.name as status_name','task_statuses.id as status_id')
                  ->orderBy('tasks.start_date', 'DESC')
                  ->take(5)
                  ->get();
        } else {
            $data = DB::table('tasks')
                      ->leftjoin('task_statuses','task_statuses.id','=','tasks.task_status_id')
                      ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                      ->select('tasks.*','priorities.name as priority','priorities.id as priority_id','task_statuses.name as status_name','task_statuses.id as status_id')
                      ->orderBy('tasks.start_date', 'DESC')
                      ->take(5)
                      ->get();
        }
        return $data;
    }

    public function getTaskDetailsById($id)
    {
        $result = DB::table('tasks')
                    ->leftjoin('task_statuses','task_statuses.id','=','tasks.task_status_id')
                    ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                    ->leftJoin('projects','projects.id','=','tasks.related_to_id')
                    ->select('tasks.*','priorities.name as priority','priorities.id as priority_id','task_statuses.name as status_name','task_statuses.id as status_id','projects.id as project_id','projects.name as project_name','projects.per_hour_project_scale', 'projects.charge_type')
                    ->where('tasks.id',$id)
                    ->first();
        return $result;
    }

    public function getAllComment($id)
    {
        $user   = Auth::user()->id;
        $result = DB::table('task_comments')
                    ->leftJoin('users','users.id','=','task_comments.user_id')
                    ->leftJoin('customers','customers.id','=','task_comments.customer_id')
                    ->select('task_comments.*','users.full_name', DB::raw('(CASE WHEN task_comments.user_id != ' . 0 . ' THEN users.full_name ELSE CONCAT(customers.first_name,SPACE(1),customers.last_name) END) AS user_name'), DB::raw('(CASE WHEN task_comments.user_id = ' . $user . ' THEN 1 WHEN task_comments.customer_id != 0 THEN 2 ELSE 0 END) AS is_user'))
                    ->where('task_id',$id)
                    ->orderBy('task_comments.created_at', 'desc')
                    ->get();
        return $result;
    }

    public function getAllCommentCustomerPanel($id)
    {
        $user = Auth::guard('customer')->user()->id;
        $result = DB::table('task_comments')
                    ->leftJoin('users','users.id','=','task_comments.user_id')
                    ->leftJoin('customers','customers.id','=','task_comments.customer_id')
                    ->select('task_comments.*','users.full_name','users.picture','customers.first_name','customers.last_name', DB::raw('(CASE WHEN task_comments.user_id != ' . 0 . ' THEN users.full_name ELSE CONCAT(customers.first_name,SPACE(1),customers.last_name) END) AS user_name'), DB::raw('(CASE WHEN task_comments.customer_id = ' . $user . ' THEN 1 ELSE 0 END) AS is_user'))
                    ->where('task_id',$id)
                    ->orderBy('task_comments.id', 'desc')
                    ->get();
        return $result;
    }

    public function getCommentById($id)
    {
        $user   = Auth::user()->id;
        $result = DB::table('task_comments')
                    ->leftJoin('users','users.id','=','task_comments.user_id')
                    ->select('task_comments.*','users.full_name as user_name', DB::raw('(CASE WHEN task_comments.user_id = ' . $user . ' THEN 1 ELSE 0 END) AS is_user'))
                    ->where('task_comments.id',$id)
                    ->first();
        return $result;
    }


    //All task
    public function getAllTaskForDT($from, $to, $status, $project, $assigne, $priority = null, $id = null, $type = null)
    {
        $result = DB::table('tasks')
                    ->leftjoin('task_statuses','task_statuses.id','=','tasks.task_status_id')
                    ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                    ->leftjoin('task_assigns','task_assigns.task_id','=','tasks.id')
                    ->leftjoin('projects','projects.id','=','tasks.related_to_id')
                    ->leftjoin('customers','customers.id','=','tasks.related_to_id')
                    ->leftjoin('tickets','tickets.id','=','tasks.related_to_id')
                    ->select('tasks.*','priorities.name as priority_name','priorities.id as priorityId','task_statuses.name as status_name','task_statuses.id as status_id','task_statuses.color','task_assigns.user_id as task_user','projects.name as project_name', 'customers.name as customer_name', 'tickets.subject', DB::raw("(SELECT task_id FROM task_timers WHERE task_timers.task_id = tasks.id AND task_timers.end_time IS NULL LIMIT 1) as not_end"))
                    ->groupBy('tasks.id');

        if (!empty($from) && !empty($to)) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $result ->where('tasks.start_date', '>=', $from)->where('tasks.start_date', '<=', $to);
        }
        if (!empty($status)) {
            $result->where('tasks.task_status_id','=',$status);
        } else {
            $result->where('tasks.task_status_id', '!=' , 4);
        }

        if (!empty($priority)) {
            $result->where('tasks.priority_id','=',$priority);
        }
        if (!empty($project)) {
            $result->where('tasks.related_to_id','=', $project)
                    ->where('tasks.related_to_type','=', 1);
        }
        if (!empty($id)) {
            $result->where('tasks.related_to_id','=', $id);
        }
        if (!empty($type)) {
            $result->where('tasks.related_to_type','=', $type);
        }
        if (!empty($assigne)) {
            $result->where('task_assigns.user_id', $assigne);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_task') && !Helpers::has_permission(Auth::user()->id, 'manage_task')) {
            $id = Auth::user()->id;
            $result->where('tasks.user_id', $id);
        }
        return $result;
    }

    public function getUserTaskForDT($from, $to, $status, $assigne, $priority)
    {
        $result = DB::table('tasks')
                    ->leftjoin('task_statuses','task_statuses.id','=','tasks.task_status_id')
                    ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                    ->leftjoin('task_assigns','task_assigns.task_id','=','tasks.id')
                    ->select('tasks.*','priorities.name as priorityName','priorities.id as priorty_id','task_statuses.name as status_name','task_statuses.id as status_id','task_statuses.color','task_assigns.user_id as task_user', DB::raw("(SELECT task_id FROM task_timers WHERE task_timers.task_id = tasks.id AND task_timers.end_time IS NULL LIMIT 1) as not_end"))
                    ->groupBy('tasks.id');

        if (!empty($from) && !empty($to)) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $result ->where('tasks.start_date', '>=', $from)->where('tasks.start_date', '<=', $to);
        }
        if (!empty($status)) {
            $result->where('tasks.task_status_id','=',$status);
        } else {
            $result->where('tasks.task_status_id', '!=' , 4);
        }

        if (!empty($priority)) {
            $result->where('tasks.priority_id','=',$priority);
        }

        if (!empty($assigne)) {
            $result->where('task_assigns.user_id','=',$assigne);
        }

        return $result;
    }

    public function customerPanleTaskList($project_id)
    {
        $match = [['tasks.related_to_id','=',$project_id],['tasks.related_to_type','=',1],['tasks.visible_to_customer','=',1]];
        $result = DB::table('tasks')
                    ->leftjoin('task_statuses','task_statuses.id','=','tasks.status')
                    ->leftjoin('priorities','priorities.id','=','tasks.priority_id')
                    ->select('tasks.*','priorities.name as priority','priorities.id as priority_id','task_statuses.name as status_name','task_statuses.id as status_id','task_statuses.color')
                    ->where($match);
        return $result;
    }

    public function taskAssignsList($task_id)
    {
        $result = DB::table('task_assigns')
                    ->leftJoin('users','users.id','=','task_assigns.user_id')
                    ->where('task_assigns.task_id',$task_id)
                    ->select('task_assigns.user_id as user_id','users.full_name as user_name')
                    ->get();
        return $result;
    }

    public function getTaskFiles($id)
    {
        $data = DB::table('files')
              ->leftJoin('users','users.id','=','files.user_id')
              ->where(['task_id'=>$id])
              ->select('files.*')
              ->get();
        return $data;
    }

    public function getCustomerTaskFiles($id)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $data = DB::table('files')
              ->leftJoin('users','users.id','=','files.user_id')
              ->leftJoin('customers','customers.id','=','files.customer_id')
              ->where(['task_id'=>$id])
              ->select('files.*', DB::raw('(CASE WHEN files.customer_id = 0 THEN users.full_name ELSE CONCAT(customers.first_name,SPACE(1),customers.last_name) END) AS user_name'), DB::raw('(CASE WHEN files.customer_id = ' . $customer_id . ' AND files.user_id = 0 THEN 1 ELSE 0 END) AS is_user'))
              ->get();

        return $data;
    }

    public function taskCustomerName($related_id)
    {
        $result = Customer::where('id', $related_id)
                    ->select('first_name','last_name')
                    ->first();
        return $result;
    }

    public function taskTicketSubject($related_id)
    {
        $result = Ticket::where('id',$related_id)
                    ->select('subject')
                    ->first();
        return $result;
    }

    public function getTotalLoggedTimeById($id)
    {
        $total_time = TaskTimer::where('task_id', $id)->get();
        $user_id           = Auth::user()->id;
        $total_logged_time = 0;
        $user_logged_time  = 0;
        foreach ($total_time as $key => $value) {
            if($value->end_time > $value->start_time){
                $total_logged_time += ($value->end_time > $value->start_time) ? ($value->end_time - $value->start_time) : 0;
                if ($value->user_id == $user_id) {
                    $user_logged_time += ($value->end_time > $value->start_time) ? ($value->end_time - $value->start_time) : 0;
                }
            }
        }
        //Total logged time
        $hours = floor($total_logged_time / 3600) > 0 ? floor($total_logged_time / 3600).'h ' : '';
        $minutes = floor(($total_logged_time / 60) % 60) > 0 ? floor(($total_logged_time / 60) % 60).'m ' : '' ;
        $seconds = $total_logged_time % 60;
        $data['total_logged_time'] = $hours.$minutes.$seconds.'s';
        $data['totalTime'] = $total_logged_time / 3600;

        //Individual logged time
        $hours = floor($user_logged_time / 3600) > 0 ? floor($user_logged_time / 3600).'h ' : '';
        $minutes = floor(($user_logged_time / 60) % 60) > 0 ? floor(($user_logged_time / 60) % 60).'m ' : '' ;
        $seconds = $user_logged_time % 60;
        $data['user_logged_time'] = $hours.$minutes.$seconds.'s';

        return $data;
    }

    public function getTaskLoggedTimeById($id)
    {
        $total_time = TaskTimer::where('task_id', $id)->get();
        $total_logged_time = 0;
        $user_logged_time  = 0;
        foreach ($total_time as $key => $value) {
            if($value->end_time > $value->start_time){
                $total_logged_time += ($value->end_time > $value->start_time) ? ($value->end_time - $value->start_time) : 0;
            }
        }
        //Total logged time
        $hours   = floor($total_logged_time / 3600) > 0 ? floor($total_logged_time / 3600).'h ' : '';
        $minutes = floor(($total_logged_time / 60) % 60) > 0 ? floor(($total_logged_time / 60) % 60).'m ' : '' ;
        $seconds = $total_logged_time % 60;

        return $hours.$minutes.$seconds.'s';
    }


    public function customerPanleTimeSheet($project_id)
    {
        $timerDetails = DB::table('task_timers')
                            ->leftJoin('users', 'users.id', '=', 'task_timers.user_id')
                            ->leftJoin('tasks', 'tasks.id', '=', 'task_timers.task_id')
                            ->select('task_timers.*', 'users.id as user_id', 'users.full_name', 'tasks.name')
                            ->where(['related_to_type' => 1, 'related_to_id' => $project_id]);
        return $timerDetails;
    }


    public function getTaskSummary($options = [])
    {
        $conditions = [];
        $conditions['related_to_type'] = !empty($options['related_to_type']) ?  $options['related_to_type'] : null ;
        $conditions['related_to_id'] = !empty($options['related_to_id']) ?  $options['related_to_id'] : null;

        $user_id = $options['user_id'];
        $allassignee = $options['allassignee'];

        // Cunting summary
        if (!empty($options['related_to_type']) && !empty($options['related_to_id'])) {
            $result = DB::table('tasks')
                ->leftJoin('task_statuses', 'task_statuses.id', '=', 'tasks.task_status_id')
                ->where($conditions)
                ->select('task_statuses.name', 'task_statuses.id', 'task_statuses.color', DB::raw("COUNT(tasks.task_status_id) as total_status"));
        } else {
            $result = DB::table('tasks')
                ->leftJoin('task_statuses', 'task_statuses.id', '=', 'tasks.task_status_id')
                ->select('task_statuses.name', 'task_statuses.id', 'task_statuses.color', DB::raw("COUNT(tasks.task_status_id) as total_status"));
        }

        if (!empty($options['from'])) {
            $result->whereDate('created_at', '>=', DbDateFormat($options['from']));
        }
        if (!empty($options['to'])) {
            $result->whereDate('created_at', '<=', DbDateFormat($options['to']));
        }
        if (!empty($allassignee)) {
            $result->join('task_assigns',function($join) use ($allassignee){
                $join->on('task_assigns.task_id', 'tasks.id');
                $join->where('task_assigns.user_id', $allassignee);
            });
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_task') && !Helpers::has_permission(Auth::user()->id, 'manage_task')) {
            $id = Auth::user()->id;
            $result->where('tasks.user_id', $id);
        }
        $result = $result->groupBy('tasks.task_status_id');
        return $result;
    }

    public function getUserTaskSummary($options = [])
    {

        $user_id = $options['user_id'];

        // Cunting summary
        $result = DB::table('tasks')
                ->leftJoin('task_statuses', 'task_statuses.id', '=', 'tasks.task_status_id')
                ->leftJoin('task_assigns', 'task_assigns.task_id', '=', 'tasks.id')
                ->select('task_statuses.name', 'task_statuses.id', 'task_statuses.color', DB::raw("COUNT(tasks.task_status_id) as total_status"));


        if (!empty($options['from'])) {
            $result->whereDate('created_at', '>=', DbDateFormat($options['from']));
        }
        if (!empty($options['to'])) {
            $result->whereDate('created_at', '<=', DbDateFormat($options['to']));
        }
        if (!empty($options['user_id'])) {
            $result->where('task_assigns.user_id', $options['user_id']);
        }

        $result = $result->groupBy('tasks.task_status_id');
        return $result;
    }


    public function projectCost($chargeType, $ratePerHour, $projectId)
    {
        if (!empty($chargeType) && $chargeType != 1) {
            $allTasksId = (new Task())->getAllTaskForDT(null, null, null, $projectId, null, null, null)->pluck('hourly_rate', 'id')->toArray();
            $totalLoggedTime = 0;
            $cost = 0;
            if ($chargeType == 2) {
                foreach ($allTasksId as $key => $value) {
                    $loggedTime = (new Task())->getTotalLoggedTimeById($key);
                    $totalLoggedTime += $loggedTime['totalTime'];
                }
                $cost = $totalLoggedTime * $ratePerHour;
            } else {
                foreach ($allTasksId as $key => $value) {
                    $loggedTime = (new Task())->getTotalLoggedTimeById($key);
                    if (!empty($value)) {
                        $cost += $loggedTime['totalTime'] * $value;
                    }
                }
            }
            return $cost;
        }
    }

}
