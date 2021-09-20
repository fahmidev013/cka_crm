<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
	public $timestamps = false;

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'project_status_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'status');
    }

    public function getProjectStat($from = null, $to = null)
    {
        $results = [];
        $projectStat = [];
        if (!empty($from) && !empty($to)) {
            $results = DB::select(DB::raw("SELECT COUNT(p.id) as projectCount, ps.name FROM project_statuses as ps LEFT JOIN `projects` as p on(p.project_status_id = ps.id) WHERE p.begin_date BETWEEN '" . $from . "' AND '" . $to . "' GROUP BY ps.name"));
        } else {
            $results = DB::select(DB::raw("SELECT COUNT(p.id) as projectCount, ps.name FROM project_statuses as ps LEFT JOIN `projects` as p on(p.project_status_id = ps.id) WHERE 1 GROUP BY ps.name"));
        }
        foreach ($results as $key => $value) {
            $projectStat[$value->name] = $value->projectCount;
        }
        return $projectStat;
    }

}
