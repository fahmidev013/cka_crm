<?php
namespace App\DataTables;
use App\Models\Item;
use App\Models\File;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
class ItemDataTable extends DataTable
{
    public function ajax()
    {
        $item = $this->query();
        return datatables()
            ->of($item)
            ->addColumn('action', function ($item) {
                $edit = (Helpers::has_permission(Auth::user()->id, 'edit_item')) ? '<a href="' . url("edit-item/item-info/$item->item_id") . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                $delete = (Helpers::has_permission(Auth::user()->id, 'delete_item')) ? '
                    <form method="POST" action="'.url("item/delete").'"accept-charset="UTF-8" class="display_inline" id="delete-item-'. $item->item_id .'">
                    ' . csrf_field() . '
                        <input type="hidden" name="id" value="'.$item->item_id.'">
                        <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $item->item_id .'" data-target="#confirmDelete" data-label = "Delete" data-title="' . __('Delete item') . '" data-message="' . __('Are you sure to delete this item?') . '">
                            <i class="feather icon-trash-2"></i> 
                        </button>
                    </form>' : '';
                return $edit.$delete;
            })

            ->addColumn('img', function ($item) {
                if (isset($item->file_name)  && !empty($item->file_name)) {
                    if (file_exists('public/uploads/items/thumbnail/' . $item->file_name)) {
                        $img = '<img src="'. url("public/uploads/items/thumbnail/". $item->file_name) .'" alt="" width="50" height="50">';
                    } else {
                        $img = '<img src="'. url("public/dist/img/default-image.png") .'" alt="" width="50" height="50">';
                    }
                } else {
                        $img = '<img src="'. url("public/dist/img/default-image.png") .'" alt="" width="50" height="50">';
                }
                return $img;
            })

            ->addColumn('description', function ($item) {
                return '<a href="'.url('edit-item/variant/'.$item->item_id).'">'.$item->name.'</a>';
            })

            ->addColumn('item_qty', function($item){
                if ($item->item_type != 'service') {
                    $qty = ($item->item_qty != null || $item->item_qty>0 ) ? $item->item_qty : 0;
                    $qtyOnHand = $item->qty_on_hand ? $item->qty_on_hand : 0;
                    $totalVariant = $item->total_variant ? $item->total_variant : 0;
                    if($qtyOnHand || $totalVariant > 0)
                        return formatCurrencyAmount($qty)."<br/> <span class='color-3c8dbc'>".formatCurrencyAmount($qtyOnHand)."</span> in ".formatCurrencyAmount($totalVariant)." variants";
                    else
                        return formatCurrencyAmount($qty);
                } else {
                    return '-';
                }
            })

            ->addColumn('purchase_price', function($item){
                $purchase_price = ($item->purchase_price != null || $item->purchase_price>0 ) ? formatCurrencyAmount($item->purchase_price) : formatCurrencyAmount(0);
                return $purchase_price;
            })

            ->addColumn('retail_sale_price', function($item){
                $retail_price = ($item->retail_sale_price != null || $item->retail_sale_price > 0 ) ? formatCurrencyAmount($item->retail_sale_price) : formatCurrencyAmount(0);
                return $retail_price;
            })

            ->addColumn('inactive', function ($item) {
                if ($item->is_active == 1) {
                    $status = '<span class="badge theme-bg text-white f-12">' . __('Active') . '</span>';
                } else {
                    $status = '<span class="badge theme-bg2 text-white f-12">' . __('Inactive') . '</span>';
                }
                return $status;
            })

            ->rawcolumns(['img','description','action','item_qty', 'inactive', 'purchase_price', 'retail_sale_price'])

            ->make(true);
    }
 
    public function query()
    {               // get purcahase price
        $items = Item::leftJoin('purchase_prices as pp','pp.item_id','=','items.id') 
                    // get category description
                  ->leftJoin('stock_categories as sc','sc.id','=','items.stock_category_id') 
                    // get all item with individual total no. of quantitiy
                  ->leftJoin(DB::raw("(SELECT item_id,sum(quantity) as item_qty FROM stock_moves GROUP BY item_id
                    ) as sm"),function($join_sm){
                      $join_sm->on("sm.item_id","=","items.id"); 
                    })
                    // get item vairiant
                  ->leftJoin(DB::raw("(SELECT vari.parent_id as p_id,COUNT(vari.parent_id) as total_variant,sum(sm.qty_on_hand) as qty_on_hand FROM items as vari LEFT JOIN (SELECT item_id,sum(quantity) as qty_on_hand FROM `stock_moves` GROUP BY item_id)as sm on sm.item_id=vari.id GROUP BY vari.parent_id)as varian"),function($join){
                      $join->on('varian.p_id','=','items.id');
                  }) 
                    // get whole-sale price 
                  ->leftJoin(DB::raw("(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 2) as sph"),function($join_sph){
                    $join_sph->on('sph.item_id', '=', 'items.id'); 
                   })
                    // get retail price 
                  ->leftJoin(DB::raw("(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1) as spr"), function($join_spr){
                    $join_spr->on('spr.item_id', '=', 'items.id');
                  }) 
                   // get files
                  ->leftJoin('files as fl', function($join) {
                    $join->on('fl.object_id', '=', 'items.id')
                         ->where('fl.object_type', '=', 'Item');
                  })  
                  ->where('parent_id', 0)
                  ->select("varian.qty_on_hand","varian.total_variant","items.id as item_id","items.is_active","items.description","items.name","items.stock_category_id","pp.price as purchase_price","sc.name as category","sm.item_qty as item_qty","sph.price as whole_sale_price","spr.price as retail_sale_price", 'items.item_type', 'fl.file_name');
        return $this->applyScopes($items);
    }
    
    public function html()
    {
        return $this->builder()
            
            ->addColumn(['data' => 'img', 'name' => 'img', 'title' => __('Picture'), 'orderable' => false, 'searchable' => false])

            ->addColumn(['data' => 'description', 'name' => 'items.name', 'title' => __('Name')])
            
            ->addColumn(['data' => 'category', 'name' => 'sc.name', 'title' => __('Category')])
           
            ->addColumn(['data' => 'item_qty', 'name' => 'sm.item_qty', 'title' => __('On hand')])

            ->addColumn(['data' => 'purchase_price', 'name' => 'pp.price', 'title' => __('Purchase').' '.($this->currency->symbol)]) 
            ->addColumn(['data' => 'retail_sale_price', 'name' => 'spr.price', 'title' => __('Retail').' '.($this->currency->symbol)])
            
            ->addColumn(['data' => 'inactive', 'name' => 'inactive', 'title' => __('Status'), 'orderable' => false])
            
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [1, 'asc']
            ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'itemdatatables_' . time();
    }
}
