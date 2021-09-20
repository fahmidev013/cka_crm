<?php

namespace App\Http\Controllers;

use App\DataTables\StockTransferListDataTable;
use App\Exports\stockTransferExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Preference;
use App\Models\Location;
use App\Models\StockMove;
use App\Models\StockTransfer;
use App\Models\Item;
use Auth;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidateNewItemTransfer;
use App\Rules\ValidateOldItemTransfer;
use App\Rules\CheckQuantity;


class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StockTransferListDataTable $dataTable)
    {
        $data = ['menu' => 'purchase'];
        $data['sub_menu'] = 'stock_transfer';
        $data['page_title'] = __('Stock Transfers');

        $data['from'] = isset($_GET['from']) ? $_GET['from']:null;
        $data['to'] = isset($_GET['to']) ? $_GET['to']:null;

        $data['source'] = $source = isset($_GET['source']) ? $_GET['source'] : 'all';
        $data['destination'] = $destination = isset($_GET['destination']) ? $_GET['destination'] : 'all';
        $stockTransfer = DB::table('stock_transfers')->get();

        $data['sourceList'] = Location::getAll();
        $data['destinationList'] = Location::getAll()->where('is_active', '=', 1)->where('id', '!=', $source);
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.stockTransfer.list',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = ['menu' => 'purchase'];
        $data['sub_menu'] = 'stock_transfer';
        $data['page_title'] = __('Create Stock Transfers');
        $data['locationList'] = Location::getAll()->where('is_active', 1);
        return view('admin.stockTransfer.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     **/
    public function store(Request $request)
    {
        $userId = \Auth::user()->id;
        $rules = array(
            'source' => 'required',
            'destination' => 'required',
            'transfer_date' => 'required',
            'id' => new ValidateNewItemTransfer,
            'quantity' => new CheckQuantity,
        );
        $fieldNames = array(
            'source' => 'Source is required',
            'destination' => 'Destination is required',
            'transfer_date' => 'Transfer date is required',
            'id' => 'New_item_avalilable',
            'quantity' => 'checkQuantity',
        );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $itemQuantity = $request->quantity;
        $itemIds = $request->id;
        $stockIds = $request->stock;
        $description = $request->description;
        $transfer_date = DbDateFormat($request->transfer_date);
        $total = 0;

        foreach ($itemQuantity as $index => $qty) {
            $total += validateNumbers($qty);
        }

        try {
                DB::beginTransaction();
                $transferInfo['source_location_id'] = $request->source;
                $transferInfo['destination_location_id'] = $request->destination;
                $transferInfo['user_id'] = $userId;
                $transferInfo['transfer_date'] = $transfer_date;
                $transferInfo['note'] = stripBeforeSave($request->comments);
                $transferInfo['quantity'] = $total;

                $transfer_id = DB::table('stock_transfers')->insertGetId($transferInfo);

                foreach ($itemIds as $key => $item_id) {
                   // Store In
                   $stockIn[$key]['item_id'] = $item_id;
                   $stockIn[$key]['transaction_type_id'] = $transfer_id;
                   $stockIn[$key]['transaction_type'] = 'STOCKMOVEIN';
                   $stockIn[$key]['location_id'] = $request->destination;
                   $stockIn[$key]['transaction_date'] = $transfer_date;
                   $stockIn[$key]['user_id'] = $userId;
                   $stockIn[$key]['reference'] = 'moved_from_'.$request->source;
                   $stockIn[$key]['note'] = stripBeforeSave($request->comments);
                   $stockIn[$key]['quantity'] = validateNumbers($itemQuantity[$key]);

                   // Store Out
                   $stockOut[$key]['item_id'] = $item_id;
                   $stockOut[$key]['transaction_type_id'] = $transfer_id;
                   $stockOut[$key]['transaction_type'] = 'STOCKMOVEOUT';
                   $stockOut[$key]['location_id'] = $request->source;
                   $stockOut[$key]['transaction_date'] = $transfer_date;
                   $stockOut[$key]['user_id'] = $userId;
                   $stockOut[$key]['reference'] = 'moved_in_'.$request->destination;
                   $stockOut[$key]['note'] = stripBeforeSave($request->comments);
                   $stockOut[$key]['quantity'] = '-'.validateNumbers($itemQuantity[$key]);
            }

            for ($i=0; $i < count($stockIds); $i++) {
                DB::table('stock_moves')->insertGetId($stockOut[$i]);
                DB::table('stock_moves')->insertGetId ($stockIn[$i]);
            }

            DB::commit();

            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('stock_transfer/list');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $data = ['menu' => 'purchase'];
        $data['sub_menu'] = 'stock_transfer';
        $data['page_title'] = __('Edit Stock Transfer');
        $data['locationList'] = Location::getAll();
        $data['Info'] = StockTransfer::with(['sourceLocation:id,name,is_active', 'destinationLocation:id,name,is_active'])->find($id);
        $data['List'] = StockMove::with(['item:id,name,is_stock_managed'])->where(['transaction_type_id' => $id, 'transaction_type' => 'STOCKMOVEIN'])->get();
        $data['stock_transfer_id'] = $id;
        if (!empty($data['Info'])) {
            if ($data['Info']->sourceLocation->is_active == 0 || $data['Info']->destinationLocation->is_active == 0) {
                \Session::now('fail', __('Can not perform any action, location inactive.'));
            }
            return view('admin.stockTransfer.edit', $data);
        } else {
            return redirect()->back()->withErrors(__('The data you are trying to access is not found.'));
        }
    }

    public function update(Request $request)
    {
        $stockOutByID = DB::table('stock_moves')->where(['transaction_type_id' => (int) $request->transfer_id, 'transaction_type' => 'STOCKMOVEOUT'])->get();
        $stockInByID = DB::table('stock_moves')->where(['transaction_type_id' => (int) $request->transfer_id, 'transaction_type' => 'STOCKMOVEIN'])->get();
        $oldItemInIds = array_column($stockInByID->toArray(), 'item_id');
        $oldItemOutIds = array_column($stockOutByID->toArray(), 'item_id');
        $oldItemIds = array_unique(array_merge($oldItemInIds, $oldItemOutIds));

        $userId = Auth::user()->id;
        $request->source = isset($request->source) ? $request->source : $request->trans_source;
        $request->destination = isset($request->destination) ? $request->destination : $request->trans_destination;
        $deletalbeIDs = array();
        $TrnsfrRowItemIds = array();
        $total = 0;

        $rules = array (
            'trans_source' => 'required',
            'trans_destination' => 'required',
            'transfer_date' => 'required',
            'new_item_id' => new ValidateNewItemTransfer,
            'item_id' => new ValidateOldItemTransfer,
            'new_item_quantity' => new CheckQuantity,
            'item_quantity' => new CheckQuantity,
        );
        $fieldNames = array (
            'trans_source' => 'Source',
            'trans_destination' => 'Destination',
            'transfer_date' => 'Transfer date',
            'new_item_id' => 'New_item_avalilable',
            'item_id' =>'Old_item_avalilable',
            'new_item_quantity' => 'checkQuantity',
            'item_quantity' => 'checkQuantity',
        );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);
        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        // Get Total item quantity
        if (isset($request->item_quantity)) {
            foreach ($request->item_quantity as $index => $qty) {
                $total += validateNumbers($qty);
            }
        }

        if (isset($request->new_item_quantity)) {
            foreach ($request->new_item_quantity as $index => $qty) {
                $total += validateNumbers($qty);
            }
        }

        // Get Item ID from database by transfer id and Stock IN
        $idArr= DB::table('stock_moves')
                    ->where(['transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEIN'])
                    ->select('item_id')->get();

        foreach ($idArr as $key => $value) {
            array_push($TrnsfrRowItemIds, $idArr[$key]->item_id);
        }

        // Get Deletable IDs
        if (! empty($TrnsfrRowItemIds) || ! empty($request->item_id)) {
            if (empty($request->item_id)) {
                $request->item_id = array(0);
            }
            $deletalbeIDs =  array_unique(array_values(array_diff($TrnsfrRowItemIds, $request->item_id)));
        }

        // For Delete ITEMS
        if (isset($request->new_item_id) && !empty($request->new_item_id)) {
            $deletalbeIDs = array_unique(array_values(array_diff($deletalbeIDs, $request->new_item_id)));
        }

        try {

            DB::beginTransaction();
            if (! empty($deletalbeIDs)) {
                foreach ($deletalbeIDs as $deletalbeID) {
                    $record = DB::table('stock_moves')->where(['item_id' => $deletalbeID, 'transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEIN'])->first();
                    if (! empty($record)) {
                        DB::table('stock_moves')->where(['item_id' => $deletalbeID,'transaction_type_id' => $request->transfer_id,'transaction_type' => 'STOCKMOVEIN'])->delete();
                        DB::table('stock_moves')->where(['item_id' => $deletalbeID,'transaction_type_id' => $request->transfer_id,'transaction_type' => 'STOCKMOVEOUT'])->delete();
                    }
                }
            }


            // UPDATE existing ITEMS
            if (! empty($request->item_id)) {
                for ($i=0; $i < count($request->item_id); $i++) {
                    DB::table('stock_moves')->where(['item_id' => $request->item_id[$i], 'transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEIN'])->update(['quantity' => validateNumbers($request->item_quantity[$i])]);
                    DB::table('stock_moves')->where(['item_id' => $request->item_id[$i], 'transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEOUT'])->update(['quantity' => '-'.validateNumbers($request->item_quantity[$i])]);
                }
                DB::table('stock_transfers')->where(['id' => $request->transfer_id,'user_id' => $userId])->update(['quantity' => $total, 'note' => stripBeforeSave($request->comments)]);
            }

            // Add new ITEMS
            if (! empty($request->new_item_id)) {
                foreach ($request->new_item_id as $key => $item_id) {
                    if (in_array($item_id, $oldItemIds)) {
                        DB::table('stock_moves')->where(['item_id' => $item_id, 'transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEIN'])->update(['quantity' => validateNumbers($request->new_item_quantity[$key])]);
                        DB::table('stock_moves')->where(['item_id'=>$item_id, 'transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEOUT'])->update(['quantity' => '-'.validateNumbers($request->new_item_quantity[$key])]);
                        DB::table('stock_transfers')->where(['id' => $request->transfer_id,'user_id' => $userId])->update(['quantity' => $total, 'note' => stripBeforeSave($request->comments)]);
                    } else {
                        // Store In
                        $stockIn[$key]['item_id']                = $item_id;
                        $stockIn[$key]['transaction_type_id']    = $request->transfer_id;
                        $stockIn[$key]['transaction_type']       = 'STOCKMOVEIN';
                        $stockIn[$key]['location_id']            = $request->destination;
                        $stockIn[$key]['transaction_date']       = DbDateFormat($request->transfer_date);
                        $stockIn[$key]['user_id']                = $userId;
                        $stockIn[$key]['reference']              = 'moved_from_'.$request->source;
                        $stockIn[$key]['quantity']               = validateNumbers($request->new_item_quantity[$key]);
                        $stockIn[$key]['note']                   = stripBeforeSave($request->comments);
                        DB::table('stock_moves')->insert($stockIn[$key]);

                        // Store Out
                        $stockOut[$key]['item_id']               = $item_id;
                        $stockOut[$key]['transaction_type_id']   = $request->transfer_id;
                        $stockOut[$key]['transaction_type']      = 'STOCKMOVEOUT';
                        $stockOut[$key]['location_id']           = $request->source;
                        $stockOut[$key]['transaction_date']      = DbDateFormat($request->transfer_date);
                        $stockOut[$key]['user_id']               = $userId;
                        $stockOut[$key]['reference']             = 'moved_in_'.$request->destination;
                        $stockOut[$key]['quantity']              = '-'.validateNumbers($request->new_item_quantity[$key]);
                        $stockOut[$key]['note']                  = stripBeforeSave($request->comments);
                        DB::table('stock_moves')->insert($stockOut[$key]);

                    }
                }
            }
            DB::commit();
            Session::flash('success', __('Successfully updated'));
            return redirect('stock_transfer/list');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function itemSearch(Request $request)
    {
        $location_id = $request->location;
        $data = array();
        $data['status_no'] = 0;
        $data['message'] = __('No Item Found');
        $data['items'] = array();

        $items = Item::with(['available' => function ($query) use($location_id) {
                    $query->where('location_id', $location_id);
                }])->where('name', 'LIKE', '%' . $request->search . '%')
                    ->where('item_type', 'product')
                    ->where('is_active', 1)
                    ->limit(10)
                    ->get();
        $transfers = null;
        if (isset($request->transfer_id)) {
            $transfers = StockMove::with(['item:id,name,is_stock_managed'])->where(['transaction_type_id' => $request->transfer_id, 'transaction_type' => 'STOCKMOVEIN'])->pluck('quantity', 'item_id')->toArray();
        }

        if (!empty($items)) {
            $data['status_no'] = 1;
            $data['message'] = __('Item Found');
            $i = 0;
            foreach ($items as $key => $value) {
                if ($value->is_stock_managed == 0 || ($value->is_stock_managed == 1 && !is_null($value->available))) {
                    $return_arr[$i]['id'] = $value->id;
                    $return_arr[$i]['name'] = $value->name;
                    $return_arr[$i]['is_stock_managed'] = $value->is_stock_managed;
                    $return_arr[$i]['available'] = 0;
                    if ($value->available) {
                        $return_arr[$i]['available'] = $value->available->quantity;
                    }
                    if (!empty($transfers) && isset($transfers[$value->id])) {
                        $return_arr[$i]['available'] += $transfers[$value->id];
                    }
                    $i++;
                }
            }
            $data['items'] = $return_arr;
        }
        echo json_encode($data);
        exit();
    }

    public function checkItemQty(Request $request)
    {
        $data = array();
        $location = $request['source'];
        $item_id = $request['item_id'];
        $result = DB::table('stock_moves')
                     ->select(DB::raw('sum(quantity) as total'))
                     ->where(['item_id' => $item_id, 'location_id' => $location])
                     ->groupBy('location_id')
                     ->first();
        if (isset($result)) {
            $data['qty'] = $result->total;
            $data['status_no'] = 1;
        } else {
            $data['qty'] = 0;
            $data['status_no'] = 0;
        }
        $data['message'] = 'Available quantity is '. $data['qty'];
        return json_encode($data);

    }

    public function checkOldItemQty(Request $request)
    {
        $data = array();
        $result = DB::table('stock_moves')
                    ->where(['item_id' => $request->item_id, 'location_id' => $request->destination, 'transaction_type' => 'STOCKMOVEIN', 'transaction_type_id' => $request->transfer_id])
                    ->first();
        if (isset($result)) {
            $data['qty'] = $result->quantity;
            $data['status_no'] = 1;
        } else {
            $data['qty'] = 0;
            $data['status_no'] = 0;
        }
        return json_encode($data);

    }

    public function destinationList(Request $request)
    {
        $source = $request['source'];
        $data['status_no'] = 0;
        $destination = '';
        $result = DB::table('locations')->select('id', 'name')->where('id', '!=', $source)->where('is_active', 1)->orderBy('name','ASC')->get();
        if (!empty($result)) {
            $data['status_no'] = 1;
            // This flag is set only at filtering in list view. Otherwise it will be not set
            isset($request['flag']) ? $destination .= "<option value=''>" . __('All') . "</option>" : $destination .= "<option value=''>" . __('All Destinations') . "</option>";

            foreach ($result as $key => $value) {
            $destination .= "<option value='". $value->id ."'>". $value->name ."</option>";
        }
        $data['destination'] = $destination;
       }
        return json_encode($data);
    }

    public function details($id)
    {
        $data = ['menu' => 'purchase', 'sub_menu' => 'stock_transfer', 'page_title' => __('View Stock Stansfer')];
        $data['Info'] = StockTransfer::with(['sourceLocation:id,name', 'destinationLocation:id,name'])->find($id);
        $data['List'] = StockMove::with(['item:id,name'])->where(['transaction_type_id' => $id, 'transaction_type' => 'STOCKMOVEIN'])->orderBy('quantity','DESC')->get();

        if (!empty($data['Info'])) {
            return view('admin.stockTransfer.detail', $data);
        } else {
            return redirect()->back()->withErrors(__('The data you are trying to access is not found.'));
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        if (isset($id)) {
            $record = \DB::table('stock_transfers')->where('id', $id)->first();
            if ($record) {

                try {
                    DB::beginTransaction();

                    DB::table('stock_moves')->where(['transaction_type_id' => $id,'reference'=>'moved_in_'.$record->destination_location_id])->delete();
                    DB::table('stock_moves')->where(['transaction_type_id' => $id, 'reference'=>'moved_from_'.$record->source_location_id])->delete();
                    DB::table('stock_transfers')->where(['id' => $id])->delete();

                    DB::commit();

                    \Session::flash('success', __('Deleted Successfully.'));
                    return redirect()->intended('stock_transfer/list');
               } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
               }

            }
        }
    }

    public function destroyTranser($id)
    {
        $id = $id;
        if (isset($id)) {
            $record = \DB::table('stock_transfers')->where('id', $id)->first();
            if ($record) {
                try {
                    DB::beginTransaction();

                    DB::table('stock_moves')->where(['transaction_type_id'=>$id,'reference'=>'moved_in_'.$record->destination])->delete();
                    DB::table('stock_moves')->where(['transaction_type_id' => $id, 'reference'=>'moved_from_'.$record->source])->delete();
                    DB::table('stock_transfers')->where(['id' => $id])->delete();

                    DB::commit();
                    \Session::flash('success', __('Deleted Successfully.'));
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->back();
                }
            }
        }
    }

    public function stock_transfer_csv()
    {
        return Excel::download(new stockTransferExport(), 'stock_transfer_list'. time() .'.csv');
    }

    public function stock_transfer_pdf()
    {
       $to       = $_GET['to'] != '' ? $_GET['to'] : null;
       $from     = $_GET['from'] != '' ? $_GET['from'] : null;
       $data['source_location_id'] = $source = $_GET['source'] != '' ? $_GET['source'] : null;
       $data['destination_location_id'] = $destination = $_GET['destination'] != '' ? $_GET['destination'] : null;
       $data['sourceTransfer'] = DB::table('locations')->select('id', 'name')->where('id', '=', $source)->first();
       $data['destinationTransfer'] = DB::table('locations')->select('id', 'name')->where('id', '=', $destination)->first();


       $stockTransfer = StockTransfer::with(['sourceLocation:id,name', 'destinationLocation:id,name']);
        if ($from) {
            $stockTransfer->where('transfer_date', '>=', DbDateFormat($from));
        }
        if ($to) {
            $stockTransfer->where('transfer_date', '<=', DbDateFormat($to));
        }
        if ($source) {
            $stockTransfer->where('source_location_id', '=', $source);
        }
        if ($destination) {
            $stockTransfer->where('destination_location_id', '=', $destination);
        }
        $data['stocks'] = $stockTransfer->orderBy('id', 'desc')->get();

        if ($from && $to) {
			$data['date_range'] =  formatDate($from) . __('To') . formatDate($to);
        } else {
			$data['date_range'] = __('No Date Selected');
        }

        return printPDF($data, 'stock_transfer_list' . time() . '.pdf', 'admin.stockTransfer.stock_transfer_pdf', view('admin.stockTransfer.stock_transfer_pdf', $data), 'pdf', 'domPdf');
    }

}
