<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use PDF;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Http\Start\Helpers;
use App\Models\Preference;
use App\Models\Activity;


class ActivityController extends Controller
{
    /**
     * index Method
     * 
     * @param  integer $id
     * @return render view
     */
    public function index($id = 0)
    {
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu'] = 'project';
        }
        $data['header'] = 'project';
        $data['page_title'] = __('Project Activity');
        $data['navbar'] = 'activity';
        $data['project']= DB::table('projects')
                        ->select('projects.id', 'projects.name', 'ps.name as status_name')
                        ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
                        ->where('projects.id', $id)->first();
        if (empty($data['project'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        $data['activities'] = Activity::where(['object_type' => "Project", 'object_id' => $id])
                            ->orderBy('id', 'desc')
                            ->get();

        return view('admin.project.activity.list', $data);
    }

}
