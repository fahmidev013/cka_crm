<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use Excel;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DataTables\AllTaskDataTable;
use App\DataTables\TaskDataTable;
use App\DataTables\ProjectTimesheetDataTable;
use App\Exports\projectTaskExport;
use App\Http\Start\Helpers;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Models\ChecklistItem;
use App\Models\Customer;
use App\Models\File;
use App\Models\Milestone;
use App\Models\Preference;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskAssign;
use App\Models\TaskComment;
use App\Models\TaskTimer;
use App\Models\TaskStatus;
use App\Models\Ticket;
use App\Models\Activity;
use App\Models\EmailTemplate;
use App\Models\TagAssign;

use App\Http\{
    Controllers\EmailController
};

class TaskController extends Controller
{

    private $_priorities = [1 => 'Low', 2 => 'Medium', 3 => 'High'];

    public function __construct(Request $request, Task $task, ProjectTimesheetDataTable $projectTimesheetDataTable, TaskDataTable $taskDataTable, Project $project, AllTaskDataTable $allTaskDataTable, EmailController $email)
    {
        $this->request          = $request;
        $this->task             = $task;
        $this->project          = $project;
        $this->email            = $email;
        $this->taskDataTable    = $taskDataTable;
        $this->projectTimesheetDataTable = $projectTimesheetDataTable;
        $this->allTaskDataTable = $allTaskDataTable;
    }

    public function index($id = null)
    {
        $data = [];
        $user_id = Auth::user()->id;
        $data = ['menu' => 'task', 'page_title' => __('Tasks')];
        $data['task_statuses_all'] = TaskStatus::getAll();
        $taskList = TaskStatus::getAll();
        $tags                    = Tag::pluck('name')->toArray();
        $data['tags']            = json_encode($tags);
        $data['projects']        = Project::getAll()->where('project_status_id', '!=', 6);
        $data['assignees']       = User::where('is_active', 1)->get();
        $data['date_type']       = isset($_GET['date_type'])? $_GET['date_type'] : null;
        $data['from']        = isset($_GET['from']) && !empty($_GET['from']) ? $_GET['from'] : null;
        $data['to']          = isset($_GET['to'])  && !empty($_GET['from']) ? $_GET['to'] : null;
        $data['allstatus']   = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status']    : null;
        $data['allproject']  = isset($_GET['project']) && !empty($_GET['project']) ? $_GET['project']   : null;
        $data['allassignee'] = $allassignee = isset($_GET['assignee']) ? $_GET['assignee']  : $user_id;
        $relatedType = !empty($data['allproject']) ? 1 : '';
        $task_summary = (new Task())->getTaskSummary(['task_status_id' => $data['allstatus'], 'related_to_id' => $data['allproject'], 'related_to_type' => $relatedType, 'from' => $data['from'], 'to' => $data['to'], 'user_id' => $user_id, 'allassignee' => $allassignee])->get();
        $summary = [];
        $stack   = [];
        for ($i = 0; $i < count($taskList); $i++) {
            for ($j = 0; $j < count($task_summary); $j++) {
                if ($taskList[$i]->id == $task_summary[$j]->id) {
                    $summary[$i]['id']           = $task_summary[$j]->id;
                    $summary[$i]['name']         = $task_summary[$j]->name;
                    $summary[$i]['color']        = $task_summary[$j]->color;
                    $summary[$i]['total_status'] = $task_summary[$j]->total_status;
                    $stack[]                     = $task_summary[$j]->id;
                } else {
                    if (!in_array($taskList[$i]->id, $stack)) {
                        $summary[$i]['id']           = $taskList[$i]->id;
                        $summary[$i]['name']         = $taskList[$i]->name;
                        $summary[$i]['color']        = $taskList[$i]->color;
                        $summary[$i]['total_status'] = 0;
                    }
                }
            }
        }
        if (count($task_summary) === 0) {
            for ($i = 0; $i < count($taskList); $i++) {
                $summary[$i]['id']           = $taskList[$i]->id;
                $summary[$i]['name']         = $taskList[$i]->name;
                $summary[$i]['color']        = $taskList[$i]->color;
                $summary[$i]['total_status'] = 0;
            }
        }
        $data['summary'] = $summary;
        // Counting summary end

        //Individual counting
        if ($allassignee != Auth::user()->id) {
            for ($i = 0; $i < count($taskList); $i++) {
                $result = DB::table('tasks')
                    ->join('task_assigns',function($join) use ($user_id){
                        $join->on('task_assigns.task_id', 'tasks.id');
                        $join->where('task_assigns.user_id', $user_id);
                    })
                    ->join('task_statuses',function($join) use ($i){
                        $join->on('task_statuses.id', 'tasks.task_status_id');
                        $join->where('task_statuses.id', $i + 1);
                    });
                if (!empty($data['allproject'])) {
                    $result->where(['related_to_id' => $data['allproject'], 'related_to_type' => 1]);
                }
                $assign_to_me[$i] =  $result->count();
            }
            $data['assign_to_me'] = $assign_to_me;
        }

        if (!empty($id)) {
            $task = Task::where('id', $id)->first();
            if (empty($task)) {
                Session::flash('fail', __('This task does not exist.'));
                return redirect('/task/list');
            }
            $data['task'] = $task;
        }
        //Individual counting end

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->allTaskDataTable->with('row_per_page', $row_per_page)->render('admin.task.list', $data);
    }

    public function taskView()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $task = $this->task->getTaskDetailsById($_GET['id']);
            $taskcomments = $this->task->getAllComment($_GET['id']);

            $data = ['files' => (new File)->getFiles('Task', $_GET['id'])];
            if (!empty($data['files'])) {
                $data['filePath'] = "public/uploads/tasks";
                foreach ($data['files'] as $key => $value) {
                    $value->icon = getFileIcon($value->file_name);
                    $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
                }
            }

            if ($task->related_to_type == 2) {
                $customer                 = $this->task->taskCustomerName($task->related_to_id);
                $return_arr['related_to'] = $customer->first_name . ' ' . $customer->last_name;
            } else if ($task->related_to_type == 3) {
                $ticket                   = $this->task->taskTicketSubject($task->related_to_id);
                $return_arr['related_to'] = @$ticket->subject;
            } else {
                $return_arr['related_to'] = $task->project_name;
                $return_arr['chargeType'] = $task->charge_type;
                $return_arr['ratePerHour'] = $task->per_hour_project_scale;
            }

            // Getting checklist From DB
            $relatedCheckList = ChecklistItem::where("task_id", $_GET['id'])->get();
            $return_arr['checklist_items'] = !empty($relatedCheckList) ? $relatedCheckList : "";

            $return_arr['subject']         = $task->name;
            $return_arr['related_to_type'] = $task->related_to_type;
            $return_arr['related_to_id']   = $task->related_to_id;
           $return_arr['description'] = preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', "<a target='_blank' href=\"\\0\">\\0</a>", nl2br($task->description));

            $return_arr['editDescription'] = $task->description;
            $return_arr['recuring']        = $task->recurring == 1 ? 'Recurring Type' : '';
            $return_arr['created_at']['date'] = timeZoneformatDate($task->created_at);
            $return_arr['created_at']['time'] = timeZonegetTime($task->created_at);
            $return_arr['status_name']     = $task->status_name;
            $return_arr['status_id']       = $task->status_id;
            $return_arr['start_date']      = $task->start_date ? timeZoneformatDate($task->start_date) : '';
            $return_arr['due_date']        = $task->due_date ? timeZoneformatDate($task->due_date) : '';
            $return_arr['priority']        = $task->priority;
            $return_arr['priority_id']     = $task->priority_id;
            $return_arr['hourly_rate']     = $task->related_to_type == 1 ? $task->per_hour_project_scale : $task->hourly_rate;
            $return_arr['task_id']         = $_GET['id'];
            $data['return_arr']            = $return_arr;

