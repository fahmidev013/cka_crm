<?php

namespace App\Models;
use App\Http\Start\Helpers;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Project extends Model
{
    public $timestamps = false;
    // Relation Start
    public function projectMembers()
    {
        return $this->hasMany("App\Models\ProjectMember", 'project_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function projectStatuses()
    {
        return $this->belongsTo('App\Models\ProjectStatus', 'project_status_id');
    }

    public function saleOrders()
    {
        return $this->hasMany('App\Models\SaleOrder', 'project_id');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'project_id');
    }
    //Relation End
    
    public static function getAll()
    {
      $data = Cache::get('gb-projects');
      if (empty($data)) {
          $data = parent::all();
          Cache::put('gb-projects', $data, 30 * 86400);
      }
      return $data;
    }

    public function getAllProjectDT($from, $to, $status, $project_type)
    {
      $result = DB::table('projects')
            ->leftJoin('customers', 'customers.id', '=', 'projects.customer_id')
            ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
            ->select('projects.id as project_id', 'projects.name', 'projects.project_type', 'projects.detail', 'projects.charge_type', 'projects.begin_date', 'projects.due_date', 'customers.first_name', 'customers.last_name', 'customers.id as customer_id', 'project_statuses.name as status_name', 'project_statuses.id as status_id');
            
          if (!empty($from) && !empty($to)) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $result ->where('projects.begin_date', '>=', $from)
                    ->where('projects.begin_date', '<=', $to);
          }
          if (!empty($status)) {
            $result->where('project_statuses.id', '=', $status);
          } else {
            $result->where('project_statuses.id', '!=', 6);
          }
          if (!empty($project_type)) {
            $result->whereIn('projects.project_type', $project_type);
          }

          if (Helpers::has_permission(Auth::user()->id, 'own_project') && !Helpers::has_permission(Auth::user()->id, 'manage_project')) {
            $id = Auth::user()->id;
            $result->where('projects.user_id', $id);
          } 
          return $result;
    }

    public function getLatestProject($from = null, $to = null) 
    {
      $data = [];
      $query = [];
      if (!empty($from) && !empty($to)) {
        $data = DB::table('projects')
              ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
              ->whereBetween('begin_date', [$from, $to])
              ->select('projects.id as project_id', 'projects.name', 'projects.detail', 'projects.charge_type', 'projects.begin_date as project_begin_date', 'projects.due_date as project_due_date', 'project_statuses.name as status_name', 'project_statuses.id as status_id', 'projects.created_at')
              ->orderBy('project_begin_date', 'DESC')
              ->take(5)
              ->get();
      } else {
        $data = DB::table('projects')
              ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
              ->select('projects.id as project_id', 'projects.name', 'projects.detail', 'projects.charge_type', 'projects.begin_date as project_begin_date', 'projects.due_date as project_due_date', 'project_statuses.name as status_name', 'project_statuses.id as status_id')
              ->orderBy('project_begin_date', 'DESC')
              ->take(5)
              ->get();
      }
       return $data;
    }



    public function getAllProjectByCustomer($from,$to,$customer_id)
    {
      $from = DbDateFormat($from);
      $to   = DbDateFormat($to);

      $match = [['projects.begin_date', '>=', $from], ['projects.begin_date', '<=', $to], ['projects.customer_id', '=', $customer_id]];

      $result = DB::table('projects')
      ->leftJoin('customers','customers.id', '=', 'projects.customer_id')
      ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
      ->where($match)
      ->select('projects.id as project_id', 'projects.name', 'projects.detail', 'projects.charge_type', 'projects.begin_date', 'projects.due_date', 'customers.name as customer_name', 'customers.id as customer_id', 'project_status.name as status_name', 'project_status.id as status_id');
      return $result;
    }

    public function getAllProjectByUser($from, $to, $id, $status, $project_type)
    {

      $match = [];
      if (!empty($from)) {
        $match[] = ['projects.begin_date', '>=', DbDateFormat($from)];
      }
      if (!empty($to)) {
        $match[] = ['projects.begin_date', '<=', DbDateFormat($to)];
      }
      if (!empty($status)) {
        $match[] = ['project_statuses.id', '=', $status];
      } else {
        $match[] = ['project_statuses.id', '!=', 6];
      }
      $match[] = ['project_members.user_id', '=', $id];
      $result = DB::table('projects')
      ->leftJoin('project_members', 'project_members.project_id', '=', 'projects.id')
      ->leftJoin('customers', 'customers.id', '=', 'projects.customer_id')
      ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
      ->leftJoin('tasks', function($join) {
        $join->on('tasks.related_to_id', '=', 'projects.id');
        $join->whereRaw('tasks.related_to_type = 1');
      })
      ->where($match)
      ->select('projects.id as project_id', 'projects.name', 'projects.detail', 'projects.charge_type', 'projects.begin_date', 'projects.project_type', 'projects.due_date', 'customers.first_name', 'customers.last_name', 'customers.id as customer_id', 'project_statuses.name as status_name', 'project_statuses.id as status_id', 'tasks.task_status_id as task_status_id', 'tasks.id as task_id');
      if ($project_type) {
        if (gettype($project_type) != "array") {
          if (strpos($project_type, ',') !== false) {
            $project_type_ar = explode(',', $project_type);
            $result->whereIn('projects.project_type', $project_type_ar);
          } else {
            $result->where('projects.project_type', $project_type);
          }
        } else {
          $result->whereIn('projects.project_type', $project_type);
        }
      }

      return $result->groupBy('projects.id');
    }


     public function getAllProjectByCustomerPanel($customer_id, $from = '', $to = '')
    {
      $match = [['projects.customer_id', '=', $customer_id]];

      $result = DB::table('projects')
      ->leftJoin('customers','customers.id','=','projects.customer_id')
      ->leftJoin('project_statuses','project_statuses.id','=','projects.project_status_id')
      ->where($match)
      ->select('projects.id as project_id','projects.name','projects.detail','projects.charge_type','projects.begin_date','projects.due_date','customers.name as customer_name','customers.id as customer_id','project_status.name as status_name','project_status.id as status_id');

      if ($from != '' && $to != '') {
          $result = $result->where('projects.begin_date', '>=', $from)->where('projects.begin_date', '<=', $to);
      }

      return $result;
    }

    public function getAllProjectByCustomerCSV($from, $to, $customer_id)
    {
        $data  = Project::where(['customer_id' => $customer_id, 'project_type' => 'customer']);
        if ($from && $to) {
            $data = null;
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $data  = Project::where(['customer_id' => $customer_id, 'project_type' => 'customer'])
            ->whereDate('begin_date', '>=', DbDateTimeFormat($from))
            ->whereDate('begin_date', '<=', DbDateTimeFormat($to))->orderBy('id', 'DESC');
        }

        return $data;
    }


    public function getAllProjectPdf($from, $to, $status, $project_type)
    {
      $result = DB::table('projects')
            ->leftJoin('customers', 'customers.id', '=', 'projects.customer_id')
            ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
            ->leftJoin('tasks', function($join) {
                  $join->on('tasks.related_to_id', '=', 'projects.id');
                  $join->whereRaw('tasks.related_to_type = 1');
              })
            ->select('projects.id as project_id', 'projects.name', 'projects.project_type', 'projects.detail', 'projects.charge_type', 'projects.begin_date', 'projects.due_date', 'customers.first_name', 'customers.last_name', 'customers.id as customer_id', 'project_statuses.name as status_name', 'project_statuses.id as status_id', 'tasks.id as task_id', 'tasks.name as task_name', 'tasks.related_to_id as task_related_to_id', 'tasks.related_to_type as task_related_to_type', 'tasks.task_status_id as task_status_id');
            
      if ($from && $to) {
        $from = DbDateFormat($from);
        $to   = DbDateFormat($to);
        $result ->where('projects.begin_date', '>=', $from)
                ->where('projects.begin_date', '<=', $to);
      }

      if (!empty($status)) {
        $result->where('project_statuses.id', '=', $status);
      } else {
        $result->where('project_statuses.id', '!=', 6);
      }

      if ($project_type) {
        if (gettype($project_type) != "array") {
          if (strpos($project_type, ',') !== false) {
            $project_type_ar = explode(',', $project_type);
            $result->whereIn('projects.project_type', $project_type_ar);
          } else {
            $result->where('projects.project_type', $project_type);
          }
        } else {
          $result->whereIn('projects.project_type', $project_type);
        }
      }

      return $result;
    }

    public function getAllProject($id)
    {
      $result = DB::table('projects')
      ->leftJoin('customers', 'customers.id', '=', 'projects.customer_id')
      ->leftJoin('project_statuses', 'project_statuses.id', '=', 'projects.project_status_id')
      ->select('projects.id as project_id', 'projects.name', 'projects.detail', 'projects.charge_type', 'projects.begin_date', 'projects.due_date', 'customers.first_name', 'customers.last_name', 'customers.id as customer_id', 'project_statuses.name as status_name', 'project_statuses.id as status_id')
      ->where('projects.id', $id)->first();
      return $result;
    }

    public function projectLoggedTime($id)
    {
      $project_tasks = DB::table('tasks')
                     ->where(['related_to_type' => 1, 'related_to_id' => $id])
                     ->select('tasks.id')
                     ->get();

      $task_logged_time = array();
      foreach ($project_tasks as $key => $task_id) {
          $total_time = DB::table('task_timers')
                  ->where('task_id', $task_id->id)
                  ->get();
          foreach ($total_time as $key => $value) {
              if ($value->end_time > $value->start_time) {
                  $task_logged_time[] = ($value->end_time > $value->start_time) ? ($value->end_time - $value->start_time) : 0;
              }
          }
      }
      $project_total_time = array_sum($task_logged_time);

      $hours = floor($project_total_time / 3600) > 0 ? floor($project_total_time / 3600).'h ' : '';
      $minutes = floor(($project_total_time / 60) % 60) > 0 ? floor(($project_total_time / 60) % 60) .'m ' : '' ;
      $seconds = $project_total_time % 60;
      $project_total_time = $hours.$minutes.$seconds.'s';

      return $project_total_time;
    }

}
