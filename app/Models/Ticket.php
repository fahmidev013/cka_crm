<?php

namespace App\Models;

use App\Http\Start\Helpers;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id');
    }

    public function ticketStatus()
    {
        return $this->belongsTo('App\Models\TicketStatus','ticket_status_id');
    }

    public function priority()
    {
        return $this->belongsTo('App\Models\Priority','priority_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id');
    }

    public function assignedMember()
    {
        return $this->belongsTo('App\Models\User','assigned_member_id');
    }

    public function ticketReplies()
    {
      return $this->hasMany('App\Models\TicketReply','ticket_id');
    }
    public function tasks()
    {
        return $this->belongsTo('App\Models\Task', 'id', 'related_to_id');
    }

    public function getAllTicketDT($from, $to, $status, $project, $departmentId, $customerId = null, $flag = null, $assigneeId = null)
    {
        $result    = DB::table('tickets')
        ->leftjoin('priorities', 'priorities.id', '=', 'tickets.priority_id')
        ->leftjoin('departments', 'departments.id', '=', 'tickets.department_id')
        ->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->leftjoin('projects','projects.id','=','tickets.project_id')
        ->leftjoin('customers','customers.id','=','tickets.customer_id')
        ->leftjoin('users','users.id','=','tickets.assigned_member_id')
        ->select('tickets.*', 'priorities.name as priority', 'priorities.id as priority_id', 'departments.name as department_name', 'ticket_statuses.name as status', 'ticket_statuses.color as color','projects.name as project_name','projects.id as project_table_id','customers.first_name as first_name','customers.last_name as last_name', 'users.full_name as assignee_name', 'users.id as assignee_id');

        if(!empty($from) && !empty($to)) {
          $from = DbDateFormat($from);
          $to   = DbDateFormat($to);

          $result ->whereDate('tickets.date', '>=', $from)
                  ->whereDate('tickets.date', '<=', $to);
         }

         if(!empty($status)) {
          $result->where('tickets.ticket_status_id','=', $status);
         }
         if(!empty($project)) {
          $result->where('tickets.project_id','=', $project);
         }
         if(!empty($departmentId)) {
          $result->where('tickets.department_id','=', $departmentId);
         }

         if(!empty($customerId)) {
          $result->where('tickets.customer_id', '=', $customerId);
         }

         if(!empty($assigneeId)) {
          $result->where('tickets.assigned_member_id', '=', $assigneeId);
         }

         if (empty($flag)) {
           if (Helpers::has_permission(Auth::user()->id, 'own_ticket') && !Helpers::has_permission(Auth::user()->id, 'manage_ticket')) {
              $id = Auth::user()->id;
              $result->where('tickets.user_id', $id);
           }
         }

        return $result;

    }

    public function getTicketSummary($from, $to, $status, $project, $departmentId, $customerId = null, $assigneeId = null)
    {
        $whereConditions = '';
        if (empty($from) || empty($to)) {
          $date_range = null;
        } else if (empty($from)) {
          $date_range = null;
        } else if (empty($to)) {
            $date_range = null;
        } else {
          $date_range = 'Available';
        }

        if (!empty($project)) {
          $whereConditions .= " AND tickets.project_id = '$project'";
        }
        if (!empty($departmentId)) {
          $whereConditions .= " AND tickets.department_id = '$departmentId'";
        }
        if (!empty($status)) {
          $whereConditions .= " AND tickets.ticket_status_id = '$status'";
        }
        if (!empty($customerId)) {
          $whereConditions .= " AND tickets.customer_id = '$customerId'";
        }
        if (!empty($assigneeId)) {
          $whereConditions .= " AND tickets.assigned_member_id = '$assigneeId'";
        }

        if (!empty($date_range)) {
          $from = DbDateFormat($from);
          $to   = DbDateFormat($to);

          $summary = DB::select(DB::raw("SELECT COUNT(tickets.ticket_status_id)
                    as total_status,ticket_statuses.name,ticket_statuses.id, ticket_statuses.color as color
                    FROM tickets
                    RIGHT JOIN ticket_statuses
                    ON ticket_statuses.id = tickets.ticket_status_id
                    $whereConditions
                    AND  date(tickets.date) BETWEEN '$from' AND '$to'
                    GROUP BY ticket_statuses.id"));
        } else {
          $summary = DB::select(DB::raw("SELECT COUNT(tickets.ticket_status_id)
                    as total_status,ticket_statuses.name,ticket_statuses.id, ticket_statuses.color as color
                    FROM tickets
                    RIGHT JOIN ticket_statuses
                    ON ticket_statuses.id = tickets.ticket_status_id
                    $whereConditions
                    GROUP BY ticket_statuses.id"));
        }
        return $summary;
    }

    public function getTicketStat($from = null, $to = null)
    {
        $results = [];
        $ticketStat = [];
        if (!empty($from) && !empty($to)) {
          $results = DB::select(DB::raw("SELECT COUNT(t.id) as ticketCount, ts.name FROM ticket_statuses as ts LEFT JOIN `tickets` as t on(t.ticket_status_id = ts.id) WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' GROUP BY ts.name"));
        } else {
          $results = DB::select(DB::raw("SELECT COUNT(t.id) as ticketCount, ts.name FROM ticket_statuses as ts LEFT JOIN `tickets` as t on(t.ticket_status_id = ts.id) WHERE 1 GROUP BY ts.name"));
        }
        foreach ($results as $key => $value) {
          $ticketStat[$value->name] = $value->ticketCount;
        }

        return $ticketStat;
    }

    public function getExceptClickedStatus($status, $customerId = null, $projectId = null, $departmentId = null, $form = null, $to = null)
    {
      $data = DB::table('tickets')
                ->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
                ->select('ticket_statuses.name', DB::raw("COUNT(tickets.ticket_status_id) as total_status"),'ticket_statuses.id', 'ticket_statuses.color as color')
                ->groupBY('ticket_statuses.id');
      if (!empty($status)) {
        $data->where('tickets.ticket_status_id', '=', $status);
      }
      if (!empty($customerId)) {
        $data->where('tickets.customer_id', '=', $customerId);
      }
      if (!empty($departmentId)) {
        $data->where('tickets.department_id', '=', $departmentId);
      }
      if (!empty($projectId)) {
        $data->where('tickets.project_id', '=', $projectId);
      }
      if (!empty($form)) {
        $data->whereDate('tickets.date', '>=', DbDateFormat($form));
      }
      if (!empty($to)) {
         $data->whereDate('tickets.date', '<=', DbDateFormat($to));
       }
      return $data->get();

    }

    public function getFilteredStatus($options = [])
    {
      $conditions = [];
      $otherStatuses = DB::table('tickets');
      $flag=0;
      $customerId = isset($options['customerId']) && !empty($options['customerId']) ? $options['customerId'] : null;
      if (isset($options['allproject']) && !empty($options['allproject'])) {
          $conditions['project_id'] = $options['allproject'];
      }

      if (isset($options['alldepartment']) && !empty($options['alldepartment'])) {
          $conditions['department_id'] = $options['alldepartment'];
      }

      if (isset($options['allstatus']) && !empty($options['allstatus']) ) {
        $otherStatuses = $this->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')->where('tickets.ticket_status_id', '!=', $options['allstatus']);

        if (!empty($conditions['project_id'])) {
          $otherStatuses->where('tickets.project_id', $options['allproject']);
        }

        if (isset($options['customerId']) && !empty($options['customerId'])) {
              $otherStatuses->where('tickets.customer_id', $customerId);
         }

         if (isset($options['allassignee']) && !empty($options['allassignee'])) {
              $otherStatuses->where('tickets.assigned_member_id', $options['allassignee']);
         }

        if (isset($options['from']) && !empty($options['from'])) {
            $otherStatuses->whereDate('tickets.date', '>=', DbDateFormat($options['from']));
        }

        if (isset($options['to']) && !empty($options['to'])) {
            $otherStatuses->whereDate('tickets.date', '<=', DbDateFormat($options['to']));
        }

        $otherStatuses = $otherStatuses->select(DB::raw('COUNT(tickets.ticket_status_id) as total_status'), 'ticket_statuses.name', 'ticket_statuses.id', 'ticket_statuses.color as color')->groupBY('ticket_statuses.id');

        $flag = 1;
      }

      return $flag ? $otherStatuses->get() : "";

    }

    public function getLatestTicket($from = null, $to = null)
    {
      $query = DB::table('tickets')
                ->leftjoin('priorities', 'priorities.id', '=', 'tickets.priority_id')
                ->leftjoin('departments', 'departments.id', '=', 'tickets.department_id')
                ->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
                ->select('tickets.*', 'priorities.name as priority', 'departments.name as department', 'ticket_statuses.name as status', 'ticket_statuses.color as color')
                ->orderBy('tickets.last_reply', 'DESC');
      if (!empty($from) && !empty($to)) {
        $query->whereBetween('tickets.date', [$from, $to]);
      }
      return $query->take(5)->get();
    }

    public function getAllTicketDetailsById($id)
    {
      $res = Ticket::with(['ticketReplies' => function($q) {
        $q->orderBy('id', 'desc')->first();
      }, 'priority:id,name', 'department:id,name', 'ticketStatus:id,name,color',  'customer:id,email,name', 'project:id,name', 'user:id,email,full_name', 'tasks:id,name,related_to_id', 'assignedMember:id,email,full_name'])->where('id', $id)->first();
      return $res;
    }

    public function getcreateFiles($id){
        $data= DB::table('files')->where(['ticket_id'=>$id,'ticket_reply_id'=>0])->get();
        return $data;
    }

    public function getRepliesFiles($id){
        $data= DB::table('files')->where(['ticket_id'=>$id])->where('ticket_reply_id','!=',0)->get();
        return $data;
    }

    public function getAllCustomerFiles($id,$customer_id){
     $data= DB::table('files')->where(['ticket_id'=>$id,'customer_id'=>$customer_id,'ticket_reply_id'=>0])->select('id','file_name','original_file_name')->get();
     return $data;
    }

    public function getAllTicketRepliersById($id)
    {
      $res = TicketReply::with('user:id,full_name,email', 'customer:id,name,email')->where('ticket_id', $id)->orderBy('date', 'desc')->get();
      return $res;
    }

    public function getAllTicketRepliersWithoutLast2($id)
    {
        $result = DB::table('ticket_replies')
            ->leftjoin('users', 'users.id', '=', 'ticket_replies.user_id')
            ->leftjoin('files', 'files.ticket_reply_id', '=', 'ticket_replies.id')
            ->select('ticket_replies.*', 'users.id as team_member_id', 'users.full_name as team_member_name', 'files.file_name', 'files.original_file_name', 'users.picture')
            ->where('ticket_replies.ticket_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return $result;

    }

    public function getProjectAllTicketDetailsById($project_id,$id){
        $result = DB::table('tickets')
            ->leftjoin('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->leftjoin('departments', 'departments.id', '=', 'tickets.department_id')
            ->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.ticket_status_id')
            ->leftjoin('users', 'users.id', '=', 'tickets.assigned_member_id')
            ->select('tickets.*', 'priorities.name as priority', 'departments.name as department', 'ticket_statuses.name as status', 'users.id as user_id', 'users.full_name as user_name', 'users.email as team_member_email')
            ->where(['tickets.id'=> $id, 'project_id' => $project_id])
            ->first();
        return $result;
    }

}
