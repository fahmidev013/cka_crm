<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Start\Helpers;
use DB;
use PDF;
use Validator;
use Auth;
use Session;
use App\Models\Preference;
use \Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function index()
    {
        $data['items']      = [];
        $data['quantities'] = [];
        $data['menu']       = 'setting';
        $data['sub_menu']   = 'barcode';
        $data['page_title'] = __('Barcode');
        $data['header']     = 'barcode';
        
        if ($this->request->isMethod('post')) {
            $data['stock_ids']     = $this->request->stock_id;
            $data['perpage']      = $this->request->perpage;
            $data['product_name'] = $this->request->product_name;
            $data['company_name'] = $this->request->site_name;

            if (!empty($this->request->name) && !empty($this->request->quantity)) {
                $data['items']        = $this->request->name;
                $data['quantities']   = $this->request->quantity;
            }
        }
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        
        if (isset($this->request->site_name) && !empty($preference['company_name'])) {
           $data['companyName'] = $preference['company_name'];
        } else {
            $data['companyName'] = '';
        }

        return view('admin.barcode.create', $data);
    }

  public function search()
    {
            $data = array();
            $data['status_no'] = 0;
            $data['message']   = __('No Item Found');
            $data['items'] = array();

            $item = DB::table('items')
            ->where('name', 'LIKE', '%' . $this->request->search . '%')
            ->orWhere('stock_id', 'LIKE', '%' . $this->request->search . '%')
            ->get();

            if (!empty($item)) {
                
                $data['status_no'] = 1;
                $data['message']   = __('Item Found');

                $i = 0;

                foreach ($item as $key => $value) {
                    $items[$i]['id'] = $value->id;
                    $items[$i]['stock_id'] = $value->stock_id;
                    $items[$i]['description'] = $value->name;
                    $i++;
                }
                $data['items'] = $items;
            }

            echo json_encode($data);
            exit;            
    }

}
