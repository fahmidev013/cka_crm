<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo("App\Models\Project", 'project_id');
    }
}
