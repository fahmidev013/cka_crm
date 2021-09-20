<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Session;
use App\Http\Start\Helpers;
use App\Models\Location;
use App\Models\Preference;
use App\Models\SaleOrder;
use App\Models\PurchaseOrder;
use App\Models\StockTransfer;
use App\Models\StockAdjustment;
use Cache;


class LocationController extends Controller
{
    /**
     * Display a listing of the Locations.
     *
     * @return Location list page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title'] = __('Locations');
        $data['list_menu'] = 'location';
        $data['locationData'] = Location::getAll();
        
        return view('admin.location.location_list', $data);
    }

    /**
     * Show the form for creating a new Location.
     *
     * @return Location create page view
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title'] = __('Create Location');
        $data['list_menu'] = 'location';
        
        return view('admin.location.location_add', $data);
    }

    /**
     * Store a newly created Location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Location list page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'location_name'    => 'required',
            'loc_code'         => 'required|unique:locations,code',
            'delivery_address' => 'required',
        ]);
        $data['location_name']    = $request->location_name;
        $data['loc_code']         = $request->loc_code;
        $data['delivery_address'] = $request->delivery_address;
        $data['default']          = $request->default;
        if ($data['default'] == 1) {
            $updateDefault = Location::where('is_default', 1)->update(['is_default' => 0]);
        }
        $data['phone']     = $request->phone;
        $data['fax']       = $request->fax;
        $data['email']     = $request->email;
        $data['contact']   = $request->contact;
        $data['is_active'] = $request->status;
        
        //insert
        $newLocation                   = new Location();
        $newLocation->name             = $data['location_name'];
        $newLocation->code             = $data['loc_code'];
        $newLocation->delivery_address = $data['delivery_address'];
        $newLocation->is_default       = $data['default'];
        $newLocation->phone            = $data['phone'];
        $newLocation->fax              = $data['fax'];
        $newLocation->email            = $data['email'];
        $newLocation->contact          = $data['contact'];
        $newLocation->is_active        = $data['is_active'];
        $newLocation->save();
        Cache::forget('gb-locations');
            

        $id = $newLocation->id;

        if (!empty($id)) {

            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('location');
        } else {

            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Show the form for editing the specified Location.
     *
     * @param  int  $id
     * @return Location edit page view
     */
    public function edit($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title'] = __('Edit Location');
        $data['header'] = 'location';
        $data['list_menu'] = 'location';
        $data['breadcrumb'] = 'editlocation';
        $data['locationData'] = Location::getAll()->find($id);
        if (empty($data['locationData'])) {
            Session::flash('fail', __("Location does not exist."));
            return redirect('location');
        }
        return view('admin.location.location_edit', $data);
    }

    /**
     * Update the specified Location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Location list page view
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'location_name' => 'required',
            'loc_code' => "required|unique:locations,code,$id,id",
            'delivery_address' => 'required',
        ]);
        
        $data['name']             = $request->location_name;
        $data['code']             = $request->loc_code;
        $data['delivery_address'] = $request->delivery_address;
        $data['is_default']       = $request->default;
        $data['phone']            = $request->phone;
        $data['fax']              = $request->fax;
        $data['email']            = $request->email;
        $data['contact']          = $request->contact;
        $data['is_active']        = $request->status;
        $data['updated_at']       = date('Y-m-d H:i:s');
        
        if ($data['is_default'] == 1) {
            $updateDefault = Location::where('is_default', 1)->update(['is_default' => 0]);
        }

        DB::table('locations')->where('id', $id)->update($data);
        Cache::forget('gb-locations');

        \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('location');
    }

    /**
     * Remove the specified Location from storage.
     *
     * @param  int  $id
     * @return redirect Location list page view
     */
    public function destroy($id)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $record         = Location::find($id);
        if (!empty($record)) {
            $saleLoc      = SaleOrder::where('location_id', $record->id)->exists();
            $purchaseLoc   = PurchaseOrder::where('location_id', $record->id)->exists(); 
            $stockTransLoc = StockTransfer::where('source_location_id', $record->id)
                                               ->orwhere('destination_location_id', $record->id)
                                               ->exists(); 
            $stockAdjLoc   = StockAdjustment::where('location_id', $record->id)
                                              ->exists(); 
            if ($saleLoc === true || $purchaseLoc === true || $stockTransLoc === true || $stockAdjLoc === true) {
                $data = [ 
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This location has records.')
                ];
            } else {
                if ($record->delete()) {
                    $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                }  
            }
        }
        Cache::forget('gb-locations');
          \Session::flash($data['type'],$data['message']);
            return redirect()->intended('location');
    }

    /**
     * Location validate from storage.
     *
     * @return true or false
     */
    public function validLocCode()
    {
        $loc_code = $_GET['loc_code'];
        if (isset($_GET['locID'])) {
            $locID = $_GET['locID'];
            $v = DB::table('locations')
                ->where('code',$loc_code)
                ->where('id', "!=", $locID)
                ->first();
        } else {
            $v = DB::table('locations')
                ->where('code',$loc_code)
                ->first();
        }             
              
        if (!empty($v)) {
            echo json_encode(__('That Location Code is already taken.'));
        } else {
            echo "true";
        }
    }
}
