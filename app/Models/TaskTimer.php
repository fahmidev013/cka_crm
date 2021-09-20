<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TaskTimer extends Model
{
	public $timestamps = false;
    
    public function getTaskTime($options = []) 
    {
    	if (isset($options['from']) && $options['from'] != null) {
            $from  =  strtotime( DbDateFormat($options['from']) );
        }
        if (isset($options['to']) && $options['to'] != null) {
            $to =  strtotime( '+23 hour +59 minutes', strtotime(DbDateFormat($options['to'])));
        }
        if (isset($options['assinee_id']) && $options['assinee_id'] != null) {
            $conditions['users.id'] = $options['assinee_id'];
        }
        if (isset($options['project_id']) && $options['project_id'] != null) {
            $conditions['projects.id'] = $options['project_id'];
        }
        if (isset($options['running']) && $options['running'] != null && $options['running'] == 'yes') {
            $conditions['task_timers.end_time'] = null;
        }

        $query = DB::table('task_timers')
            ->leftJoin('users', 'users.id', '=', 'task_timers.user_id')
            ->leftJoin('tasks', 'tasks.id', '=', 'task_timers.task_id')
            ->leftJoin('projects', 'tasks.related_to_id', '=', 'projects.id');

        if (isset($conditions) && ! empty($conditions)) {
        	$query = $query->where($conditions);
        }
        if (isset($options['running']) && $options['running'] != null && $options['running'] == 'no') {
			$query = $query->where('task_timers.end_time', '!=', null);
		}
		if (isset($from)) {
			$query = $query->whereBetween('task_timers.start_time',[$from, $to]);
		}

        return $query;
    }
    

}