            $data['comment']             = $this->taskCommentSlice($taskcomments);
            $data['fileDetails']         = $data['files'];
            $data['files']               = $this->taskFileSlice($data['files']);
            $data['logged_time']         = $this->task->getTotalLoggedTimeById($_GET['id']);
            $data['delete_task_comment'] = Helpers::has_permission(Auth::user()->id, 'delete_task_comment');
            $data['edit_task_comment']   = Helpers::has_permission(Auth::user()->id, 'edit_task_comment');

            return $data;
        }
    }

    public function taskCommentSlice($taskcomments)
    {
        $opts = [];
        for ($i = 0; $i < count($taskcomments); $i++) {
            $opts[$i]['id']          = $taskcomments[$i]->id;
            $opts[$i]['content']     = $taskcomments[$i]->content;
            $opts[$i]['task_id']     = $taskcomments[$i]->task_id;
            $opts[$i]['user_id']     = $taskcomments[$i]->user_id;
            $opts[$i]['customer_id'] = $taskcomments[$i]->customer_id;
            $opts[$i]['created_at']  = timeZoneformatDate($taskcomments[$i]->created_at) . ' ' . timeZonegetTime($taskcomments[$i]->created_at);
            $opts[$i]['user_name']   = $taskcomments[$i]->user_name;
            $opts[$i]['is_user']     = $taskcomments[$i]->is_user;
        }
        return $opts;
    }

    public function taskFileSlice($files)
    {
        $file_data = [];
        for ($i = 0; $i < count($files); $i++) {
            $file_data[$i]['id']                  = $files[$i]->id;
            $file_data[$i]['user_id']             = $files[$i]->user_id;
            $file_data[$i]['customer_id']         = $files[$i]->customer_id;
            $file_data[$i]['project_id']          = $files[$i]->project_id;
            $file_data[$i]['ticket_id']           = $files[$i]->ticket_id;
            $file_data[$i]['ticket_reply_id']     = $files[$i]->ticket_reply_id;
            $file_data[$i]['file_name']           = $files[$i]->file_name;
            $file_data[$i]['original_file_name']  = $files[$i]->original_file_name;
            $file_data[$i]['file_type']           = $files[$i]->file_type;
            $file_data[$i]['last_activity']       = $files[$i]->last_activity;
            $file_data[$i]['created_at']          = timeZoneformatDate($files[$i]->created_at) . ' ' . timeZonegetTime($files[$i]->created_at);
            $file_data[$i]['visible_to_customer'] = $files[$i]->visible_to_customer;
            $file_data[$i]['external']            = $files[$i]->external;
            $file_data[$i]['external_link']       = $files[$i]->external_link;
            $file_data[$i]['thumnail_link']       = $files[$i]->thumnail_link;
            $file_data[$i]['task_id']             = $files[$i]->task_id;
            $file_data[$i]['icon']                = $files[$i]->icon;

            if ($files[$i]->user_id && $files[$i]->user_id != 0) {
                $user = User::find($files[$i]->user_id);
                $file_data[$i]['uploader_name'] = $user->full_name;
            }
            if ($files[$i]->customer_id && $files[$i]->customer_id != 0) {
                $customer = Customer::find($files[$i]->customer_id);
                $file_data[$i]['uploader_name'] = $customer->first_name .' '. $customer->last_name;
            }
        }

        return $file_data;
    }


    public function changeStatus(Request $request)
    {
        $data = ['status' => 0];
        $task_id   = $request->task_id;
        $status_id = $request->status_id;
        if (!empty($task_id) && !empty($status_id)) {
            if ($status_id == 6) {
                $previousStatus = Task::where('id', $task_id)->first(['task_status_id']);
                $data['preStatusName'] = str_replace(' ', '', $previousStatus->taskStatus->name);
                $update = DB::table('tasks')->where(['id' => $task_id])->update(['task_status_id' => 6]);
            } else {
                $previousStatus = Task::where('id', $task_id)->first(['task_status_id']);
                $data['preStatusName'] = str_replace(' ', '', $previousStatus->taskStatus->name);
                $update = DB::table('tasks')->where(['id' => $task_id])->update(['task_status_id' => $status_id]);
            }
            if ($request->project_id != '') {
                $project = DB::table('projects')->where('id',$request->project_id)->first();
                if (isset($project->improvement_from_tasks) && $project->improvement_from_tasks == 1) {
                    $total_tasks = DB::table('tasks')
                                ->select(DB::raw('count(*) as total_task'))
                                ->where([
                                    'related_to_type' => 1,
                                    'related_to_id' => $request->project_id
                                ])->first();
                    $complete_tasks = DB::table('tasks')
                                   ->select(DB::raw('count(*) as complete_task'))
                                   ->where([
                                    'related_to_type' => 1,
                                    'related_to_id' => $request->project_id,
                                    'task_status_id' => 4
                                ])->first();
                    $completed = ($complete_tasks->complete_task/$total_tasks->total_task)*100;
                    DB::table('projects')->where(['id' => $request->project_id])->update(['improvement' => $completed]);
                }
                //Insert Activity
                $task_name  = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Change Status of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');
            }
            if ($update) {
                $newStatus = Task::where('id', $task_id)->first(['task_status_id']);
                $this->changeMailStatus($task_id, $previousStatus, $newStatus);
                $data['newStatusName'] = str_replace(' ', '', $newStatus->taskStatus->name);
                $data['newStatusColor'] = $newStatus->taskStatus->color;
                $data['status'] = 1;
            }
        }

        return $data;
    }

    public function getAllPriority()
    {
        if (isset($_GET['priority_id']) && !empty($_GET['priority_id'])) {
            $data = ['output' => ''];
            $priority_id    = $_GET['priority_id'];
            $project_id     = $_GET['project_id'];
            $task_id        = $_GET['task_id'];
            $priorities     = DB::table('priorities')->where('id', '!=', $priority_id)->get();
            foreach ($priorities as $key => $value) {
                $data['output'] .= '<a href="#" class="priority_change"  project_id="' . $project_id . '" data-id="' . $value->id . '" data-value="' . $value->name . '"  task_id="' . $task_id . '">' . $value->name . '</a>' . '<br/>';
            }

            return $data;
        }
    }

    public function changePriority(Request $request)
    {
        $data = ['status' => 0];
        $task_id     = $request->task_id;
        $priority_id = $request->priority_id;

        if (!empty($task_id) && !empty($priority_id)) {
            $confirm = DB::table('tasks')->where(['id' => $task_id])->update(['priority_id' => $priority_id]);
            if (!empty($request->project_id)) {
                //Insert Activity
                $task_name  = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Change Priority of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');
            }
            if ($confirm) {
                $data['status'] = 1;
            }
        }

        return $data;
    }

    public function updateDescription(Request $request)
    {
        $data = [];
        $task_id = $request->task_id;
        $description = strip_tags($request->description);
        if (DB::table('tasks')->where('id', $task_id)->update(['description' => $description])) {
           $data['status'] = 1;
        }
        $data['description'] = !empty($description) ? preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', "<a target='_blank' href=\"\\0\">\\0</a>", nl2br($description)) : '';

        return $data;
    }

    public function getAllStatus()
    {
        $data = ['output' => ''];
        $status_id      = $_GET['status_id'];
        $project_id     = $_GET['project_id'];
        $task_id        = $_GET['task_id'];
        $tasks          = DB::table('task_statuses')->where('id', '!=', $status_id)->get();
        foreach ($tasks as $key => $value) {
            $data['output'] .= '<a href="#" class="status_change" project_id="' . $project_id . '" data-id="' . $value->id . '" data-value="' . $value->name . '" task_id="' . $task_id . '" >' . $value->name . '</a>' . '<br/>';
        }

        return $data;
    }

    public function getAllAssignee()
    {
        if (isset($_GET['task_id']) && !empty($_GET['task_id'])) {
            $data = ['status' => 0];
            $task_id = $_GET['task_id'];
            $oldAssignees = DB::table('task_assigns')
                ->leftJoin('users', 'users.id', '=', 'task_assigns.user_id')
                ->leftJoin('files', function($join) {
                    $join->on('files.object_id', '=', 'task_assigns.user_id');
                    $join->on('files.object_type', '=', DB::raw("'USER'"));
                })
                ->where('task_assigns.task_id', $task_id)
                ->select('task_assigns.user_id as user_id', 'users.full_name as user_name', 'files.file_name as picture')
                ->get();
            if ($oldAssignees) {
                $data['status'] = 1;
                $data['oldAssignees'] = $oldAssignees;
            }

            return $data;
        }
    }

    /**
     * reutrn all the users name to assign in task
     * @return get
     */
    public function getAllUser()
    {
        $data = ['status' => 0];
        $users = User::where(['is_active'=>1])->get();
        if ($users) {
            $data['status']       = 1;
            $data['users'] = $users;
        }

        return $data;
    }

    public function getRestAssignee(Request $request)
    {
        $data = ['old_member_status' => 0, 'project_member_status' => 0];
        $task_id       = $request->task_id;
        $project_id    = $request->project_id;
        $old_members = DB::table('task_assigns')
                            ->leftJoin('users', 'users.id', '=', 'task_assigns.user_id')
                            ->leftJoin('files', function($join) {
                                $join->on('files.object_id', '=', 'task_assigns.user_id');
                                $join->on('files.object_type', '=', DB::raw("'USER'")); })
                            ->where('task_id', $task_id)
                            ->select('task_assigns.user_id', 'users.full_name as user_name', 'files.file_name as picture')
                            ->get();
        $old_member_id = $old_members->pluck('user_id')->toArray();
        if ($project_id != '') {
            $project_member = DB::table('project_members')
                ->leftJoin('users', 'users.id', '=', 'project_members.user_id')
                ->where('users.is_active', 1)
                ->where('project_members.project_id', $project_id)
                ->whereNotIn('project_members.user_id', $old_member_id)
                ->select('project_members.user_id', 'users.full_name as user_name')
                ->get();
        } else {
            $project_member = DB::table('users')
                ->whereNotIn('users.id', $old_member_id)
                ->select('users.id as user_id', 'users.full_name as user_name')
                ->get();
        }
        if ($old_members) {
            $data['old_member_status'] = 1;
            $data['old_members'] = $old_members;
        }
        if ($project_member) {
            $data['project_member_status']         = 1;
            $data['project_member'] = $project_member;
        }
        return $data;
    }

    public function assignMember(Request $request)
    {
        $data = ['status' => 0];
        $task_id     = $request->task_id;
        $assignee_id = $request->assignee_id;
        if ($assignee_id != '') {
            $confirm         = DB::table('task_assigns')->insert(['task_id' => $task_id, 'user_id' => $assignee_id, 'assigned_by' => Auth::user()->id]);
            $old_member_list = DB::table('task_assigns')->where('task_id', $task_id)->pluck('user_id')->toArray();
            $assinee_details = DB::table('users')->where('id', $assignee_id)->first();
            // Mail to assignee
            if ($assinee_details) {
                $this->mailToAssignee($assinee_details,$task_id);
            }

            if (!empty($request->project_id)) {
                //Insert Activity
                $task_name  = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Added new task assignee of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');
            }
            if ($confirm) {
                $data['status']          = 1;
                $data['oldMembers']      = $old_member_list;
                $data['assinee_details'] = $assinee_details;
            }

        }

        return $data;
    }

    public function mailToAssignee($assignee, $task_id, $attachments = null)
    {
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $lang       = $preference['dflt_lang'];
        $email      = EmailTemplate::getAll()->where('template_id', 10)->where('language_short_name', $lang)->first();
        $task       = Task::find($task_id);
        $status     = DB::table('task_statuses')->where(['id'=>$task->task_status_id])->first();

        $subject = $email->subject;
        $message = $email->body;

        $task->start_date = isset($task->start_date) && !empty($task->start_date)? formatDate($task->start_date) : '-';
        $task->due_date = isset($task->due_date) && !empty($task->due_date)? formatDate($task->due_date) : '-';

        $subject = str_replace('{task_name}', $task->name, $subject);
        $message = str_replace('{company_name}', $preference['company_name'], $message);
        $message = str_replace('{assignee_name}', $assignee->full_name, $message);
        $message = str_replace('{task_name}', $task->name, $message);
        $message = str_replace('{task_details}', $task->description, $message);
        $message = str_replace('{start_date}', $task->start_date, $message);
        $message = str_replace('{due_date}', $task->due_date, $message);
        $message = str_replace('{priority}', $this->_priorities[$task->priority_id], $message);

        $checkList = ChecklistItem::where('task_id', $task_id)->get(['title']);
        $task_checklist = [];
        foreach ($checkList as $value) {
            $task_checklist[] = $value->title;
        }
        $message = str_replace('{task_checklist}', implode(', ', $task_checklist), $message);

        $message = str_replace('{ticket_status}', $status->name, $message);
        $message = str_replace('{task_link}', url('task/v/' . $task_id), $message);

        $job = $this->email->sendEmail($assignee->email, $subject, $message, $attachments, $preference['company_name']);
    }

    public function deleteAssignee(Request $request)
    {
        $data = ['status' => 0];
        $task_id = $request->task_id;
        $user_id = $request->user_id;
        if (!empty($task_id) && !empty($user_id)) {
            $confirm = DB::table('task_assigns')->where(['task_id' => $task_id, 'user_id' => $user_id])->delete();
            if (!empty($request->project_id)) {
                //Insert Activity
                $task_name  = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Remove task assignee of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');
            }
            if (!empty($confirm)) {
                $data['status'] = 1;
            }
        }

        return $data;
    }

    public function getAllTask($id)
    {
        $data = [];
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
          $data['menu'] = 'relationship';
          $data['sub_menu'] = 'users';
        } else {
            $data['menu']    = 'project';
        }

        $data['page_title'] = __('Project\'s Tasks');
        $user_id         = Auth::user()->id;
        $data['header']  = 'project';
        $data['navbar']  = 'task';
        $data['project'] = DB::table('projects')
                            ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
                            ->select('projects.id', 'projects.name', 'ps.name as status_name')
                            ->where('projects.id', $id)->first();
        if (empty($data['project'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        $tags                      = DB::table('tags')->pluck('name')->toArray();
        $data['tags']              = json_encode($tags);
        $data['taskStatus']        = TaskStatus::getAll()->pluck('name', 'id')->toArray();
        $taskList       = TaskStatus::getAll();
        $data['task_priority_all'] = DB::table('priorities')->select('id', 'name')->get();
        $data['from']        = isset($_GET['from'])    ? $_GET['from']     : null;
        $data['to']          = isset($_GET['to'])      ? $_GET['to']       : null;
        $data['allstatus']   = isset($_GET['status'])  ? $_GET['status']   : null;
        $data['allpriority'] = isset($_GET['priority'])? $_GET['priority'] : null;
        $data['allassignee'] = $allassignee = isset($_GET['assignee']) ? $_GET['assignee']  : $user_id;
        $projectMembers            = DB::table('project_members')->where('project_id', $id)->pluck('user_id')->toArray();
        if (!empty($projectMembers)) {
            $data['assignees']     = DB::table('users')->where('is_active', 1)
                                    ->whereIn('id', $projectMembers)
                                    ->get();
        } else {
            $data['assignees'] = null;
        }

        if (isset($_GET['reset_btn'])) {
            $data['allstatus']   = null;
            $data['allpriority'] = null;
            $data['allassignee'] = $allassignee = null;
        }
        if (isset($_GET['from'])) {
            $data['from'] = $_GET['from'];
        }
        if (isset($_GET['to'])) {
            $data['to'] = $_GET['to'];
        }

        $task_summary = (new Task())->getTaskSummary(['task_status_id' => $data['allstatus'], 'related_to_id' => $id, 'related_to_type' => "1", 'from' => $data['from'], 'to' => $data['to'], 'user_id' => $user_id, 'allassignee' => $allassignee])->get();
        $summary = [];
        $stack   = [];
        for ($i = 0; $i < count($taskList); $i++) {
            for ($j = 0; $j < count($task_summary); $j++) {
                if ($taskList[$i]->id == $task_summary[$j]->id) {
                    $summary[$i]['id']           = $task_summary[$j]->id;
                    $summary[$i]['name']         = $task_summary[$j]->name;
                    $summary[$i]['color']        = $task_summary[$j]->color;
                    $summary[$i]['total_status'] = $task_summary[$j]->total_status;
                    $stack[]                     = $task_summary[$j]->id;
                } else {
                    if (!in_array($taskList[$i]->id, $stack)) {
                        $summary[$i]['id']           = $taskList[$i]->id;
                        $summary[$i]['name']         = $taskList[$i]->name;
                        $summary[$i]['color']        = $taskList[$i]->color;
                        $summary[$i]['total_status'] = 0;
                    }
                }
            }
        }
        if (count($task_summary) === 0) {
            for ($i = 0; $i < count($taskList); $i++) {
                $summary[$i]['id']           = $taskList[$i]->id;
                $summary[$i]['name']         = $taskList[$i]->name;
                $summary[$i]['color']        = $taskList[$i]->color;
                $summary[$i]['total_status'] = 0;
            }
        }
        $data['summary'] = $summary;
        // Cunting summary

        // Counting summary end

        //Individual counting
        if ($allassignee != Auth::user()->id) {
            for ($i = 0; $i < count($taskList); $i++) {
                $result = DB::table('tasks')
                    ->where([ 'related_to_type' => 1, 'related_to_id' => $id ])
                    ->join('task_assigns',function($join) use ($user_id){
                        $join->on('task_assigns.task_id', 'tasks.id');
                        $join->where('task_assigns.user_id', $user_id);
                    })
                    ->join('task_statuses',function($join) use ($i){
                        $join->on('task_statuses.id', 'tasks.task_status_id');
                        $join->where('task_statuses.id', $i+1);
                    });
                if (!empty($id)) {
                    $result->where(['related_to_id' => $id, 'related_to_type' => 1]);
                }
                $assign_to_me[$i] =  $result->count();
            }
            $data['assign_to_me'] = $assign_to_me;
        }
        //Individual counting end

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $this->taskDataTable->with('row_per_page', $row_per_page)->with('project_id', $id)->render('admin.project.task.list', $data);
    }

    /**
     * Function to view the blade file for add task
     */
    public function addTask()
    {
        $data                = ['menu' => 'task', 'page_title' => __('Create Task')];
        $data['priorities']  = Priority::getAll();
        $data['assignees']   = DB::table('users')->where('is_active', 1)->get();
        $tags                = DB::table('tags')->pluck('name')->toArray();
        $data['tags']        = json_encode($tags);
        $data['date_format'] = Preference::getAll()->where('category', 'preference')->where('field', 'date_format_type')->first('value')->value;

        return view('admin.task.add', $data);
    }

    /**
     * Store the task in database
     * @param  Request $request
     * @return redirect
     */
    public function taskStore(Request $request)
    {
        $this->validate($request, [
                'task_name'     => 'required',
                'priority_id'   => 'required',
                'upload_File.*' => 'mimes:jpeg,png,gif,docx,xls,xlsx,pdf|max:5000',
            ],
            [
                'upload_File.*.mimes' => 'Only jpeg,png,gif,docx,xls,xlsx and pdf files are allowed',
                'upload_File.*.max'   => 'Max file size will be equal or less than 5 MB',
            ]
        );
        try {
            DB::beginTransaction();
            $data['name']           = stripBeforeSave($request->task_name);
            $data['description']    = $request->task_details;
            $data['priority_id']    = $request->priority_id;
            $data['start_date']     = $request->start_date ? DbDateFormat($request->start_date) : null;
            $data['due_date']       = $request->due_date ? DbDateFormat($request->due_date) : null;
            $data['created_at']     = date('Y-m-d H:i:s');
            $data['user_id']        = Auth::user()->id;
            $data['task_status_id'] = 1;

            $related_to_type = $request->related_to;
            $related_to_id = $request->relatedId;
            $data['related_to_id']       = $related_to_id;
            $data['related_to_type']     = $related_to_type;
            $data['hourly_rate']         = isset($request->hourly_rate) ? validateNumbers($request->hourly_rate) : 0;
            $data['milestone_id'] = isset($related_to_type) && $related_to_type == 1 ? $request->milestone_id : null;
            $insertId = DB::table('tasks')->insertGetId($data);
            if ($insertId) {
                // Inserting checklist items
                $CheklistItemsFromView = $request->allCheckListHiidenInput;
                if (!empty($CheklistItemsFromView)) {
                    foreach ($CheklistItemsFromView as $CheklistItem) {
                        $newChecklistItems  = new ChecklistItem;
                        $newChecklistItems->title       = $CheklistItem;
                        $newChecklistItems->task_id     = $insertId;
                        $newChecklistItems->checked_at  = null;
                        $newChecklistItems->save();
                    }
                }
            }

            // If has assignee
            if (isset($request->assignee)) {
                if ($related_to_type == '1') {
                //If it is a task related to a project
                    $assigns = DB::table('project_members')->where(['project_id'=> $related_to_id])
                            ->whereIn('user_id', $request->assignee)->get();
                    if ($assigns) {
                        foreach ($assigns as $assign) {
                            DB::table('task_assigns')->insert(['task_id' => $insertId, 'user_id' => $assign->user_id, 'assigned_by' => Auth::user()->id]);
                        }
                    }
                } else {
                    foreach ($request->assignee as $value) {
                        DB::table('task_assigns')->insert(['task_id' => $insertId, 'user_id' => $value, 'assigned_by' => Auth::user()->id]);
                    }
                }
            }

            if (!empty($request->tags)) {
                $oldTag    = DB::table('tags')->pluck('name', 'id')->toArray();
                $newTag    = explode(',', $request->tags);
                $newTagIds = [];
                $new       = [];
                if (!empty($oldTag)) {
                    foreach ($oldTag as $key => $value) {
                        if (in_array($value, $newTag)) {
                            $newTagIds[] = $key;
                        }
                    }
                    //Insert Old tag
                    if (!empty($newTagIds)) {
                        foreach ($newTagIds as $value) {
                            DB::table('tag_assigns')->insert(['tag_type' => 'task', 'tag_id' => $value, 'reference_id' => $insertId]);
                        }
                    }
                }
                //Insert New Tag
                foreach ($newTag as $key => $value) {
                    if (!in_array($value, $oldTag)) {
                        $new[] = $value;
                    }
                }
                if (!empty($new)) {
                    foreach ($new as $value) {
                        $lastInsertId = DB::table('tags')->insertGetId(['name' => stripBeforeSave($value)]);
                        if ($lastInsertId) {
                            $datas['tag_type']    = 'task';
                            $datas['tag_id']  = $lastInsertId;
                            $datas['reference_id'] = $insertId;
                            DB::table('tag_assigns')->insert($datas);
                        }
                    }
                }
            }

            if ($related_to_type == '1') {
                $project = DB::table('projects')->where('id', $related_to_id)->first();
                    if (isset($project->improvement_from_tasks) && $project->improvement_from_tasks == 1) {
                        $total_tasks = DB::table('tasks')
                                    ->select(DB::raw('count(*) as total_task'))
                                    ->where(['related_to_type'=> 1, 'related_to_id'=> $related_to_id])
                                    ->first();
                        $complete_tasks = DB::table('tasks')
                                       ->select(DB::raw('count(*) as complete_task'))
                                       ->where(['related_to_type'=> 1, 'related_to_id'=> $related_to_id, 'status'=> 5])
                                       ->first();
                        $completed = ($complete_tasks->complete_task/$total_tasks->total_task)*100;
                        DB::table('projects')->where(['id' => $related_to_id])->update(['improvement' => $completed]);
                    }
            }

            # region store files
                if (isset($insertId) && !empty($insertId)) {
                    if (!empty($request->attachments)) {
                        $path = createDirectory("public/uploads/tasks");
                        $fileIdList = (new File)->store($request->attachments, $path, 'Task', $insertId, ['isUploaded' => true, 'resize' => false, 'isOriginalNameRequired' => true]);
                    }
                }
            # end region

            // Send notification email to the assignees if has
            if ($request->assignee) {
                $assinee_details = DB::table('users')->whereIn('id', [$request->assignee, 1])->get();
            } else {
                $assinee_details = DB::table('users')->whereIn('id', [1])->get();
            }
            if ($assinee_details) {
                foreach ($assinee_details as $assignee_detail) {
                    $this->mailToAssignee($assignee_detail, $insertId, isset($fileIdList) ? $fileIdList : '');
                }
            }

            DB::commit();
            Session::flash('success', __('Successfully Saved'));
            return redirect('task/list');

        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Could not saved'));
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function taskEdit($id)
    {
        $data = ['menu' => 'task', 'relatedName' => '', 'page_title' => __('Edit Task')];
        $data['priorities']    = Priority::getAll();
        $data['task_assignee'] = DB::table('task_assigns')->where(['task_id'=>$id])->pluck('user_id')->toArray();
        $data['task_statuses'] = TaskStatus::getAll()->where('id', '!=', 6)->pluck('name', 'id')->toArray();
        $tags                  = DB::table('tags')->pluck('name')->toArray();
        $data['tags']          = json_encode($tags);

        $data['task']          = $task = $this->task->getTaskDetailsById($id);
        if (!empty($task)) {
            if (!empty($task->related_to_type)) {
                if ($task->related_to_type == 1 ) {
                    $data['relatedName'] = Project::where('id', $task->related_to_id)->first(['name'])->name;
                } else if ($task->related_to_type == 2 ) {
                    $data['relatedName'] = Customer::where('id', $task->related_to_id)->first(['name'])->name;
                } else {
                    $data['relatedName'] = Ticket::where('id', $task->related_to_id)->first(['subject'])->subject;
                }
            }

            $project_id = $task->related_to_type == '1' ? $task->related_to_id : 0;
            $data['milestones'] = DB::table('milestones')->where('project_id', $project_id)->get();
            $data['tags'] = TagAssign::with(['tag'])->where(['reference_id'=> $id, 'tag_type'=> 'task'])->get();
            $relatedCheckList        = ChecklistItem::where("task_id", $id)->get();
            $data['checklist_items'] =  $relatedCheckList;
            if (isset($task->project_id)) {
                $projectMembers = DB::table('project_members')->where(['project_id'=>$task->project_id])->pluck('user_id')->toArray();
                $data['assignees']     = User::whereIn('id', $projectMembers)->where(['is_active' => 1])->get();
            } else {
                $data['assignees']     = User::where(['is_active' => 1])->get();
            }
            return view('admin.task.edit', $data);
        } else {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
    }

    public function taskUpdate(Request $request)
    {
        $data = [];
        $this->validate($request, [
            'task_name'   => 'required',
            'priority_id' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $task_id                    = $request->task_id;
            $data['name']               = stripBeforeSave($request->task_name);
            $data['description']        = $request->task_details;
            $data['priority_id']        = $request->priority_id;
            $data['start_date']         = DbDateFormat($request->start_date);
            $data['due_date']           = $request->due_date ? DbDateFormat($request->due_date) : null;
            $data['task_status_id']     = $request->status_id;

            $related_to_type = $request->related_to;
            $related_to_id = $request->relatedId;
            $data['related_to_type'] = $related_to_type;
            $data['related_to_id']   = $related_to_id;

            $data['hourly_rate']  = isset($request->hourly_rate) ? validateNumbers($request->hourly_rate) : 0;
            $data['milestone_id'] = isset($related_to_type) && $related_to_type == 1 ? $request->milestone_id : null;

            DB::table('tasks')->where('id', $task_id)->update($data);
            // Update Tag
            $newTag = $request->tags;
            if (!empty($newTag)) {
                $newTag        = $request->tags;
                $oldTag        = DB::table('tags')->pluck('name', 'id')->toArray();
                $equalTagId    = [];
                $notEqualTag   = [];
                $notEqualTagId = [];
                $newArry       = [];
                foreach ($oldTag as $key => $value) {
                    // If tag exist in tags table then get it's id
                    if (in_array($value, $newTag)) {
                        $equalTagId[] = $key;
                    }
                }
                foreach ($newTag as $key => $value) {
                    if (!in_array($value, $oldTag)) {
                        $notEqualTag[] = $value;
                    }
                }
                if (!empty($notEqualTag)) {
                    foreach ($notEqualTag as $key => $value) {
                        $insertTagId = DB::table('tags')->insertGetId(['name' => stripBeforeSave($value)]);
                        if (!empty($insertTagId)) {
                            $notEqualTagId[] = $insertTagId;
                        }
                    }
                }

                $allTags     = array_merge($equalTagId, $notEqualTagId);
                // Fetch tag from tag_assigns tb
                $insertedTag = DB::table('tag_assigns')->where('reference_id', $task_id)->pluck('tag_id')->toArray();
                if (!empty($insertedTag)) {
                    foreach ($allTags as $key => $value) {
                        if (!in_array($value, $insertedTag)) {
                            DB::table('tag_assigns')->insert(['tag_type' => 'task', 'tag_id' => $value, 'reference_id' => $task_id]);
                        }
                    }

                    foreach ($insertedTag as $key => $value) {
                        if (!in_array($value, $allTags)) {
                            DB::table('tag_assigns')->where(array('reference_id' => $task_id, 'tag_id' => $value))->delete();
                        }
                    }
                } else {
                    foreach ($allTags as $key => $value) {
                        DB::table('tag_assigns')->insert(['tag_type' => 'task', 'tag_id' => $value, 'reference_id' => $task_id]);
                    }
                }
            } else {
                DB::table('tag_assigns')->where(['reference_id' => $task_id, 'tag_type' => 'task'])->delete();
            }

            // Update Project Progress
            if ($related_to_type == '1') {
                $project = DB::table('projects')->where('id', $related_to_id)->first();
                    if (isset($project->improvement_from_tasks) && $project->improvement_from_tasks == 1) {
                        $total_tasks = DB::table('tasks')
                                    ->select(DB::raw('count(*) as total_task'))
                                    ->where(['related_to_type'=> 1, 'related_to_id'=> $related_to_id])
                                    ->first();
                        $complete_tasks = DB::table('tasks')
                                       ->select(DB::raw('count(*) as complete_task'))
                                       ->where(['related_to_type'=> 1, 'related_to_id'=> $related_to_id, 'status' => 5])
                                       ->first();
                        $completed = ($complete_tasks->complete_task / $total_tasks->total_task) * 100;
                        DB::table('projects')->where(['id' => $related_to_id])->update(['improvement' => $completed]);
                    }
            }

            // Update assignees
            if (isset($request->assignee)) {
                $task_assignees = DB::table('task_assigns')->where(['task_id' => $request->task_id])->pluck('user_id')->toArray();
                $new_assignee = array_diff($request->assignee, $task_assignees);
                $delete_assignee = array_diff($task_assignees, $request->assignee);
                // For delete assignee
                if ($delete_assignee) {
                    foreach ($delete_assignee as $value) {
                        DB::table('task_assigns')->where(['user_id' => $value, 'task_id' => $request->task_id])->delete();
                    }
                }
                // For insert new assignees
                if ( $new_assignee) {
                    foreach ($new_assignee as $value) {
                       DB::table('task_assigns')->insert(['task_id' => $request->task_id, 'user_id' => $value, 'assigned_by' => Auth::user()->id]);
                    }
                }

                // Send notification email to the assignees if has
                if ($new_assignee) {
                    $assineeDetails = DB::table('users')->whereIn('id', $new_assignee)->get();
                    if ($assineeDetails) {
                        foreach ($assineeDetails as $assigneeDetail) {
                            $this->mailToAssignee($assigneeDetail, $task_id);
                        }
                    }
                }

            } else {
                // For delete all assignee if the input field is empty
                DB::table('task_assigns')->where(['task_id' => $request->task_id])->delete();
            }
            DB::commit();
            \Session::flash('success', __('Successfully updated'));
            return redirect('task/list');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Could not saved'));
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

    }

    public function taskDelete(Request $request)
    {
        try {
            $task_id = $request->task_id;
            \DB::beginTransaction();
            if (isset($task_id)) {
                $record = DB::table('tasks')->where('id', $task_id)->first();
                if ($record) {
                    $deleteTaskFiles = (new File)->deleteFiles('Task', $request->task_id, [], $path = 'public/uploads/tasks');
                    DB::table('task_timers')->where(['task_id' => $task_id])->delete();
                    DB::table('task_assigns')->where(['task_id' => $task_id])->delete();
                    DB::table('task_comments')->where(['task_id' => $task_id])->delete();
                    DB::table('checklist_items')->where(['task_id' => $task_id])->delete();
                    DB::table('tasks')->where('id', '=', $task_id)->delete();

                     if ($request->project_id != null) {
                        $project = DB::table('projects')->where('id', $request->project_id)->first();
                            if (isset($project->improvement_from_tasks) && $project->improvement_from_tasks == 1) {
                                $total_tasks = DB::table('tasks')
                                            ->select(DB::raw('count(*) as total_task'))
                                            ->where(['related_to_type' => 1, 'related_to_id' => $request->project_id])
                                            ->first();
                                if ($total_tasks->total_task > 0) {
                                   $complete_tasks = DB::table('tasks')
                                               ->select(DB::raw('count(*) as complete_task'))
                                               ->where(['related_to_type'=> 1, 'related_to_id' => $request->project_id, 'status' => 5])
                                               ->first();
                                    $completed = ($complete_tasks->complete_task/$total_tasks->total_task) * 100;
                                    DB::table('projects')->where(['id' => $request->project_id])->update(['improvement' => $completed]);
                                }
                        }
                    }
                }
            }
            \DB::commit();
            Session::flash('success', __('Deleted Successfully.'));
            return redirect()->back();
        } catch (Exception $e) {
            \DB::rollBack();
            Session::flash('success', __('Delete failed'));
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function customerStoreComment(Request $request)
    {
        $task_id = $request->task_id;
        $comment = $request->comment;
        $user_id = Auth::guard('customer')->user()->id;
        if (!empty($task_id)) {
            $comment_id = DB::table('task_comments')->insertGetId(['content' => $comment, 'task_id' => $task_id, 'customer_id' => $user_id]);

            if ($comment_id) {
                Session::flash('success', __('Successfully Saved'));
                return redirect()->back();
            }
        }
    }

    public function customerDeleteComment(Request $request)
    {
        $comment = TaskComment::find($request->id);
        $comment->delete();
        Session::flash('success', __('Deleted Successfully.'));
        return redirect()->back();
    }

    public function customerUpdateComment(Request $request)
    {
        $comment = TaskComment::find($request->id);
        $comment->content = $request->comment;
        $comment->task_id = $request->task_id;
        $comment->user_id = 0;
        $comment->customer_id = Auth::guard('customer')->user()->id;
        $comment->save();
        Session::flash('success', __('Successfully updated'));
        return redirect()->back();
    }

    public function editStartDate(Request $request)
    {
        $result = DB::table('tasks')
                    ->where('id', $request->task_id)
                    ->update(['start_date' => DbDateFormat($request->date)]);
        return $result;
    }

    public function editDueDate(Request $request)
    {
        $result = DB::table('tasks')
                    ->where('id', $request->task_id)
                    ->update(['due_date' => DbDateFormat($request->date)]);
        return $result;
    }

    public function projectTimeSheet($id)
    {
        $data = [];
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu']       = 'project';
        }

        $data['page_title'] = __('Project\'s Task Timesheet');
        $data['header']     = 'project';
        $data['navbar']     = 'timesheet';
        $data['project_id'] = $id;
        $data['project']    = DB::table('projects')
            ->where('projects.id', $id)
            ->select('projects.*', 'dm.name as customer_name', 'ps.name as status_name')
            ->leftJoin('customers as dm', 'dm.id', '=', 'projects.customer_id')
            ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
            ->first();

        if (empty($data['project'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }

        $timesheetDetails = (new TaskTimer())->getTaskTime(['from' => null, 'to' => null, 'assinee_id' => null, 'project_id' => $id, 'running' => null])->select('task_timers.start_time', 'task_timers.end_time')->get();

        $totalTime = 0;
        foreach ($timesheetDetails as $timesheetDetail) {
            if (!empty($timesheetDetail->end_time)) {
                $diff = ($timesheetDetail->end_time > $timesheetDetail->start_time) ? ($timesheetDetail->end_time - $timesheetDetail->start_time) : null;
            } else {
                $timesheetDetail->start_time = (int) $timesheetDetail->start_time;
                $diff = (time() > $timesheetDetail->start_time) ?  time() - $timesheetDetail->start_time : null;
            }
            $totalTime = $totalTime + $diff;
        }

        $data['hours']    = floor($totalTime / 3600) > 0 ? floor($totalTime / 3600) : 0;
        $data['minutes']  = floor(($totalTime / 60) % 60) > 0 ? floor(($totalTime / 60) % 60) : 0;
        $data['seconds']  = $totalTime % 60;

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        return $this->projectTimesheetDataTable->with('row_per_page', $row_per_page)->with('project_id', $id)->render('admin.project.timeSheet.list', $data);
    }

    public function deleteProjectTimer(Request $request, $id)
    {
        if (isset($id)) {
            DB::table('task_timers')->where(['id' => $id])->delete();
            Session::flash('success', __('Deleted Successfully.'));
            return redirect()->intended('project/tasks/timesheet/' . $request->project_id);
        }
    }

    public function tasks_pdf()
    {
        $data = [];
        $data['from']     = $from     = $_GET['from'];
        $data['to']       = $to       = $_GET['to'];
        $data['project']  = $project  = $_GET['project'];
        $data['assignee'] = $assignee = $_GET['assignee'];
        $data['status']   = $status   = $_GET['status'];
        $data['projectName'] = !empty($project) ? Project::where('id', $project)->first(['name']) : null ;
        $data['assignees'] = !empty($assignee) ? User::where('id', $assignee)->first(['full_name']) : null ;
        $data['taskStatus'] = !empty($status) ? TaskStatus::getAll()->where('id', $assignee)->first() : null ;
        $data['tasks']      = $this->task->getAllTaskForDT($from, $to, $status, $project, $assignee)->orderBy('start_date', 'desc')->get();
        foreach ($data['tasks'] as $task) {
            $assigne = (new Task())->taskAssignsList($task->id)->pluck('user_name');
            $list = "";
            if (!empty($assigne)) {
                foreach ($assigne as $counter => $assign) {
                    $list .= $assign;
                    if ($counter < count($assigne) - 1) {
                        $list .= ", ";
                    }
                }
            }
            $task->assignee = $list;
        }
        if ($from && $to) {
          $data['date_range'] =  formatDate($from) . " ". __('To') . " " . formatDate($to);
        } else {
         $data['date_range'] = __('No date selected');
        }
        $data['company_logo'] = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'task_list_pdf' . time() . '.pdf', 'admin.task.list_pdf', view('admin.task.list_pdf', $data), 'pdf', 'domPdf');
    }

    public function tasks_csv()
    {
        return Excel::download(new projectTaskExport(), 'task_list' . time() . '.csv');
    }

    public function customerFileStore(Request $request)
    {
        $data = ['status' => 'fail'];
        if ($request->hasFile('file')) {
            $file         = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $nameonly     = preg_replace('/\..+$/', '', $file->getClientOriginalName());
            $uniqueName   = strtolower($nameonly . '_' . time() . '.' . $file->getClientOriginalExtension());
            $file_extn    = strtolower($file->getClientOriginalExtension());
            $uploadPath   = public_path('uploads/taskFile');
            $file->move($uploadPath, $uniqueName);

            $data['user_id']            = 0;
            $data['customer_id']        = Auth::guard('customer')->user()->id;
            $data['task_id']            = $request->task_id;
            $data['file_name']          = $uniqueName;
            $data['original_file_name'] = $originalName;
            $data['file_type']          = $file_extn;
            $file_id                    = DB::table('files')->insertGetId($data);
        }

        if ($file_id) {
            $data['file_id']    = $file_id;
            $data['customer_name'] = Auth::guard('customer')->user()->first_name.' '.Auth::guard('customer')->user()->last_name;
            $data['created_at'] = timeZoneformatDate(date('Y-m-d H:i:s')) . ' ' . timeZonegetTime(date('Y-m-d H:i:s'));
            $data['status']     = 'success';
        }

        return $data;
    }


    public function customerFileDelete(Request $request)
    {
        $data = ['message' => "fail"];
        if ($request->file_id) {
            $file = DB::table('files')->where(['id' => $request->file_id])->first();
            if (!empty($file)) {
                @unlink(public_path() . '/uploads/taskFile/' . $file->file_name);
                $confirm = DB::table('files')->where(['id' => $request->file_id])->delete();
            }
        }
        if ($confirm) {
            $data['status'] = 'success';
        }

        return $data;
    }

    public function uploadEventAttachments(Request $request, $save = false)
    {
        $files = $request->attachment;
        if (!empty($files)) {
            $user_id = Auth::user()->id;
            $fileDirectory = createDirectory("public/uploads/tasks");
            $extension = $files->getClientOriginalExtension();
            if ($files->getError() <> 0 || !is_uploaded_file($_FILES['attachment']['tmp_name'])) {
                $this->_returnJSON(false, __('Error in uploading file. Please try again..'));
            }
            if (($files->getSize() / 1024) > 10240) {
                $this->_returnJSON(false, __('Maximum File Size 10M.'));
            }

            $validator = Validator::make($request->all(), []);

            $validator->after(function ($validator) use ($request) {
                $files  = $request->file('attachment');
                if (empty($files)) {
                    return true;
                }

                if (checkFileValidationOne($files->getClientOriginalExtension()) == false) {
                    $validator->errors()->add('upload_File', __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf'));
                }
            });

            if ($validator->fails()) {
                $this->_returnJSON(false, __('Invalid file type.'));
            }

            $uploadedFiles = (new File)->store([$request->attachment], $fileDirectory, "Task", $request->task_id, ['isOriginalNameRequired' => true]);
            if ($uploadedFiles) {
                $task = Task::find($request->task_id);
                //Insert Activity
                if ($task->related_to_type == 1) {
                    (new Activity)->store('Project', $task->related_to_id, 'user', Auth::user()->id, __('Add new file'));
                }
                $uploadedFile = File::find($uploadedFiles[0]);
                $attachmentPath = $fileDirectory ."/". $uploadedFile->file_name;
                $this->_returnJSON(true, array('task_id' => $request->task_id, 'message' => __('Attachment uploaded successfully'), 'attachment_id' => $uploadedFile->id, 'attachment_name' => $uploadedFile->file_name, 'attachment_icon' => getFileIcon($uploadedFile->file_name), 'attachment_path' => $attachmentPath, 'attachment_original_name' => $uploadedFile->original_file_name, 'attachment_extension' => $extension, 'created_at' => $uploadedFile->created_at));
            }
        }

        $this->_returnJSON(false, __('Error in uploading photo. Please try again.'));
    }

    public function deleteEventAttachment(Request $request)
    {
        $id = $request->id;
        return (new File)->deleteFiles("Task", $request->task_id, ['ids' => [$id]], "public/uploads/tasks");
    }

    public function downloadAttachment($id)
    {
        $file = DB::table('transaction_files')->where('id', '=', $id)->first();
        $dir_path = "public/uploads/transactionFiles";
        $doc_path = $dir_path ."/". $file->file;
        $path = url('/') . $file->file;

        if (file_exists($doc_path)) {

             // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            switch (strtolower(pathinfo($file->file, PATHINFO_EXTENSION))) {
                case 'pdf':
                    $mime = 'application/pdf';
                    break;
                case 'zip':
                    $mime = 'application/zip';
                    break;
                case 'jpeg':
                case 'jpg':
                    $mime = 'image/jpg';
                    break;
                default:
                    $mime = 'application/force-download';
            }
            // required
            header('Pragma: public');
            // no cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($doc_path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Content-Transfer-Encoding: binary');
            // provide file size*/
            header('Content-Length: ' . filesize($doc_path));
            header('Connection: close');
            ob_clean();
            flush();
            // push it out
            readfile($doc_path);
            exit();
        } else {
            echo __("This file does not exist.");
            exit();
        }
    }

    /**
     * Return JSON Encoded output for ajax call
     *
     * @param boolean $result
     * @param mixed $data
     * @param boolean $return if set the json encoded string will be returned
     * @return string
     */
    protected function _returnJSON($result, $data = null, $options = array())
    {
        $options = array_merge(array('return' => false, 'render' => false, 'layout' => false), $options);
        if (is_string($data)) {
            $data = array('errorMessage' => $data);
        }
        // render the current action and add to html data key
        if (!empty($options['render'])) {
            if (!empty($options['layout'])) {
                $this->layout = $options['layout'];
            }
            if (empty($options['view'])) {
                $options['view'] = $this->action;
            }
            $data['html'] = $this->render($options['view']);
            if (is_object($data['html'])) {
                if (($options['layout'] == 'paginated-html')) {
                    $data['html'] = json_decode($data['html']->body(), true);
                } else {
                    $data['html'] = $data['html']->body();
                }
            }
        }
        if (!empty($options['sqlLogs'])) {
            $data['_sqlLogs'] = $options['sqlLogs'];
        }
        if ($result !== false) {
            if (!empty($options['return'])) {
                return json_encode(array('result' => true, 'serverTime' => date('Y-m-d H:i:s'), 'data' => $data));
            } else {
                if (!empty($options['jsonResponse'])) {
                    // send josn response without exiting the code
                    $this->autoRender = false;
                    $this->response->body(json_encode(array('result' => true, 'serverTime' => date('Y-m-d H:i:s'), 'data' => $data)));
                    return $this->response;
                } else {
                    // @deprecated, should be removed in the future, this is currently default way
                    echo json_encode(array('result' => true, 'serverTime' => date('Y-m-d H:i:s'), 'data' => $data));
                    exit;
                }
            }
        } else {
            $errorMessage = false;
            if (!empty($data['errorMessage'])) {
                $errorMessage = $data['errorMessage'];
            }
            if (!empty($options['return'])) {
                return json_encode(array('result' => false, 'serverTime' => date('Y-m-d H:i:s'), 'errorMessage' => $errorMessage, 'data' => $data));
            } else {
                if (!empty($options['jsonResponse'])) {
                    // send josn response without exiting the code
                    $this->autoRender = false;
                    $this->response->body(json_encode(array('result' => false, 'serverTime' => date('Y-m-d H:i:s'), 'errorMessage' => $errorMessage, 'data' => $data)));
                    return $this->response;
                } else {
                    // @deprecated, should be removed in the future, this is currently default way
                    echo json_encode(array('result' => false, 'serverTime' => date('Y-m-d H:i:s'), 'errorMessage' => $errorMessage, 'data' => $data));
                    exit;
                }
            }
        }
    }

    public function projectTaskPdf (Request $request)
    {
        $from     = isset($request->from) ? $request->from : null ;
        $to       = isset($request->to) ? $request->to : null ;
        $project  = isset($request->project) ? $request->project : null ;
        $assignee = isset($request->assignee) ? $request->assignee : null ;
        $status   = isset($request->status) ? $request->status : null ;
        $priority = isset($request->priority) ? $request->priority : null ;

        $data['taskStatus']   = TaskStatus::where('id', $status)->first(['name']);
        $data['taskPriority'] = Priority::where('id', $priority)->first(['name']);
        $data['assignees']    = User::where(['is_active' => 1, 'id' => $assignee])->first(['full_name']);
        $data['projectName']  = Project::where('id', $project)->first(['name']);

        $data['tasks']      = $this->task->getAllTaskForDT($from, $to, $status, $project, $assignee, $priority)->get();
        foreach ($data['tasks'] as $task) {
            $assigne = (new Task())->taskAssignsList($task->id)->pluck('user_name');
            $list = "";
            if (!empty($assigne)) {
                foreach ($assigne as $counter => $assign) {
                    $list .= $assign;
                    if ($counter < count($assigne) - 1) {
                        $list .= ", ";
                    }
                }
            }
            $task->assignee = $list;
        }

        $data['date_range']   = (!empty($from) && !empty($to)) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        $data['company_logo'] = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'project_task_list_pdf' . time() . '.pdf', 'admin.project.task.list_pdf', view('admin.project.task.list_pdf', $data), 'pdf', 'domPdf');
    }

    public function projectTaskCsv()
    {
        return Excel::download(new projectTaskExport(), 'project_task_list' . time() . '.csv');
    }

    public function allFiles(Request $request)
    {
        $data['status'] = 0;
        if (!empty($request->task_id)) {
            $data['files'] = (new File)->getFiles('Task', $request->task_id);
            if (!empty($data['files'])) {
                $data['filePath'] = "public/uploads/tasks";
                foreach ($data['files'] as $key => $value) {
                    $value->icon = getFileIcon($value->file_name);
                    $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
                }
                $data['status'] = 1;
            }
        }
        return $data;
    }

    public function relatedSearch(Request $request)
    {
        $data['status_no']  = 0;
        $data['message']    = __('No Item Found');
        $data['result']      = array();
        $return_arr         = [];
        if (!empty($request->relatedTo)) {
            if ($request->relatedTo == 1) {
                $result = Project::where('name', 'LIKE', '%' . $request->search . '%')->where('project_status_id', '!=', 6)->get(['id', 'name', 'charge_type'])->take(10);
            } else if ($request->relatedTo == 2) {
                $result = Customer::where('name', 'LIKE', '%' . $request->search . '%')->where('is_active', 1)->get(['id', 'name'])->take(10);
            } else {
                $result = Ticket::where('subject', 'LIKE', '%' . $request->search . '%')->get(['id', 'subject'])->take(10);
            }


            if (!empty($result)) {
                $data['status_no'] = 1;
                $data['message'] = __('Item Found');
                $i = 0;
                foreach ($result as $key => $value) {
                  $return_arr[$i]['id'] = $value->id;
                  $return_arr[$i]['name'] = isset($value->name) && !empty($value->name) ? $value->name : $value->subject;
                  $return_arr[$i]['chargeType'] = isset($value->charge_type) && !empty($value->charge_type) ? $value->charge_type : '';
                  $i++;
                }
            }
            $data['result'] = $return_arr;
        }
        echo json_encode($data);
        exit;
    }
    /**
     * When a task status will be changed then a mail sent to admin & all assignees
     * @param  int $task_id
     * @param  collection $previousStatus
     * @param  collection $newStatus
     * @return void
     */
    public function changeMailStatus($task_id, $previousStatus, $newStatus)
    {
        if (!empty($task_id)) {
            $preference = Preference::getAll()->pluck('value', 'field')->toArray();
            $task       = Task::find($task_id);
            $taskAssignee = TaskAssign::where('task_id', $task_id)->pluck('user_id')->toArray();
            $allAssigneeName = User::whereIn('id', $taskAssignee)->get();
            $assigneeName = [];
            foreach ($allAssigneeName as $data) {
                $assigneeName[] = $data->full_name;
            }
            $teamMembers = User::whereIn('id', $taskAssignee)->get();
            $lang       = $preference['dflt_lang'];
            $email      = EmailTemplate::getAll()->where('template_id', 20)->where('language_short_name', $lang)->first();
            if ($teamMembers) {
                foreach ($teamMembers as $data) {
                    $subject = $email->subject;
                    $message = $email->body;
                    $startDate = isset($task->start_date) && !empty($task->start_date)? formatDate($task->start_date) : '-';
                    $dueDate = isset($task->due_date) && !empty($task->due_date) ? formatDate($task->due_date) : '-';
                    $subject = str_replace('{task_id}', $task->id, $subject);
                    $subject = str_replace('{changed_by}', Auth::user()->full_name, $subject);
                    $message = str_replace('{company_name}', $preference['company_name'], $message);
                    $message = str_replace('{assignee_name}', $data->full_name, $message);
                    $message = str_replace('{task_id}', $task->id, $message);
                    $message = str_replace('{task_prev_status}', $previousStatus->taskStatus->name, $message);
                    $message = str_replace('{task_new_status}', isset($newStatus->taskStatus->name) ? $newStatus->taskStatus->name : '', $message);
                    $message = str_replace('{task_name}', $task->name, $message);
                    $message = str_replace('{changed_by}', Auth::user()->full_name, $message);
                    $message = str_replace('{all_assignee_name}', implode(', ', $assigneeName), $message);
                    $message = str_replace('{start_date}', $startDate, $message);
                    $message = str_replace('{due_date}', $dueDate, $message);
                    $message = str_replace('{priority}', isset($task->priority->name) ? $task->priority->name : '', $message);
                    $message = str_replace('{task_new_status}', isset($newStatus->taskStatus->name) ? $newStatus->taskStatus->name : '', $message);
                    $message = str_replace('{task_link}', url('task/v/' . $task_id), $message);
                    $this->email->sendEmail($data->email, $subject, $message, null, $preference['company_name']);
                }
            }

        }
    }

    /**
     * To clean checklist input
     * @param  Request $request [description]
     * @return string          [description]
     */
    public function cleanInput(Request $request)
    {
        $data['status'] = 1;
        $data['value'] = $request->todoItem;

        return $data;
    }
}
