<?php
/**
 * @package GroupController
 * @author tehcvillage <support@techvill.org>
 * @contributor Sakawat Hossain Rony <[sakawat.techvill@gmail.com]>
 * @created 19-06-2021
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    Group,
    KnowledgeBase
};
use Illuminate\Http\Request;
use DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the Group.
     *
     * @return Group List page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Group');
        $data['list_menu'] = 'group';
        $data['groupData'] = Group::getAll();
        return view('admin.group.group_list', $data);
    }

    /**
     * Validate the group name wheather it is existed or not.
     *
     * @param  \Illuminate\Http\Request  $request
     */

   public function validGroup(Request $request)
   {
       $query = Group::getAll()->where('name', $request->name);

       if (isset($request->group_id)) {
           $query = $query->where('id', "!=", $request->group_id);
       }
       if (!empty($query->first())) {
           echo json_encode(__('That Group Name is already taken.'));
       } else {
           echo "true";
       }
    }

    /**
     * Store a newly created group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Group page view
     */
    public function store(Request $request)
    {
        $data = ['status' => 'fail', 'message' => __("Invalid Request")];
        $validator = Group::storeValidation($request->all());
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->messages());
        }
        if((new Group)->store($request->only('name', 'status', 'description'))) {
            $data['status'] = 'success';
            $data['message'] = __('Successfully Saved');
        }

        \Session::flash($data['status'], $data['message']);
        return redirect()->intended('groups');
    }



    /**
     * Show the form for editing the specified Group.
     *
     * @param  int  $id
     * @return Group edit page view
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $groupData = Group::getAll()->where('id', $request->id)->first();

            $return_arr['name']          = $groupData->name;
            $return_arr['description']   = $groupData->description;
            $return_arr['active_status'] = $groupData->status;
            $return_arr['id']            = $groupData->id;

            echo json_encode($return_arr);
        }
    }

    /**
     * Update the specified Groupin storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Group List page view
     */
    public function update(Request $request)
    {
        $data = ['status' => 'fail', 'message' => __("Failed To Update Group Information")];
        $request['status'] = $request->my_status;
        $validator = Group::updateValidation($request->all(), $request->group_id);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->messages());
        }
        if ($request->my_status == 'inactive' && KnowledgeBase::getAll()->where('group_id', $request->group_id)->count() > 0) {
                $data['message'] = __('Can not be inactive. This group has records.');
        } elseif ((new Group)->updateGroup($request->only('name', 'status', 'description'), $request->group_id)) {
            $data['status'] = 'success';
            $data['message'] = __('Successfully updated');
        }

        \Session::flash($data['status'], $data['message']);
        return redirect()->intended('groups');
    }

    /**
     * Remove the specified Group from storage.
     *
     * @param  int  $id
     * @return redirect Group List page view
     */
    public function destroy($id)
    {
        $data = ['status' => 'fail', 'message' => __("Failed To Delete Group Information")];
        if ((new Group)->remove($id)) {
            $data['status'] = 'success';
            $data['message'] = __('Deleted Successfully.');
        }

        \Session::flash($data['status'], $data['message']);
        return redirect()->intended('groups');
    }
}
