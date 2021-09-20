<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectDataTable;
use App\Exports\projectListExport;
use App\Http\Controllers\EmailController;
use App\Http\Start\Helpers;
use App\Models\File;
use App\Models\Preference;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\ProjectSetting;
use App\Models\Task;
use App\Models\TaskTimer;
use App\Models\SaleOrder;
use App\Models\Milestone;
use App\Models\ChecklistItem;
use App\Models\User;
use App\Models\Activity;
use App\Models\TagAssign;
use App\Models\Tag;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Cache;
use PDF;
use Validator;
use Carbon;
use App\Exports\TimesheetExport;


class ProjectController extends Controller
{
  public function __construct(Request $request,Project $project, ProjectDataTable $projectDataTable,EmailController $email, Task $task)
  {
    $this->request          = $request;
    $this->project          = $project;
    $this->projectDataTable = $projectDataTable;
    $this->email            = $email;
    $this->task             = $task;
  }

  public function index()
  {
    $data['menu']   = 'project';
    $data['header'] = 'project';
    $data['page_title'] = __('Projects');
    $data['status'] = DB::table('project_statuses')->select('id','name')->get();

    $data['from']         = $from = isset($_GET['from']) ? $_GET['from'] : null;
    $data['to']           = $to = isset($_GET['to']) ? $_GET['to'] : null;
    $data['allstatus']    = $allstatus = isset($_GET['status']) ? $_GET['status'] : '';
    $data['project_type'] = (isset($_GET['project_type']) && $_GET['project_type'] != '') ? $_GET['project_type'] : ['customer', 'product', 'in_house'];

    $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first('value')->value;

    return $this->projectDataTable->with('row_per_page', $row_per_page)->render('admin.project.list', $data);
  }

