<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\LeadStatus;
use App\Models\Preference;
use App\Models\Lead;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use Validator;
use Session;

class LeadStatusController extends Controller
{
    /**
     * Display a listing of the Lead Status.
     *
     * @return Lead Status List page view
     */
    public function index()
    {
        $data['access'] = \Session::all();
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Lead Status');
        $data['list_menu'] = 'lead_status';
        $data['leadStatusData'] = LeadStatus::all();
        return view('admin.lead.lead_status_list', $data);
    }

    /**
     * Validate the lead status name wheather it is existed or not.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function validLeadStatus(Request $request)
    {   
        $leadStatusName = $_GET['name'];
        if (isset($_GET['leadStatus_id'])) {            
            $leadStatusID = $_GET['leadStatus_id'];
            $v = DB::table('lead_statuses')
                ->where('name',$leadStatusName)
                ->where('id', "!=", $leadStatusID)
                ->first();
        } else {
            $v = DB::table('lead_statuses')
                ->where('name',$leadStatusName)
                ->first();
        }

        if (!empty($v)) {
            echo json_encode(__('That Lead Status name is already taken.'));
        } else {
            echo "true";
        }
    }

    
    /**
     * Store a newly created Lead Status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Lead Status List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:lead_statuses,name',
        ]);

        $newLeadStatus = new LeadStatus();
        $newLeadStatus->name    = $request->name;
        if ($request->color==null) {
            $newLeadStatus->color = "#ddd";
        } else {
            $newLeadStatus->color   = $request->color;
        }
        $newLeadStatus->status  = $request->status;

        $newLeadStatus->save();

        $id = $newLeadStatus->id;

        if (!empty($id)) {

            Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('lead-status');
        } else {

            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Show the form for editing the specified Lead Status.
     *
     * @param  int  $id
     * @return Lead Status edit page view
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $leadStatusData = LeadStatus::find($request->id);

            $return_arr['name']          = $leadStatusData->name;
            $return_arr['color']         = $leadStatusData->color;
            $return_arr['active_status'] = $leadStatusData->status;
            $return_arr['id']            = $leadStatusData->id;

            echo json_encode($return_arr);
        }
    }

    /**
     * Update the specified Lead Status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Lead Status List page view
     */
    public function update(Request $request)
    {    
        $this->validate($request, [
           'name' => 'required',
            
        ]);

        $leadStatusToUpdate = LeadStatus::find($request->status_id);
        $leadStatusToUpdate->name   = $request->name;
        if ($request->color==null) {
            $leadStatusToUpdate->color = "#ddd";
        } else {
            $leadStatusToUpdate->color   = $request->color;
        }
        $leadStatusToUpdate->status  = $request->my_status;

        $leadStatusToUpdate->save();

        $id = $leadStatusToUpdate->id;

        \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('lead-status');
    }

    /**
     * Remove the specified Lead Status from storage.
     *
     * @param  int  $id
     * @return redirect Lead Status List page view
     */
    public function destroy($id)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $leadStatusToDelete = LeadStatus::find($id);
        if (!empty($leadStatusToDelete)) {
            $leadStatus         = Lead::where('lead_status_id', $id)->exists();
            if ($leadStatus === true) {
                $data ['message'] = __('Can not be deleted. This status has records.');
            } else {
                if ($leadStatusToDelete->delete()) {
                     $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                }
            }
        }
        \Session::flash($data['type'],$data['message']);
        return redirect()->intended('lead-status');
    }
}
