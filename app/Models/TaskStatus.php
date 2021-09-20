<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;

class TaskStatus extends Model
{
	public $timestamps = false;
    
    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'task_status_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-task_statues');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-task_statues', $data, 30 * 86400);
        }
        return $data;
    }
}