  public function create()
  {
    $data['menu']   = 'project';
    $data['header'] = 'project';
    $data['page_title'] = __('Create Project');
    $data['departments']   = DB::table('departments')->first();
    $data['priorities']    = DB::table('priorities')->get();
    $data['assignees']     = DB::table('users')->where('is_active', 1)->get();
    $data['ticketStatus']  = DB::table('ticket_statuses')->get();
    $data['customers']     = DB::table('customers')->where('is_active', 1)->get();
    $data['projectStatus'] = DB::table('project_statuses')->get();
    $tags                  = DB::table('tags')->select('name')->get();

    $newArry = [];
    foreach ($tags as $key => $value) {
      $newArry[] = $value->name;
    }
    $data['tags']  = json_encode($newArry);
    $data['users'] = User::where('is_active',1)->get();

    return view('admin.project.add', $data);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'project_name' => 'required',
      'charge_type'  =>'required',
      'status'     =>'required',
      'members'      =>'required',
      'start_date'   =>'required'
    ]);
    try {
        DB::beginTransaction();
          $projectMembers                 = $request->members;
          $data['name']                   = stripBeforeSave($request->project_name);
          $data['detail']                 = $request->project_details;
          $data['project_type']           = $request->project_type;
          $data['customer_id']            = (($request->project_type) == 'customer' ? $request->customer_id : NULL);
          $data['user_id']                = $user_id = Auth::user()->id;
          $data['project_status_id']      = $request->status;
          $data['charge_type']            = $request->charge_type;
          $data['begin_date']             = DbDateFormat($request->start_date);
          $data['due_date']               = $request->end_date ? DbDateFormat($request->end_date) : NULL ;
          $data['improvement']            = 0;
          $data['improvement_from_task']  = 0;
          $data['cost']                   = $request->charge_type == 1 ? validateNumbers($request->total_cost) : 0;
          $data['per_hour_project_scale'] = validateNumbers($request->rate_per_hour);
          $data['estimated_hours']        = validateNumbers($request->project_hours);

          $insertId                       = DB::table('projects')->insertGetId($data);

          //Insert Activity
          $projectCreator = User::select('full_name')->where(['id' => $user_id])->first();
          (new Activity)->store('Project', $insertId, 'user', Auth::user()->id, __('A new project has been created by') . ' ' . '<strong>' . htmlentities($projectCreator->full_name) .'</strong>');
          $projectDetails = $this->project->getAllProject($insertId);

          if (!empty($projectMembers)) {
            foreach ($projectMembers as $key => $value) {
              $projectData['project_id'] = $insertId;
              $projectData['user_id']    = $value;
              DB::table('project_members')->insert($projectData);

              //Insert Activity
              $userName = User::select('full_name')->where(['id' => $value])->first();
              (new Activity)->store('Project', $insertId, 'user', Auth::user()->id, __('New team member :? has been added.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
            }
          }

          if (!empty($request->tags)) {
            $oldTag = DB::table('tags')->pluck('name','id')->toArray();
            $newTag = $request->tags;

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
                  DB::table('tag_assigns')->insert([
                    'tag_type'     => 'project',
                    'tag_id'       =>$value,
                    'reference_id' => $insertId
                  ]);
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
                $lastInsertId = DB::table('tags')->insertGetId([ 'name' => stripBeforeSave($value) ]);
                if ($lastInsertId) {
                  $datas['tag_type']    = 'project';
                  $datas['tag_id']  = $lastInsertId;
                  $datas['reference_id'] = $insertId;
                  DB::table('tag_assigns')->insert($datas);
                }
              }
            }
          }

          //Insert Project Setting
          if (isset($request->setting)) {
            foreach ($request->setting as $key => $value) {
              $setting                = new ProjectSetting;
              $setting->project_id    = $insertId;
              $setting->setting_label = $key;
              $setting->setting_value = $value;
              $setting->save();
            }
          }
          ini_set('max_execution_time', 600);
          $emailResponseAssigne = $this->mailToAssigne($projectDetails, $oldMembers = array());
          if ($emailResponseAssigne['status'] == false) {
               \DB::rollBack();
              return redirect()->back()->withInput()->withErrors(['fail' => $emailResponseAssigne['message']]);
           }
          if ($request->checkbox) {
            if ($request->project_type == 'customer') {
              $emailResponse = $this->mailToCustomer($projectDetails, $request->customer_id);
              if ($emailResponse['status'] == false) {
                 \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
            }
          }

          DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      return redirect('project/details/'.$insertId)->withErrors(__('Failed To Add The Project'));
    }
    if ($insertId) {
      Cache::forget('gb-projects');
      Session::flash('success', __('Successfully Saved'));
      return redirect()->intended('project/details/'.$insertId);
    }
  }


  public function editProject($id)
  {
    $data['menu'] = 'project';
    $data['header'] = 'project';
    $data['page_title'] = __('Edit Project');

    $data['departments']  = DB::table('departments')->first();
    $data['priorities']   = DB::table('priorities')->get();
    $data['assignees']    = DB::table('users')->where('is_active', 1)->get();
    $data['ticketStatus'] = DB::table('ticket_statuses')->get();
    $data['customers']    = DB::table('customers')->where('is_active', 1)->get();
    $data['projectStatus'] = DB::table('project_statuses')->get();
    $data['permissions']  = ProjectSetting::where('project_id',$id)->pluck('setting_label')->toArray();

    $data['users']  = User::where('is_active',1)->get();
    $data['project']= DB::table('projects')
        ->where('projects.id',$id)
        ->select('projects.*','dm.name as customer_name', 'dm.id as customer_id','ps.name as status_name')
        ->leftJoin('customers as dm','dm.id','=','projects.customer_id')
        ->leftJoin('project_statuses as ps','ps.id','=', 'projects.project_status_id')
        ->first();
    if (empty($data['project'])) {
      \Session::flash('fail', __('The data you are trying to access is not found.'));
      return redirect()->back();
    }
    $data['projectMember'] = DB::table('project_members')
        ->where('project_members.project_id', $id)
        ->leftJoin('users','users.id','=','project_members.user_id')
        ->pluck('project_members.user_id')->toArray();
    $data['oldMembers'] = json_encode($data['projectMember']);

    $data['tags'] = DB::table('tag_assigns')
                  ->leftJoin('tags','tags.id','=','tag_assigns.tag_id')
                  ->where(['tag_assigns.reference_id'=> $id, 'tag_type'=> 'project'])
                  ->get();
    $data['taskExist'] =  Task::where(['related_to_id'=> $id, 'related_to_type' => 1])->exists();

    return view('admin.project.edit',$data);
  }

  public function updateProject(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'project_name' => 'required',
      'status'     =>'required',
      'start_date'   =>'required'
    ]);
    if ($request->project_type == 'customer') {
       Validator::make($request->all(), [
          'customer_id'  => 'required',
        ]);
    }

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    \DB::beginTransaction();
    $projectMembers                 = $request->members;
    $newTag                         = $request->tags;
    $project_id                     = $request->project_id;
    $data['name']                   = $request->project_name;
    $data['detail']                 = $request->project_details;
    $data['project_type']           = $request->project_type;
    $data['customer_id']            = $request->project_type == 'customer' ? $request->customer_id : NULL;
    $data['user_id']                = $user_id =  Auth::user()->id;

    $data['project_status_id']      = $request->status;
    $data['charge_type']            = !empty($request->charge_type) ? $request->charge_type : $request->ChargeType;
    $data['begin_date']             = DbDateFormat($request->start_date);
    $data['due_date']               = $request->end_date ? DbDateFormat($request->end_date) : NULL ;
    $data['improvement']            = 0;

    $data['improvement_from_task']  = 0;
    $data['cost']                   = $request->charge_type == 1 ? validateNumbers($request->total_cost) : 0;
    $data['per_hour_project_scale'] = validateNumbers($request->rate_per_hour);
    $data['estimated_hours']        = validateNumbers($request->project_hours);

    DB::table('projects')->where('id', $project_id)->update($data);

    //Update Project Member
    $oldMembers = DB::table('project_members')->where('project_id',$project_id)->pluck('user_id')->toArray();
    //If member seleted
     if (!empty($projectMembers)) {
       $members    = $projectMembers;
       if (!empty($oldMembers)) {
       foreach ($oldMembers as $value) {
         if (!in_array($value, $members)) {
            DB::table('project_members')
            ->where([
              'project_id'=> $project_id,
              'user_id'=>$value
            ])->delete();
            //Delete Task Assignee
            DB::table('task_assigns')
            ->leftJoin('tasks','tasks.id','=','task_assigns.task_id')
            ->leftJoin('projects','projects.id','=','tasks.related_to_id')
            ->where([
              'tasks.related_to_type' => '1',
              'projects.id'           => $project_id,
              'task_assigns.user_id'  => $value
            ])->delete();

            //Insert Activity
            $userName = User::select('full_name')->where(['id' => $value])->first();
            (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('Team member :? has been removed.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
         }
       }

       foreach ($members as $value) {
            if (!in_array($value, $oldMembers)) {
                DB::table('project_members')
                ->insert([
                  'project_id' => $project_id,
                  'user_id'    => $value
                ]);

               //Insert Activity
               $userName = User::select('full_name')->where(['id' => $value])->first();
               (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('New team member :? has been added.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
              }
          }
     } else {
      //If no old member
      foreach ($members as $value) {
            DB::table('project_members')
            ->insert([
              'project_id' => $project_id,
              'user_id'    => $value
            ]);
            //Insert Activity
            (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('New team member has been Added'));
          }
      }
    } else {
      //If no member select then delate all member of this project
      DB::table('project_members')->where('project_id', $project_id)->delete();

      //Delete Task Assignee
      DB::table('task_assignes')
      ->leftJoin('tasks','tasks.id','=','task_assignes.task_id')
      ->leftJoin('projects','projects.id','=','tasks.related_to_id')
      ->where([
        'tasks.related_to_type' =>'1',
        'projects.id'           => $project_id
      ])->delete();

      //Insert Activity
      (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('All Team Member From Project has been removed.'));
    }

     //Update Tag
   if (!empty($newTag)) {
     $newTag = $request->tags;
     $oldTag = DB::table('tags')->pluck('name','id')->toarray();
     $equalTagId    = [];
     $notEqualTag   = [];
     $notEqualTagId = [];
     $newArry       = [];
       foreach ($oldTag as $key => $value) {
               if (in_array($value, $newTag)) {
                  $equalTagId[] = $key;//If tag exist in tags table then get it's id
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
         $allTags = array_merge($equalTagId,$notEqualTagId);
         $insertedTag = DB::table('tag_assigns')->where('reference_id',$project_id)->pluck('tag_id')->toarray();//Fetch tag from tag_assigns tb
         if (!empty($insertedTag)) {
              foreach ($allTags as $key => $value) {
                 if (!in_array($value, $insertedTag)) {
                   DB::table('tag_assigns')
                   ->insert([
                    'tag_type'     => 'project',
                    'tag_id'       => $value,
                    'reference_id' => $project_id
                  ]);
                 }
               }
               foreach ($insertedTag as $key => $value) {
                 if (!in_array($value, $allTags)) {
                   DB::table('tag_assigns')
                   ->where([
                    'reference_id' => $project_id,
                    'tag_id'       =>$value,
                    'tag_type'     =>'project'
                  ])->delete();
                 }
               }
         } else {
           foreach ($allTags as $key => $value) {
             DB::table('tag_assigns')->insert([
              'tag_type'     => 'project',
              'tag_id'       => $value,
              'reference_id' => $project_id
             ]);
           }
         }
    } else {
     DB::table('tag_assigns')
     ->where([
      'reference_id' => $project_id,
      'tag_type'     => 'project'
    ])->delete();
    }
    //Update project progress
    $project = DB::table('projects')->where('id',$project_id)->first();
        if (isset($project->improvement_from_tasks) && $project->improvement_from_tasks == 1) {
            $total_tasks = DB::table('tasks')
                         ->select(DB::raw('count(*) as total_task'))
                         ->where(['related_to_type' => 1, 'related_to_id'=> $project_id])
                         ->first();
            if ($total_tasks->total_task > 0) {
               $complete_tasks = DB::table('tasks')
                           ->select(DB::raw('count(*) as complete_task'))
                           ->where([
                            'related_to_type'=> 1,
                            'related_to_id'=> $project_id,
                            'task_status_id'=> 5
                          ])->first();
               $completed = ($complete_tasks->complete_task / $total_tasks->total_task) * 100;
               DB::table('projects')->where(['id' => $project_id])->update(['improvement' => $completed]);
            }
        }
    //Update project permission
    if ($request->project_type == 'customer' && $request->setting) {
      $permissions = ProjectSetting::where(['project_id'=> $project_id])->pluck('setting_label');
      $permission = $permissions->toarray();
      $keyy[] = '';

      //For insert permission
      foreach ($request->setting as $key => $value) {
        $keyy[] = $key;
        $projectSetting = new ProjectSetting();
        if (!in_array($key, $permission)) {
          $projectSetting->project_id = $project_id;
          $projectSetting->setting_label = $key;
          $projectSetting->setting_value = 'on';
          $projectSetting->save();
        }
      }

      //For delete permission
      foreach ($permission as $key => $value) {
        if (!in_array($value, $keyy)) {
          $projectSetting = ProjectSetting::where([
            'project_id'    => $project_id,
            'setting_label' =>$value
          ])->delete();
        }
      }
    }

    if ($request->project_type != 'customer') {
      $projectSetting = ProjectSetting::where(['project_id'=> $project_id]);
      $projectSetting->delete();
    }

    //Send project update email
      $projectDetails = $this->project->getAllProject($project_id);
      ini_set('max_execution_time', 600);
      $this->mailToAssigne($projectDetails, $oldMembers);
      if ($request->project_type == 'customer' && !empty($request->customer_id)) {
        if ($request->previous_customer_id != $request->customer_id) {
          $emailResponse = $this->mailToCustomer($projectDetails, $request->customer_id);
          if ($emailResponse['status'] == false) {
               \DB::rollBack();
              return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
           }
        }
      }
      \DB::commit();
      Cache::forget('gb-projects');
     \Session::flash('success', __('Successfully updated'));
    return redirect()->intended('project/details/'.$project_id);
  }

  public function projectDestroy(Request $request)
  {
    $project_id = $request->project_id;
    try {
      DB::beginTransaction();
      if (!empty($project_id)) {
        Project::where('id', $project_id)->update(['project_status_id' => 6]);
        Cache::forget('gb-projects');
        DB::commit();
        \Session::flash('success', __('Deleted Successfully.'));
        return redirect()->back();
      }
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect('project/list')->withErrors(__('Failed To Delete the Project'));
    }
  }

  public function details($id)
  {
      $data['menu'] = 'project';
      $data['page_title'] = __('Project Details');
      $data['header']  = 'project';
      $data['navbar']  = 'details';
      $data['totalTask'] = DB::table('tasks')
        ->where('tasks.related_to_type', 1)
        ->where('tasks.related_to_id', $id)
        ->count();
      $data['completedTask'] = DB::table('tasks')
        ->where('tasks.related_to_type', 1)
        ->where('related_to_id', $id)->where('task_status_id', 4)
        ->count();
      $data['todayActivities'] = Activity::where([
                                  'object_type' => 'Project',
                                  'object_id' => $id
                                ])
                                ->orderBy('id', 'desc')
                                ->whereDate('created_at', Carbon::today())->get();
      $data['thisWeekActivities'] = Activity::where([
                                  'object_type' => 'Project',
                                  'object_id' => $id
                                ])
                                ->orderBy('id', 'desc')
                                ->where('created_at', '>=', Carbon::today()->subDays(7))->get();
      $data['project'] = DB::table('projects')
        ->where('projects.id',$id)
        ->select('projects.*','dm.first_name','dm.last_name','ps.name as status_name', 'users.full_name as userName')
        ->leftJoin('customers as dm','dm.id','=','projects.customer_id')
        ->leftJoin('users','users.id','=','projects.user_id')
        ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
        ->first();
      if (empty($data['project'])) {
        \Session::flash('fail', __('The data you are trying to access is not found.'));
        return redirect()->back();
      }
      if ($data['project']->due_date == null) {
        $datediff = time() - strtotime($data['project']->begin_date);
        $data['dayCount'] = abs(intval(round($datediff / (60 * 60 * 24))));
        $data['dayTitle'] = __('Days passed');
      } else {
        $datediff = time() - strtotime($data['project']->due_date);
        $data['dayCount'] = abs(intval(round($datediff / (60 * 60 * 24))));
        $data['dayTitle'] = __('Days left');
      }

      $data['projectMembers'] = DB::table('project_members')
          ->leftJoin('users','users.id','=','project_members.user_id')
          ->where('project_members.project_id', $id)
          ->get();
      $data['users']= User::where('is_active', 1)->get();
      $data['tags'] = DB::table('tag_assigns')
                    ->leftJoin('tags','tags.id','=','tag_assigns.tag_id')
                    ->select(DB::raw("(GROUP_CONCAT(tags.name SEPARATOR ',')) as `all_tags`"))
                    ->groupBy('tag_assigns.reference_id')
                    ->where([
                      'tag_assigns.reference_id'=> $id,
                      'tag_assigns.tag_type'=> 'project'
                    ])->first();
      $newArry = [];
      foreach ($data['projectMembers'] as $key => $value) {
        $icon = (new File)->getFiles('USER', $value->user_id);
        if (count($icon) != 0) {
          $value->imageIcon = $icon[0]->file_name;
        }
        $newArry[] = $value->id;
      }
      $data['oldMembers'] = json_encode($newArry);
      $data['total_logged_time'] = $this->project->projectLoggedTime($id);
      $array = [];
      $data['amounts'] = $amounts = (new SaleOrder)->getMoneyStatus(['project_id' => $id]);
      $allCurrency = [];
      $overdueCurrency = [];
      foreach ($amounts['amounts'] as $amount) {
        if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
          $allCurrency[] =  $amount->currency->symbol;
        }
      }
      foreach ($amounts['overDue'] as $amount) {
        if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
          $overdueCurrency[] =  $amount->currency->symbol;
        }
      }
      $data['allCurrency'] = array_diff($allCurrency, $overdueCurrency);

    return view('admin.project.details', $data);
  }

  public function removeProjectmember(Request $request)
  {
      if ($request->m_id && $request->p_id) {
           $data1 = DB::table('project_members')->where([ 'project_id' => $request->p_id, 'user_id' => $request->m_id])->first();
         if (!empty($data1)) {
            $confirm = DB::table('project_members')->where([ 'project_id' => $request->p_id, 'user_id' => $request->m_id])->delete();

            //Delete Task Assignee
              DB::table('task_assigns')
              ->leftJoin('tasks','tasks.id','=','task_assigns.task_id')
              ->leftJoin('projects','projects.id','=','tasks.related_to_id')
              ->where(['tasks.related_to_type'=>'1','projects.id'=> $request->p_id, 'task_assigns.user_id'=> $request->m_id])
              ->delete();

            //Insert Activity
            $userName = User::where(['id' => $request->m_id])->select('full_name')->first();
            (new Activity)->store('Project', $request->p_id, 'user', Auth::user()->id, __('Team member :? has been removed.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
            if ($confirm) {
              $data['status'] = '1';
            }else{
              $data['status'] = '0';
            }
            return $data;
          }
      }
  }

  public function updateProjectmember(Request $request)
  {
    $members    = $request->members;
    $project_id = $request->project_id;
    $user = User::select('full_name');
    if (!empty($members)) {
      $oldMembers = DB::table('project_members')->where('project_id',$project_id)->pluck('user_id')->toarray();
      if (!empty($oldMembers)) {
        foreach ($oldMembers as $value) {
          if (!in_array($value, $members)) {
            DB::table('project_members')->where(array('project_id'=> $project_id,'user_id'=>$value))->delete();

           //Delete Task Assignee
            DB::table('task_assigns')
            ->leftJoin('tasks','tasks.id','=','task_assigns.task_id')
            ->leftJoin('projects','projects.id','=','tasks.related_to_id')
            ->where(['tasks.related_to_type'=>'1','projects.id'=> $project_id, 'task_assigns.user_id'=> $value])
            ->delete();
            //Insert Activity
            $userName = User::select('full_name')->where(['id' => $value])->first();
            (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('Team member :? has been removed.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
          }
        }
        foreach ($members as $value) {
          if (!in_array($value, $oldMembers)) {
            DB::table('project_members')->insert(['project_id'=> $project_id, 'user_id'=> $value]);
            //Insert Activity
            $userName = User::select('full_name')->where(['id' => $value])->first();
            (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('New team member :? has been added.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
          }
        }
      } else {
        foreach ($members as $value) {
          DB::table('project_members')->insert(['project_id'=> $project_id, 'user_id'=> $value]);
          //Insert Activity
          $userName = User::select('full_name')->where(['id' => $value])->first();
          (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('New team member :? has been added.', ['?' => '<strong>'. htmlentities($userName->full_name) .'</strong>']));
         }
        }
      }else{
         DB::table('project_members')->where('project_id', $project_id)->delete();

          //Delete Task Assignee
        DB::table('task_assigns')
        ->leftJoin('tasks','tasks.id','=','task_assigns.task_id')
        ->leftJoin('projects','projects.id','=','tasks.related_to_id')
        ->where(['tasks.related_to_type'=>'1','projects.id'=> $project_id])
        ->delete();

        //Insert Activity
        (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('All team member from project has been removed.'));
      }
      \Session::flash('success', __('Successfully updated'));
      return redirect()->back();
  }

  public function project_pdf()
  {
    $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
    $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
    $data['status'] = $status = isset($_GET['status']) ? $_GET['status'] : null;
    $data['statusSelected'] = !empty($status) ? ProjectStatus::find($status) : null;
    if (isset($_GET['project_type']) && $_GET['project_type'] == "customer,product,in_house")  {
      $data['project_type'] = __("Customer, Product, In house");
      $project_type = $_GET['project_type'];
    } else {
      $data['project_type'] = $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : null;
    }
    if ($from && $to) {
      $data['date_range'] =  formatDate($from) . __('To') . formatDate($to);
    } else {
      $data['date_range'] = __('No date selected');
    }
    $result = [];
    $finalRes = [];
    $projects = $this->project->getAllProjectPdf($from, $to, $status, $project_type)->get()->toArray();
    if (!empty($projects)) {
      foreach($projects as $val) {
          $result[$val->project_id][] = $val;
      }
    }

    if (!empty($result)) {
      foreach ($result as $key => $value) {
        $completedTask = 0;
        $totalTask = 0;
        if (!empty($value)) {
          foreach ($value as $k => $v) {
            if ($v->task_status_id == 5) {
              $completedTask += 1;
            }
            if (isset($v->task_id) && !empty($v->task_id)) {
              $totalTask += 1;
            }
            $finalRes[$key]['project_id'] = $v->project_id;
            $finalRes[$key]['name'] = $v->name;
            $finalRes[$key]['project_type'] = $v->project_type;
            $finalRes[$key]['first_name'] = $v->first_name;
            $finalRes[$key]['last_name'] = $v->last_name;
            $finalRes[$key]['begin_date'] = $v->begin_date;
            $finalRes[$key]['due_date'] = $v->due_date;
            $finalRes[$key]['charge_type'] = $v->charge_type;
            $finalRes[$key]['status_name'] = $v->status_name;
            $finalRes[$key]['totalTask'] = $totalTask;
            $finalRes[$key]['completedTask'] = $completedTask;
          }
        }
      }
    }
    arsort($finalRes);
    $data['project'] = $finalRes;
    return printPDF($data, 'project_list_pdf' . time() . '.pdf', 'admin.project.list_pdf', view('admin.project.list_pdf', $data), 'pdf', 'domPdf');
  }

  public function project_csv()
  {
    return Excel::download(new projectListExport(), 'Project_details'.time().'.csv');
  }

  public function mailToCustomer($projectDetails,$customer_id)
  {
    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    $lang       = $preference['dflt_lang'];
    $email      = DB::table('email_templates')->where(['template_id' => 12, 'language_short_name' => $lang])->select('subject', 'body')->first();
    $subject    = $email->subject;
    $message    = $email->body;

    $customerData = DB::table('customers')->where('id',$customer_id)->first();
    if (isset($customerData->email) && !empty($customerData->email) && filter_var($customerData->email, FILTER_VALIDATE_EMAIL)) {
      $project_link = '<a href="'. url('customer-project/detail/' . $projectDetails->project_id) . '">' . $projectDetails->name . '</a>';
      $message = str_replace('{project_name}', $projectDetails->name, $message);
      $message = str_replace('{customer_name}', $projectDetails->first_name.' '.$projectDetails->last_name, $message);
      $message = str_replace('{start_date}', $projectDetails->begin_date, $message);
      $message = str_replace('{status}', $projectDetails->status_name, $message);
      $message = str_replace('{details}', $project_link, $message);

      $message = str_replace('{company_name}', $preference['company_name'], $message);

      return $this->email->sendEmail($customerData->email, $subject, $message, null, $preference['company_name']);
    }

    return ['status' => false, 'message' => __(':? can not be sent, please try agian.', ['?' => __('Email')])];
  }


  public function mailToAssigne($projectDetails, $oldMembers)
  {
    $members    = DB::table('project_members')->where('project_id',$projectDetails->project_id)->get();
    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    $lang       = $preference['dflt_lang'];
    $email      = DB::table('email_templates')->where(['template_id' => 13, 'language_short_name' => $lang])->select('subject', 'body')->first();

    $subject = $email->subject;
    $message = $email->body;

    $project_link = '<a href="'. url('project/details/'.$projectDetails->project_id).'">'.$projectDetails->name.'</a>';
    $message = str_replace('{project_name}', $projectDetails->name, $message);
    $message = str_replace('{customer_name}', $projectDetails->first_name.' '.$projectDetails->last_name, $message);
    $message = str_replace('{start_date}', $projectDetails->begin_date, $message);
    $message = str_replace('{status}', $projectDetails->status_name, $message);
    $message = str_replace('{details}', $project_link, $message);
    $message = str_replace('{company_name}', $preference['company_name'], $message);

    foreach ($members as $value) {
      if (Auth::user()->id != $value->user_id) {
        if (!in_array($value->user_id, $oldMembers)) {
          $assigne = DB::table('users')->where('id', $value->user_id)->first();
          $message = str_replace('{assignee}', $assigne->full_name, $message);
          $response = $this->email->sendEmail($assigne->email, $subject, $message, null, $preference['company_name']);
          if ($response['status'] == false) {
            return ['status' => false, 'message' => __(':? can not be sent, please try agian.', ['?' => __('Email')])];
          }
        }
      }
    }
    return ['status' => true, 'message' => __(':? sent successfully.', ['?' => __('Email')])];
  }

  public function projectTimeSheetPdf()
  {
    $taskTimer = new TaskTimer();
    $data['timerDetails'] = $sheetTimerDetails = $taskTimer->getTaskTime(['project_id' => $_GET['project']])->select('task_timers.*', 'users.id as user_id', 'users.full_name', 'tasks.name as name', 'projects.name as project_name')->get();
    $data['projectData'] = Project::find($_GET['project']);
    $data['range'] = null ;
    return printPDF($data, 'project_time_sheet_list_' . time() . '.pdf', 'admin.timeSheet.list_pdf', view('admin.timeSheet.list_pdf', $data), 'pdf', 'domPdf');
  }

  public function projectTimeSheetCsv()
  {
    return Excel::download(new TimesheetExport(), 'time_sheet'. time() .'.csv');
  }

  public function addTask($id)
  {
    $data = ['menu' => 'project', 'header' => 'project', 'navbar' => 'task', 'page_title' => __('Project Add Task')];
    $data['project'] = DB::table('projects')
                        ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
                        ->select('projects.id', 'projects.name', 'ps.name as status_name', 'projects.charge_type')
                        ->where('projects.id', $id)->first();
    if (empty($data['project'])) {
      \Session::flash('fail', __('The data you are trying to access is not found.'));
      return redirect()->back();
    }
    $data['milestones'] = Milestone::where('project_id', $id)->get();
    $data['projects']   = DB::table('projects')->select('id', 'name')->get();
    $data['customers']  = DB::table('customers')->select('id', 'first_name', 'last_name')->get();
    $data['tickets']    = DB::table('tickets')->select('id', 'subject')->get();
    $data['priorities'] = DB::table('priorities')->get();
    $projectMembers     = DB::table('project_members')->where('project_id', $id)->pluck('user_id')->toArray();
    if (!empty($projectMembers)) {
        $data['assignees'] = DB::table('users')->where('is_active', 1)
                                ->whereIn('id', $projectMembers)
                                ->get();
    } else {
        $data['assignees'] = null;
    }

    $tags               = DB::table('tags')->pluck('name')->toArray();
    $data['tags']       = json_encode($tags);

    return view('admin.project.task.add', $data);
  }

  public function taskStore(Request $request)
  {
    $data = [];
    $validator = Validator::make($request->all(), [
      'task_name'       => 'required',
      'related_to'     => 'required',
      'priority_id'   => 'required',
      'upload_File.*' => 'max:5000'
    ]);

    $validator->after(function ($validator) use ($request) {
      $files  = $request->file('upload_File');
      if (empty($files)) {
        return true;
      }

      foreach ($files as $key => $file) {
        if (checkFileValidationOne($file->getClientOriginalExtension()) == false) {
          // return validator with error by file input name
          $validator->errors()->add('upload_File', __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf'));
        }
      }
    });

    if ($validator->fails()) {
      return redirect('project/task/add/'.$request->project_id)->withErrors($validator)->withInput();
    }

    $data['name']           = $request->task_name;
    $data['description']    = $request->task_details;
    $data['priority_id']    = $request->priority_id;
    $data['start_date']     = DbDateFormat($request->start_date);
    $data['due_date']       = $request->due_date ? DbDateFormat($request->due_date) : null;
    $data['created_at']     =  date('Y-m-d H:i:s');
    $data['user_id']        = Auth::user()->id;
    $data['task_status_id'] = 1;
    $recurring_type_id      = $request->repeted_on;
    $custom_recurring       = $request->custom_repet;

    $related_to_type = $request->related_to;
    if ($related_to_type == '1') {
        $related_to_id = $request->project_id;
    } elseif ($related_to_type == '2') {
        $related_to_id = $request->customer_id;
    } elseif ($related_to_type == '3') {
        $related_to_id = $request->ticket_id;
    }
    $data['related_to_id']   = $related_to_id;
    $data['related_to_type'] = $related_to_type;
    $data['hourly_rate']  = validateNumbers($request->hourly_rate);
    $data['milestone_id'] = $request->milestone_id;

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
    if (isset($request->assignee) && !empty($request->assignee)) {
      foreach ($request->assignee as $value) {
        DB::table('task_assigns')->insert([
          'task_id' => $insertId,
          'user_id' => $value,
          'assigned_by' => Auth::user()->id
        ]);
      }
    }

    if (! empty($request->tags)) {
        $oldTag    = DB::table('tags')->pluck('name', 'id')->toArray();
        $newTag    = $request->tags;
        $newTagIds = [];
        $new       = [];
        if (! empty($oldTag)){
            foreach ($oldTag as $key => $value) {
                if (in_array($value, $newTag)) {
                    $newTagIds[] = $key;
                }
            }

            if (! empty($newTagIds)) {
                foreach ($newTagIds as $value) {
                    DB::table('tag_assigns')->insert([
                        'tag_type' => 'task',
                        'tag_id' => $value,
                        'reference_id' => $insertId
                    ]);
                }
            }
        }
        //Insert New Tag
        foreach ($newTag as $key => $value) {
            if (!in_array($value, $oldTag)) {
                $new[] = $value;
            }
        }
        if (! empty($new)) {
            foreach ($new as $value) {
                $lastInsertId = DB::table('tags')->insertGetId(['name' => $value]);
                if ($lastInsertId) {
                    $datas['tag_type']    = 'task';
                    $datas['tag_id']  = $lastInsertId;
                    $datas['reference_id'] = $insertId;
                    DB::table('tag_assigns')->insert($datas);
                }
            }
        }
    }

    //Update Project Progress
    if ($related_to_type == '1') {
        $project = DB::table('projects')->where('id', $request->project_id)->first();
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
                        'status' => 5
                    ])->first();
        $completed = ($complete_tasks->complete_task / $total_tasks->total_task) * 100;
        DB::table('projects')->where(['id' => $request->project_id])->update(['improvement' => $completed]);
      }
    }

    #region store files
    if (isset($insertId) && !empty($insertId)) {
      if (!empty($request->attachments)) {
        $path = createDirectory("public/uploads/tasks");
        $replyFiles = (new File)->store($request->attachments, $path, 'Task', $insertId, ['isUploaded' => true, 'resize' => false]);
      }
    }
    #end region

    if ($insertId) {
        Session::flash('success', __('Successfully Saved'));
        if ($related_to_type == 1) {
          return redirect()->intended('project/tasks/' . $request->project_id);
        } else {
          return redirect()->intended('task/list');
        }
    }
  }

  public function taskEdit($id)
  {
    if (!isset($_GET['task_id']) || empty($_GET['task_id'])) {
      \Session::flash('fail', __('The data you are trying to access is not found.'));
      return redirect()->back();
    }
    $data = ['menu' => 'project', 'header' => 'project', 'navbar' => 'task', 'page_title' => __('Project Edit Task')];
    $data['menu'] = 'project';

    $data['projects']    = DB::table('projects')->select('id', 'name')->get();
    $data['customers']   = DB::table('customers')->select('id', 'first_name', 'last_name')->get();
    $data['tickets']     = DB::table('tickets')->select('id', 'subject')->get();
    $data['priorities']  = DB::table('priorities')->get();
    $data['task_assignee'] = DB::table('task_assigns')->where(['task_id'=> $_GET['task_id'] ])->pluck('user_id')->toArray();
    $data['task_statuses'] = DB::table('task_statuses')->select('id', 'name')->get();
    $data['task'] = $task = $this->task->getTaskDetailsById($_GET['task_id']);
    if (empty($task)) {
      \Session::flash('fail', __('The data you are trying to access is not found.'));
      return redirect()->back();
    }
    $projectMembers = DB::table('project_members')->where(['project_id'=>$data['task']->project_id])->pluck('user_id')->toArray();
    $data['assignees']     = User::whereIn('id', $projectMembers)->where(['is_active' => 1])->get();
    $data['project'] = DB::table('projects')
                      ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
                      ->select('projects.id','projects.name','ps.name as status_name', 'projects.charge_type')
                      ->where('projects.id', $data['task']->project_id)->first();

    $data['tags'] = TagAssign::with(['tag'])->where(['reference_id'=> $_GET['task_id'], 'tag_type'=> 'task'])->get();
    $relatedCheckList        = ChecklistItem::where("task_id", $_GET['task_id'])->get();
    $data['checklist_items'] =  $relatedCheckList;

    $project_id = $task->related_to_type == '1' ? $task->related_to_id : 0;
    $data['milestones'] = DB::table('milestones')->where('project_id', $project_id)->get();

    return view('admin.project.task.edit', $data);
  }

  public function taskUpdate(Request $request)
  {
    $data = [];
    $this->validate($request, [
        'task_name'   => 'required',
        'start_date'  => 'required',
        'priority_id' => 'required',
    ]);
    $task_id             = $request->task_id;
    $data['name']        = $request->task_name;
    $data['description'] = $request->task_details;
    $data['priority_id']    = $request->priority_id;
    $data['start_date']  = DbDateFormat($request->start_date);
    $data['due_date']    = $request->due_date ? DbDateFormat($request->due_date) : null;
    $data['user_id']  = Auth::user()->id;
    $data['task_status_id']      = $request->status_id;
    $recurring_type_id   = $request->repeted_on;
    $custom_recurring    = $request->custom_repet;

    $related_to_type = $request->related_to;
    if ($related_to_type == '1') {
        $data['related_to_id']   = $request->project_id;
        $data['related_to_type'] = $related_to_type;
        $data['hourly_rate']     = null;
        $data['milestone_id']    = $request->milestone_id;
    } elseif ($related_to_type == '2') {
        $data['related_to_id']   = $request->customer_id;
        $data['related_to_type'] = $related_to_type;
        $data['hourly_rate']     = $request->hourly_rate;
        $data['milestone_id']    = null;
    } elseif ($related_to_type == '3') {
        $data['related_to_id']   = $request->ticket_id;
        $data['related_to_type'] = $related_to_type;
        $data['hourly_rate']     = $request->hourly_rate;
        $data['milestone_id']    = null;
    }


    DB::table('tasks')->where('id', $task_id)->update($data);
    //Update Tag
    $newTag = $request->tags;
    if (! empty($newTag)) {
        $newTag        = $request->tags;
        $oldTag        = DB::table('tags')->pluck('name', 'id')->toArray();
        $equalTagId    = [];
        $notEqualTag   = [];
        $notEqualTagId = [];
        $newArry       = [];
        foreach ($oldTag as $key => $value) {
            if (in_array($value, $newTag)) {
                $equalTagId[] = $key; //If tag exist in tags table then get it's id
            }
        }

        foreach ($newTag as $key => $value) {
            if (! in_array($value, $oldTag)) {
                $notEqualTag[] = $value;
            }
        }

        if (! empty($notEqualTag)) {
            foreach ($notEqualTag as $key => $value) {
                $insertTagId = DB::table('tags')->insertGetId(['name' => $value]);
                if (! empty($insertTagId)) {
                    $notEqualTagId[] = $insertTagId;
                }
            }
        }

        $allTags     = array_merge($equalTagId, $notEqualTagId);
        $insertedTag = DB::table('tag_assigns')->where('reference_id', $task_id)->pluck('tag_id')->toArray(); //Fetch tag from tags_in tb
        if (! empty($insertedTag)) {
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
        $project = DB::table('projects')->where('id', $request->project_id)->first();

            if (isset($project->improvement_from_tasks) && $project->improvement_from_tasks == 1) {
                $total_tasks = DB::table('tasks')
                            ->select(DB::raw('count(*) as total_task'))
                            ->where(['related_to_type' => 1, 'related_to_id' => $request->project_id])
                            ->first();
                $complete_tasks = DB::table('tasks')
                               ->select(DB::raw('count(*) as complete_task'))
                               ->where(['related_to_type' => 1, 'related_to_id' => $request->project_id, 'task_status_id' => 5])
                               ->first();
                $completed = ($complete_tasks->complete_task/$total_tasks->total_task)*100;
                DB::table('projects')->where(['id' => $request->project_id])->update(['improvement' => $completed]);
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
    } else {
        // For delete all assignee if the input field is empty
        DB::table('task_assigns')->where(['task_id' => $request->task_id])->delete();
    }

    \Session::flash('success', __('Successfully updated'));
    return redirect()->intended('project/tasks/' . $request->project_id);
  }


}
