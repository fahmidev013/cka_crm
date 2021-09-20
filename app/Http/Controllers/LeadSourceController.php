<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\LeadSource;
use App\Models\Preference;
use App\Models\Lead;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use Validator;
use session;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the Lead Source.
     *
     * @return Lead Source List page view
     */
    public function index()
    {
        $data['access'] = \Session::all();
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Lead Source');
        $data['list_menu'] = 'lead_source';
        
        $data['leadSourceData'] = LeadSource::all();

        return view('admin.lead.lead_source_list', $data);
    }

    /**
     * Validate the lead source name wheather it is existed or not.
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function validLeadSource(Request $request)
    {   
        $leadSourceName = $_GET['name'];
        if (isset($_GET['leadSource_id'])) {            
            $leadSource_id = $_GET['leadSource_id'];
            $v = DB::table('lead_sources')
                ->where('name',$leadSourceName)
                ->where('id', "!=", $leadSource_id)
                ->first();
        } else {
            $v = DB::table('lead_sources')
                ->where('name',$leadSourceName)
                ->first();
        }

        if (!empty($v)) {
            echo json_encode(__('That Lead Source Name is already taken.'));
        } else {
            echo "true";
        }
    }

    /**
     * Store a newly created Lead Source in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Lead Source List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:lead_sources,name',
        ]);

        $newLeadSource = new LeadSource();
        $newLeadSource->name    = $request->name;
        $newLeadSource->status  = $request->status;

        $newLeadSource->save();

        $id = $newLeadSource->id;

        if (!empty($id)){

            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('lead-source');
        } else {

            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    

    /**
     * Show the form for editing the specified Lead Source.
     *
     * @param  int  $id
     * @return Lead Source edit page view
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $leadSourceData = LeadSource::find($request->id);

            $return_arr['name']        = $leadSourceData->name;
            $return_arr['active_status']  = $leadSourceData->status;
            $return_arr['id'] = $leadSourceData->id;

            echo json_encode($return_arr);
        }
    }

    /**
     * Update the specified Lead Source in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Lead Source List page view
     */
    public function update(Request $request)
    {    
        $this->validate($request, [
           'name' => 'required',
            
        ]);

        $leadSourceToUpdate = LeadSource::find($request->source_id);
        $leadSourceToUpdate->name   = $request->name;
        $leadSourceToUpdate->status  = $request->my_status;

        $leadSourceToUpdate->save();

        $id = $leadSourceToUpdate->id;

        \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('lead-source');
    }

    /**
     * Remove the specified Lead Source from storage.
     *
     * @param  int  $id
     * @return redirect Lead Source List page view
     */
    public function destroy($id)
    {
         $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $leadSourceToDelete = LeadSource::find($id);
        if (!empty($leadSourceToDelete)) {
            $leadSource         = Lead::where('lead_source_id', $id)->exists();
            if ($leadSource === true) {
                $data ['message'] = __('Can not be deleted. This source has records.');
            } else {
                if ($leadSourceToDelete->delete()) {
                    $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ]; 
                }
            }
        }
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('lead-source');
    }

    

}
