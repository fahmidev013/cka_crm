<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Http\Start\Helpers;
use App\Models\TaxType;
use App\Models\StockMaster;
use App\Models\SaleTax;
use App\Models\Preference;
use Cache;


class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'finance';
        $data['page_title'] = __('Taxes');
        $data['list_menu'] = 'tax';
        $data['taxData'] = TaxType::getAll();
        
        if (Helpers::has_permission(\Auth::user()->id, 'manage_tax')) {
            return view('admin.tax.tax_list', $data);
        }
        
        return redirect(getRouteAccordingToPermission([
                'manage_currency'       => 'currency',
                'manage_account_type'   => 'bank/account-type',
                'manage_payment_term'   => 'payment/terms',
                'manage_payment_method' => 'payment/method'
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'tax_rate' => 'required',
        ]);
        $data['name'] = $request->name;
        $data['tax_rate'] = validateNumbers($request->tax_rate);
        $data['defaults'] = $request->defaults;

        if($request->defaults == 1) {
            $defultTaxToUpdate  = TaxType::where('is_default', 1)->update(['is_default' => 0]);
        }
        $newTax= new TaxType();
        $newTax->name   = $data['name'];
        $newTax->tax_rate   = $data['tax_rate'];
        $newTax->is_default   = $data['defaults'];
        $newTax->save();

        Cache::forget('gb-taxType');


        $id = $newTax->id;

        if (!empty($id)) {
            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('tax');
        } else {
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    public function validTaxName(Request $request)
    {
       
        $taxName = $_GET['name'];
        if(isset($_GET['tax_id'])){
            $taxID = $_GET['tax_id'];
            $v = DB::table('tax_types')
                ->where('name',$taxName)
                ->where('id', "!=", $taxID)
                ->first();
        }else{
            $v = DB::table('tax_types')
                ->where('name',$taxName)
                ->first();
        }

        if (!empty($v)) {
            echo json_encode(__('That tax name is already taken.'));
        } else {
            echo "true";
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $taxData = TaxType::find($request->id);

            $return_arr['name'] = $taxData->name;
            $return_arr['tax_rate'] = $taxData->tax_rate;
            $return_arr['id'] = $taxData->id;
            $return_arr['defaults'] = $taxData->is_default;

            echo json_encode($return_arr);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'tax_id' => 'required',
            'tax_rate' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['tax_rate'] = validateNumbers($request->tax_rate);
        $data['defaults'] = $request->defaults;
        $id = $request->tax_id;

        if($request->defaults == 1) {
            $defultTaxToUpdate  = TaxType::where('is_default', 1)->update(['is_default' => 0]);
        }
        
        //update
        $taxToUpdate             = TaxType::find($id);
        $taxToUpdate->name       = $data['name'];
        $taxToUpdate->tax_rate   = $data['tax_rate'];
        $taxToUpdate->is_default = $data['defaults'];
        $taxToUpdate->save();
        Cache::forget('gb-taxType');

        \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('tax');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {   
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $record  = TaxType::find($request->id);
        if (!empty($record)) {
            $invoice_quotation_tax = SaleTax::where('tax_type_id', $request->id)->exists();
            $purchase_tax          = DB::table('purchase_taxes')->where('tax_type_id', $request->id)->exists();
            if ($invoice_quotation_tax === true || $purchase_tax === true) {
                 $data = [ 
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This tax has records.')
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
        Cache::forget('gb-taxType');
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('tax');
    }
}
