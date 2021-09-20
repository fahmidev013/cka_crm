<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskAssign;
use App\Http\Start\Helpers;
use App\Models\TaskTimer;
use App\Models\Task;
use App\Models\Project;
use App\Http\Controllers\TaskController;
use Auth;
use DB;

class TaskTimerController extends Controller
{
	public function __construct(Task $task, TaskController $taskController) {
        $this->task = $task;
        $this->taskController = $taskController;
    }
    public function start(Request $request)
    {
        $result = ["success" => 0];
        $user_id            = Auth::user()->id;
        $data['user_id']    = $user_id;
        $data['task_id']    = $request->task_id;
        $data['start_time'] = strtotime(date("Y-m-d H:i:s"));
        $data['end_time']   = null;
        $data['note']       = null;
        $runnintTask        = TaskTimer::where('user_id', $user_id)->whereNull('end_time')->first();
        $assignedTask       = TaskAssign::where(['user_id' => $user_id, 'task_id' => $request->task_id])->first();
        $previousStatus = Task::with('taskStatus')->where('id', $request->task_id)->first(['task_status_id']);
        if (empty($assignedTask)) {
            $result['success']  = 2;
        } else if (empty($runnintTask)) {
            $result['timer_id'] = DB::table('task_timers')->insertGetId($data);
            if ($request->taskStatusId == 1 || $request->taskStatusId == 5 || $request->taskStatusId == 6) {
                if (Task::where('id', $request->task_id)->update(['task_status_id' => 2])) {
                    $status = Task::with('taskStatus')->where('id', $request->task_id)->first(['task_status_id']);
                    $this->taskController->changeMailStatus($data['task_id'], $previousStatus, $status);
                    $result['statusId'] = $status->task_status_id;
                    $result['statusName'] = $status->taskStatus->name;
                }
            }
            $result['success']  = 1;
        }
        echo json_encode($result);
        exit();
    }

    public function end(Request $request)
    {
        $data = ["success" => 0];
        $timer_id         = $request->timer_id;
        $task_id          = $request->task_id;
        $timer['end_time'] = strtotime(date("Y-m-d H:i:s"));
        $timer['note']     = $request->note;
        if(DB::table('task_timers')->where('id', $timer_id)->update($timer)) {
            $data['success'] = 1;
            $data['logged_time'] = $this->task->getTotalLoggedTimeById($task_id);
            if (!empty($request->relatedToType) && $request->relatedToType == 1) {
                $cost = (new Task())->projectCost($request->chargeType, $request->ratePerHour, $request->relatedToId);
                Project::where('id', $request->relatedToId)->update(['cost' => $cost]);
            }
        }
        echo json_encode($data);
        exit();
    }

    public function checkTimer(Request $request)
    {
        $task_id       = $request->task_id;
        $user_id       = Auth::user()->id;
        $timer         = DB::table('task_timers')->where(['user_id' => $user_id, 'task_id' => $task_id])->latest('id', 'desc')->first();
        $data = ['timer' => $timer];
        echo json_encode($data); 
        exit();
    }

    public function get(Request $request)
    {
        $user                  = Auth::user()->id;
        $data = ['success' => 0, 'message' => __('No record found')];
        $task_id               = $request->task_id;
        $data['assignee_list'] = DB::table('task_assigns')
            ->leftJoin('users', 'users.id', '=', 'task_assigns.user_id')
            ->where('task_assigns.task_id', $task_id)
            ->select('task_assigns.user_id', 'users.full_name')
            ->get();
        $timerDetails = DB::table('task_timers')
            ->leftJoin('users', 'users.id', '=', 'task_timers.user_id')
            ->select('task_timers.*', 'users.full_name', DB::raw('(CASE WHEN task_timers.user_id = ' . $user . ' THEN 1 ELSE 0 END) AS is_user'))
            ->where('task_id', $task_id)
            ->get();
        if (!empty($timerDetails)) {
            $i          = 0;
            $return_arr = array();
            foreach ($timerDetails as $key => $value) {
                $start = $value->start_time;

                $startDate = timeZoneformatDate(date("m/d/Y h:i:s A T", $start));
                $startTime = timeZonegetTime(date("m/d/Y h:i:s A T", $start));

                if (!empty($value->end_time)) {
                    $end     = $value->end_time;
                    $endDate = timeZoneformatDate(date("m/d/Y h:i:s A T", $end));
                    $endTime = timeZonegetTime(date("m/d/Y h:i:s A T", $end));
                } else {
                    $endDate = '';
                    $endTime = '';
                }
                $diff = ($value->end_time > $value->start_time) ? ($value->end_time - $value->start_time) : null;

                $hours    = floor($diff / 3600) > 0 ? floor($diff / 3600) . 'h ' : '';
                $minutes  = floor(($diff / 60) % 60) > 0 ? floor(($diff / 60) % 60) . 'm ' : '';
                $seconds  = $diff % 60;
                $diffTime = $hours . $minutes . $seconds . 's';

                $return_arr[$i]['id']         = $value->id;
                $return_arr[$i]['is_user']    = $value->is_user;
                $return_arr[$i]['full_name']  = $value->full_name;
                $return_arr[$i]['start_time'] = $startDate . '<br>' . $startTime;
                $return_arr[$i]['end_time']   = $endDate . '<br>' . $endTime;
                $return_arr[$i]['spent_time'] = ($value->end_time ? $diffTime : '');
                $i++;
            }

            $timerData                 = ($return_arr);
            $data['delete_task_timer'] = Helpers::has_permission(Auth::user()->id, 'delete_task_timer');
            $data['add_task_timer']    = Helpers::has_permission(Auth::user()->id, 'add_task_timer');
            $data['success']           = 1;
            $data['message']           = count($timerData) . __(' record found');
            $data['data']              = $timerData;
        }
        echo json_encode($data);
        exit();
    }

    public function delete(Request $request) 
    {
        $data = ['success' => 0];
        $id     = $request->task_timer_id;
        $task_id = $request->task_id;
        if (isset($id)) {
            $record = DB::table('task_timers')->where('id', $id)->first();
            if ($record) {
                DB::table('task_timers')->where('id', '=', $id)->delete();
                $data['logged_time'] = $this->task->getTotalLoggedTimeById($task_id);
                $data['success'] = 1;
                if (!empty($request->relatedToType) && $request->relatedToType == 1) {
                    $cost = (new Task())->projectCost($request->chargeType, $request->ratePerHour, $request->relatedToId);
                    Project::where('id', $request->relatedToId)->update(['cost' => $cost]);
                }
            }
        }
        
        echo json_encode($data);
    }

    public function storeManualTime(Request $request) 
    {
        $rdata = ['status' => 'fail'];
        $data['task_id']    = $request->task_id;
        $data['user_id']    = $request->assignee;
        $data['start_time'] = strtotime(subtractZonegetTime($request->start_time));
        $data['end_time']   = strtotime(subtractZonegetTime($request->end_time));
        $data['note']       = $request->note;

        $id = DB::table('task_timers')->insert($data);
        if ($id) {
            $rdata['status']  = 'success';
            $rdata['task_id'] = $request->task_id;
            $rdata['logged_time'] = $this->task->getTotalLoggedTimeById($request->task_id);
            if (!empty($request->relatedToType) && $request->relatedToType == 1) {
                $cost = (new Task())->projectCost($request->chargeType, $request->ratePerHour, $request->relatedToId);
                Project::where('id', $request->relatedToId)->update(['cost' => $cost]);
            }
        }
        
        return $rdata;
    }
}
