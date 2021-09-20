<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Start\Helpers;
use Auth;
use App\Http\Controllers\EmailController;
use App\Models\Task;
use App\Models\Activity;
use App\Models\TaskAssign;
use App\Models\User;
use App\Models\Preference;
use App\Models\EmailTemplate;

class CommentController extends Controller
{
	public function __construct(Task $task, EmailController $email) {
        $this->task = $task;
        $this->email = $email;
    }

    public function store(Request $request) {
        $data = [];
        $task_id = $request->task_id;
        $comment = $request->comment;
        $user_id = Auth::user()->id;
        if (!empty($task_id) && !empty($comment)) {
            $comment_id = DB::table('task_comments')->insertGetId(['content' => $comment, 'task_id' => $task_id, 'user_id' => $user_id, 'created_at' => date('Y-m-d H:i:s')]);
            if (!empty($request->project_id)) {
                // Insert Activity
                $task_name = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Added a comment of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');

            }

            if ($comment_id) {
                
                $lastdata                    = $this->task->getCommentById($comment_id);
                $data['status']              = 1;
                $data['comment_id']          = $comment_id;
                $data['task_id']             = $task_id;
                $data['user_name']           = $lastdata->user_name;
                $data['created_at']          = timeZoneformatDate($lastdata->created_at) . ' ' . timeZonegetTime($lastdata->created_at);
                $data['content']             = $lastdata->content;
                $data['is_user']             = $lastdata->is_user;
                $data['delete_task_comment'] = Helpers::has_permission(Auth::user()->id, 'delete_task_comment');
                $data['edit_task_comment']   = Helpers::has_permission(Auth::user()->id, 'edit_task_comment');
                $response = $this->mailToAssignee($task_id, $comment);
                if ($response['status'] == false) {
                    $data['status'] = false;
                    $data['message'] = $response['message'];
                }
            }
        } else {
            $data['status'] = false;
            $data['message'] = __('Please Leave a Comment');
        }
        return $data;
    }

    public function update(Request $request) {
        $data = ['status' => 0];
        $task_id    = $request->comment_task_id;
        $comment_id = $request->comment_id;
        $message    = $request->update_comment;
        if (!empty($task_id) && !empty($message)) {
            $confirm = DB::table('task_comments')->where(['id' => $comment_id, 'task_id' => $task_id])->update(['content' => $message, 'user_id' => Auth::user()->id]);
            if (!empty($request->project_id)) {
                // Insert Activity
                $task_name = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Update comment of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');
            }

            if($confirm){
                $data['status']  = 1;
            }
        } else {
            $data['status'] = false;
            $data['message'] = __('Please Leave a Comment');
        }

        return $data;
    }

    public function delete(Request $request) {
        $data = ['status' => 0];
        $id      = $request->comment_id;
        $task_id = $request->task_id;
        if (!empty($id)) {
            $confirm = DB::table('task_comments')->where('id', $id)->delete();
            if (!empty($request->project_id)) {
                // Insert Activity
                $task_name = DB::table('tasks')->where('id', $task_id)->select('name')->first();
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Delete comment of task ').'<strong>'. htmlentities($task_name->name) .'</strong>');
            }
            if ($confirm) {
                $data['status'] = 1;
            }
        }
        
        return $data;
    }

    /**
     * When a new task comment will be created then a mail sent to admin & all assignees
     * @param  int $task_id
     * @param  string $comment
     * @return void
     */
    
    public function mailToAssignee($task_id, $comment) {
        if (!empty($task_id)) {
            $preference = Preference::getAll()->pluck('value', 'field')->toArray();
            $task       = Task::find($task_id);
            $taskAssignee = TaskAssign::where('task_id', $task_id)->pluck('user_id')->toArray();
            $teamMembers = User::whereIn('id', $taskAssignee)->get();
            $lang       = $preference['dflt_lang'];

            $email      = EmailTemplate::getAll()->where('template_id', 21)->where('language_short_name', $lang)->first();
            if ($teamMembers) {
                foreach ($teamMembers as $data) {
                    $subject = $email->subject;
                    $message = $email->body;
                    $startDate = isset($task->start_date) && !empty($task->start_date)? formatDate($task->start_date) : '-';
                    $dueDate = isset($task->due_date) && !empty($task->due_date) ? formatDate($task->due_date) : '-';
                    $subject = str_replace('{commented_by}', Auth::user()->full_name, $subject);
                    $subject = str_replace('{task_id}', $task->id, $subject);
                    $subject = str_replace('{task_name}', $task->name, $subject);

                    $message = str_replace('{company_name}', $preference['company_name'], $message);
                    $message = str_replace('{assignee_name}', $data->full_name, $message);
                    $message = str_replace('{task_id}', $task->id, $message);
                    $message = str_replace('{task_name}', $task->name, $message);
                    $message = str_replace('{commented_by}', Auth::user()->full_name, $message);
                    $message = str_replace('{start_date}', $startDate, $message);
                    $message = str_replace('{due_date}', $dueDate, $message);
                    $message = str_replace('{comment}', $comment, $message);
                    $message = str_replace('{task_link}', url('task/v/' . $task_id), $message);
                    $emailResponse = $this->email->sendEmail($data->email, $subject, $message, null, $preference['company_name']);

                    if ($emailResponse['status'] == false) {
                        return $emailResponse;
                    } 

                }
            }
            
        }
        return ['status' => true, 'message' => __(':? sent successfully.', ['?' => __('Email')])];
    }
}


                