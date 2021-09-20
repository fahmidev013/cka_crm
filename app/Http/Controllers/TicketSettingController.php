<?php

namespace App\Http\Controllers;

use App\Exports\departmentsExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Category;
use App\Models\Department;
use App\Models\Preference;
use App\Models\Ticket;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use Validator;
use Illuminate\Support\Facades\Session;
use Cache;

class TicketSettingController extends Controller
{
    public function department()
    {
    	$data['menu']           = 'setting';
        $data['sub_menu']       = 'company';
        $data['page_title']     = __('Departments');
        $data['list_menu']      = 'ticket_setting';
        $data['departmentData'] = Department::all();

        return view('admin.departmentSetting.setting_list', $data);
    }

    public function storeDepartment(Request $request)
    {
    	$this->validate($request, [
            'name' => 'required|min:2|unique:departments,name'
        ]);

        $data['name'] = $request->name;
        $newDepartment  = new Department();
        $newDepartment->name    = $data['name'];
        $newDepartment->save();
        
        $id = $newDepartment->id;

        if (!empty($id)) {
            Cache::forget('gb-departments');
            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('department-setting');
        } else {
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }

    }

    public function editDepartment(Request $request)
    {
        if (!empty($request->id)) {
            $departmentData = Department::find($request->id);
            $return_arr['name'] = $departmentData->name;
            $return_arr['id'] = $departmentData->id;

            echo json_encode($return_arr);
        }

    }

    public function destroyDepartment(Request $request)
    {
        $data = [ 
                'type'    => 'fail',
                'message' => __('Something went wrong, please try again.')
            ];
        $departmentToDelete = Department::find($request->id);
        if (!empty($departmentToDelete)) {
            $ticket_department    = Ticket::where('department_id', $request->id)->exists();
            $user_department      = DB::table('user_departments')->where('department_id', $request->id)->exists();
            if ($ticket_department === true && $user_department === true) {
                 $data = [ 
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This department has records.')
                ];
            } else {
                if ($departmentToDelete->delete()) {
                    Cache::forget('gb-departments');
                    $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                } 
            }

        }
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('department-setting');
    }

    public function updateDepartment(Request $request)
    {
    	$this->validate($request, [
            'name' => 'required|min:2',
        ]);

        $id = $request->depart_id;
        $data['name'] = $request->name;
        
        $departmentToUpdate = Department::find($id);
        $departmentToUpdate->name   = $data['name'];
        $departmentToUpdate->save();
        Cache::forget('gb-departments');
        
        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('department-setting');

    }

    public function validDepartmentName(Request $request)
    {
        $deptName = $_GET['name'];
        if (isset($_GET['deptID'])) {
            $deptID = $_GET['deptID'];
            $v = DB::table('departments')
                ->where('name',$deptName)
                ->where('id', "!=", $deptID)
                ->first();
        } else {
            $v = DB::table('departments')
                ->where('name',$deptName)
                ->first();
        }

        if (!empty($v)) {
            echo json_encode(__('That Department Name is already taken.'));
        } else {
            echo "true";
        }
    }

    public function departmentdownload()
    {
        if (request()->type == "csv") {
            return Excel::download(new departmentsExport(), 'department_list'.time(). '.' . request()->type);
        } else if (request()->type == 'pdf') {
            $data['department'] = (new Department)->getdepartmentsCSV();
            $data['categorydata'] = (new Department)->getdepartmentsCSV()->get();
            return printPDF($data, 'department_list' . time() . '.pdf', 'admin.departmentSetting.department_pdf', view('admin.departmentSetting.department_pdf', $data), 'pdf', 'domPdf');
        } else {
            session()->flash('danger', 'File type did not match');
            return back();
        }
    }
}