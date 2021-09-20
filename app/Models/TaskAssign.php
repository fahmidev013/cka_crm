<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TaskAssign extends Model
{
	public $timestamps = false;
    
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
