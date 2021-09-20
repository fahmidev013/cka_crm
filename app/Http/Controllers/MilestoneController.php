<?php

namespace App\Http\Controllers;

use App\DataTables\MileStoneDataTable;
use App\DataTables\MilestoneTaskDataTable;
use App\Http\Start\Helpers;
use App\Models\Preference;
use App\Models\Project;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Exports\MileStoneExport;
use PDF;
use Excel;

class MilestoneController extends Controller
{
    
    public function __construct(Request $request, MileStoneDataTable $mileStoneDataTable, Project $project, MilestoneTaskDataTable $mileStoneTaskDataTable)
    {
        $this->request = $request;
        $this->project = $project;
        $this->mileStoneDataTable = $mileStoneDataTable;
        $this->mileStoneTaskDataTable = $mileStoneTaskDataTable;
    }
    public function index($id)
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

        $data['page_title'] = __('Project\'s Milestones');
        $data['header'] = 'project';
        $data['navbar'] = 'milestone';
        $data['project_id'] = $id;
        $data['project'] = DB::table('projects')
                          ->where('projects.id', $id)
                          ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
                          ->select('projects.id','projects.name','ps.name as status_name')->first();
        if (empty($data['project'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }              
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first()->value;
        return $this->mileStoneDataTable->with('row_per_page', $row_per_page)->with('project_id', $id)->render('admin.project.milestone.list', $data);
        
    }

    public function add($id)
    {
        $data['menu'] = 'project';
        $data['header'] = 'project';
        $data['page_title'] = __('Create Milestone for project');
        $data['navbar'] = 'milestone';
        $data['project'] = DB::table('projects')
                        ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
                        ->select('projects.id','projects.name','ps.name as status_name')
                        ->where('projects.id',$id)->first();
        if (empty($data['project'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }

        return view('admin.project.milestone.add', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'due_date' => 'required',
        ]);
        $data['project_id']  = $request->project_id;
        $data['name']        = $request->name;
        $data['description'] = $request->description;
        $data['due_date']    = DbDateFormat($request->due_date);

        $insertId = DB::table('milestones')->insertGetId($data);

        // Insert Activity
        (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Added new milestone'));
        if ($insertId) {
            Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('project/milestones/' . $request->project_id);
        }

    }

    public function edit($id)
    {
        $data['menu'] = 'project';
        $data['header'] = 'project';
        $data['page_title'] = __('Edit Milestone for project');
        $data['navbar'] = 'milestone';
        $data['milestone'] = DB::table('milestones')->where('id', $id)->first();
        
        if (empty($data['milestone'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }

        $data['project'] = DB::table('projects')
                        ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
                        ->select('projects.id','projects.name','ps.name as status_name')
                        ->where('projects.id', $data['milestone']->project_id)->first();
        
        return view('admin.project.milestone.edit',$data);
    }

    public function update(Request $request)
    {
      $this->validate($request, [
            'name'     => 'required',
            'due_date' => 'required',
        ]);

        $data['name'] = $request->name; 
        $data['description'] = $request->description; 
        $data['due_date'] = DbDateFormat($request->due_date);

        DB::table('milestones')->where('id', $request->milestone_id)->update($data);
        // Insert Activity
        (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('Update milestone'));

        \Session::flash('success', __('Successfully updated'));
        return redirect()->intended('projects/milestones/view/'.$request->project_id.'/'.$request->milestone_id);   
    }

    public function view($project_id, $id)
    {
        $data['menu'] = 'project';
        $data['header'] = 'project';
        $data['page_title'] = __('View Milestone for project');
        $data['navbar'] = 'milestone';
        $data['project'] = DB::table('projects')->where('id', $project_id)->select('id')->first();
        $data['milestone'] = DB::table('milestones')->where('id',$id)->first();
        if (empty($data['project']) || empty($data['milestone'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        $tags                    = DB::table('tags')->pluck('name')->toArray();
        $data['tags']            = json_encode($tags);
        $data['task_status_all'] = DB::table('task_statuses')->select('id', 'name')->get();
        $data['assignees']       = DB::table('users')->where('is_active', 1)->get();

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->mileStoneTaskDataTable->with('milestone_id', $id)->with('row_per_page', $row_per_page)->render('admin.project.milestone.view', $data);
    }

    public function delete(Request $request)
    {
        $milestone_id = $request->milestone_id;
        $project_id   = $request->project_id;
        if ($milestone_id && $project_id) {
           DB::table('milestones')->where(['id'=> $milestone_id, 'project_id'=> $project_id])->delete();
             // Insert Activity
             (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('Delete milestone'));

           \Session::flash('success', __('Deleted Successfully.'));
        }
        return redirect()->back();
    }

  public function projectMileStonePdf()
  {
    $data['milestones'] = DB::table('milestones')->where('milestones.project_id', intval($_GET['project']))->get();
    $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
    $data['projectData'] = Project::find($_GET['project']);
    return printPDF($data, 'milestones_pdf' . time() . '.pdf', 'admin.project.milestone.list_pdf', view('admin.project.milestone.list_pdf', $data), 'pdf', 'domPdf');
  }

  public function projectMileStoneCsv()
  {
    return Excel::download(new MileStoneExport(), 'time_sheet'.time().'.csv');
  }

  public function getMilestone(Request $request)
  {
    $data = ['output' => '', 'status' => 0];
    $project_id     = $request->project_id;
    $milestones     = DB::table('milestones')->where('project_id', $project_id)->select('id', 'name')->get();
    if ($milestones) {
        $data['output'] .= '<option value=" ">' . __('Please Select Milestone') . '</option>';
        foreach ($milestones as $key => $value) {
            $data['output'] .= '<option value="' . $value->id . '">' . $value->name . '</option>';
        }
        $data['status'] = 1;
    }
    return $data;
  }
}
