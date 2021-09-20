<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Session;
use App\Http\Start\Helpers;
use App\Models\ItemUnit;
use App\Models\Preference;
use App\Models\StockCategory;


class UnitController extends Controller
{   
    /**
     * Display a listing of the Item Units.
     *
     * @return Unit list page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Units');
        $data['list_menu'] = 'unit';
        $data['unitData'] = ItemUnit::all();
        
        return view('admin.unit.unit_list', $data);
    }


    /**
     * Verify a unit abbr before storing it, same abbr is not allowed without the particular edit.
     *
     * @param  \Illuminate\Http\Request  $_GET
     * @return a validation error massage will be shown, if a abbr is already existed in both add or edit operation
     */

    public function validUnitAbbr()
    {
        $abbrName= $_GET['abbr'];        
        if (isset($_GET['unit_id'])) {
            $unit_id = $_GET['unit_id'];
            $v = DB::table('item_units')
                ->where('abbreviation',$abbrName)
                ->where('id', "!=", $unit_id)
                ->first();
        } else {
            $v = DB::table('item_units')
                ->where('abbreviation',$abbrName)
                ->first();
        }             
              
        if (!empty($v)) {
            echo json_encode( __('That Abbreviation(Abbr) is already taken.') );
        } else {
            echo "true";
        }
    }

    /**
     * Store a newly created Item Unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Unit list page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2|unique:item_units,name',
            'abbr' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['abbr'] = $request->abbr;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $newUnit = new ItemUnit();
        $newUnit->abbreviation    = $data['abbr'];
        $newUnit->name    = $data['name'];
        $newUnit->save();
        
        $id = $newUnit->id;

        if (!empty($id)) {

            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('unit');
        } else {

            return back()->withInput()->withErrors(['email' => __('Invalid Request')]);
        }
    }
    
    /**
     * Show the form for editing the specified Item Unit.
     *
     * @param  int  $id
     * @return Unit edit page view
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $unitData = ItemUnit::find($request->id);
            
            $return_arr['name'] = $unitData->name;
            $return_arr['abbr'] = $unitData->abbreviation;
            $return_arr['id'] = $unitData->id;

            echo json_encode($return_arr); 
        }
    }

    /**
     * Update the specified Item Unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Unit list page view
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'abbr' => 'required',
            'id' => 'required',
        ]);

        $id                         = $request->id;
        $unitToUpdate               = ItemUnit::find($id);
        $unitToUpdate->name         = $request->name;
        $unitToUpdate->abbreviation = $request->abbr;
        $unitToUpdate->save();
        

        \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('unit');
    }

    /**
     * Remove the specified Item Unit from storage.
     *
     * @param  int  $id
     * @return Unit list page view
     */
    public function destroy($id)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
       
        $record = ItemUnit::find($id);
        if (isset($id)) {
            $itemCatUnit = StockCategory::where('item_unit_id', $id)->exists();
            $itemUnit    = DB::table('items')->where('item_unit_id', $id)->exists();
            $itemWeightUnit    = DB::table('items')->where('weight_unit_id', $id)->exists();
             if ($itemCatUnit === true || $itemUnit === true || $itemWeightUnit === true) {
                 $data = [ 
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This unit has records.')
                ];
            } else {
                if ($record->delete()) {
                    $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully!')
                    ];   
                }
            }
        }
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('unit');
    }
}
