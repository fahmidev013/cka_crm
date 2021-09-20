<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $fillable = ['stock_id','stock_category_id', 'item_type', 'parent_id', 'name', 'available_variant',
  'size', 'color', 'weight', 'weight_unit_id', 'is_stock_managed', 'description', 'item_image', 'hsn', 'is_active', 'alert_quantity'];

  public function stockMoves(){
    return $this->hasMany("App\Models\StockMove", 'item_id');
  }

  public function available()
  {
    return $this->hasOne('App\Models\StockMove')
                ->selectRaw('item_id,SUM(quantity) as quantity')
                ->groupBy('item_id');
  }

  public function image()
  {
    return $this->hasOne('App\Models\File', 'object_id')->where('object_type', 'Item');
  }

  public function variants(){
    return $this->hasMany("App\Models\Item", 'parent_id');
  }

  public function category()
  {
    return $this->belongsTo("App\Models\StockCategory", 'stock_category_id');
  }

  public function parent()
  {
      return $this->belongsTo("App\Models\Item", 'parent_id');
  }
  public function itemCustomVariants()
  {
      return $this->hasMany("App\Models\ItemCustomVariant", 'item_id');
  }

  public function weightUnit()
  {
      return $this->belongsTo("App\Models\ItemUnit", 'weight_unit_id');
  }

  public function salePrices()
  {
      return $this->hasOne("App\Models\SalePrice", 'item_id')->where(['sale_type_id' => 1]);
  }

  public function purchasePrices()
  {
      return $this->hasOne("App\Models\PurchasePrice", 'item_id');
  }

  public function retailPrices()
  {
      return $this->hasOne("App\Models\SalePrice", 'item_id')->where(['sale_type_id' => 1]);
  }

  public function wholesalePrices(){
      return $this->hasOne("App\Models\SalePrice", 'item_id')->where(['sale_type_id' => 2]);
  }

  public function salesOrderDetails()
  {
      return $this->hasMany("App\Models\SaleOrderDetail", 'item_id');
  }

  public function taxType()
  {
      return $this->belongsTo("App\Models\TaxType", 'tax_type_id');
  }

  public function itemUnit()
  {
      return $this->belongsTo("App\Models\ItemUnit", 'item_unit_id');
  }

  public function purchaseOrderDetail()
  {
      return $this->hasMany('App\Models\PurchaseOrderDetail', 'item_id');
  }

  public function search($request)
  {
      $request       = json_decode($request);
      $type          = $request->type;
      $location_id   = isset($request->location) ? $request->location : null;
      $saleType      = $request->saleType;
      $currency_id   = $request->currency_id;
      $exchange      = validateNumbers($request->exchange_rate);
      $key           = $request->key;
      if(!$type){
          return __("invalid type");
      }
      $data = array();
      $data['status_no']  = 0;
      $data['message']    = __('No Item Found');
      $data['items']      = array();
      $return_arr         = [];
      $defaultCurrencyId  = Preference::getAll()->pluck('value', 'field')->toArray()['dflt_currency_id'];
      if($defaultCurrencyId != $currency_id) {
          $ex_rate = ExchangeRate::where(['currency_id' => $currency_id])->orderBy('id','DESC')->first();
          if (!empty($ex_rate)) {
              $exchange_rate = $ex_rate->rate;
          } else {
              $exchange_rate = 1;
          }
      } else {
          $exchange_rate = 1;
      }
	  
      if (!empty($exchange) &&  $exchange != 0) {
          $exchange_rate = $exchange;
      }

      $items = Item::with(['taxType', 'available' => function ($query) use($location_id)
      {
        if (!empty($location_id)) {
          $query->where('location_id', $location_id);
        }
      }, 'itemUnit'])
                  ->where('name', 'LIKE', '%' . $key . '%')
                  ->where('item_type',$type)
                  ->where('is_active', 1)
                  ->limit(10)
                  ->get();

      if(!$items->isEmpty()){
          $data['status_no'] = 1;
          $data['message'] = __('Item Found');
          $i = 0;
          foreach ($items as $key => $value) {
              if ($value->is_stock_managed == 0 || ($value->is_stock_managed == 1 && !is_null($value->available))) {
                  if ($request->transactionType == 'Purchase') {
                      $itemPriceValue = PurchasePrice::where(['item_id' => $value->id])->first(['price']);
                  } else {
                    $itemPriceValue = SalePrice::where(['item_id' => $value->id, 'sale_type_id' => $saleType])->first(['price']);
                  }
                  if (empty($itemPriceValue)) {
                      $itemSalesPriceValue = 0;
                  } else {
                      $itemSalesPriceValue = round($itemPriceValue->price * $exchange_rate, 8);
                  }
                  $return_arr[$i]['id'] = $value->id;
                  $return_arr[$i]['stock_id'] = $value->stock_id;
                  $return_arr[$i]['name'] = $value->name;
                  $return_arr[$i]['description'] = $value->description;
                  if (!empty($value->itemUnit)) {
                      $return_arr[$i]['units'] = $value->itemUnit->name;
                  }
                  $return_arr[$i]['price'] = $itemSalesPriceValue;
                  $return_arr[$i]['tax_rate'] = 0;
                  if ($value->taxType) {
                      $return_arr[$i]['tax_id'] = $value->taxType->id;
                      $return_arr[$i]['tax_rate'] = $value->taxType->tax_rate;
                  }
                  $return_arr[$i]['hsn'] = $value->hsn;
                  $return_arr[$i]['is_stock_managed'] = $value->is_stock_managed;
                  $return_arr[$i]['available'] = 0;
                  if ($value->available) {
                      $return_arr[$i]['available'] = $value->available->quantity;
                  }
                  $i++;
              }
          }
          $data['items'] = $return_arr;
      }
      echo json_encode($data);
      exit;
  }


  public function getAllItem()
  {
    $data = DB::select(DB::raw("SELECT item.id as item_id, item.inactive, item.id, item.item_name, item.category_id, item.item_image as img,pp.price as purchase_price, sc.description as category_name, COALESCE
            (sm.item_qty,0) as item_qty, sph.price as whole_sale_price, spr.price as retail_sale_price

            FROM (SELECT * FROM items WHERE deleted_status = 0)item

            LEFT JOIN purchase_prices as pp
             ON pp.item_id = item.id

            LEFT JOIN stock_category as sc
             ON sc.id = item.category_id

            LEFT JOIN(SELECT stock_id,sum(qty) as item_qty FROM stock_moves GROUP BY stock_id)sm
             ON sm.stock_id = item.id

            LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 2)sph
             ON sph.item_id = item.id

            LEFT JOIN(SELECT item_id,price FROM sale_prices WHERE sale_type_id = 1)spr
             ON spr.item_id = item.id

            ORDER BY item.item_name ASC
             "));
    return $data;
  }

  public function getItemById($id)
  {
    return $this->where('items.id', '=', $id)
    				->leftJoin('stock_master', 'items.id', '=', 'stock_master.item_id')
    				->leftJoin('stock_category', 'items.category_id', '=', 'stock_category.id')
    				->select('items.*', 'stock_master.*', 'stock_category.description as cname')
    				->first();
  }

  public function getTransaction($id)
  {
            $data = DB::table('stock_moves')
                   ->select(DB::raw('sum(qty) as total, loc_code'))
                   ->where(['stock_id' => $id])
                   ->groupBy('loc_code')
                   ->get();
        return $data;
  }

  public function stock_validate($loc,$id)
  {
      $data = DB::table('stock_moves')
                   ->select(DB::raw('sum(qty) as total'))
                   ->where(['stock_id' => $id, 'loc_code' => $loc])
                   ->groupBy('loc_code')
                   ->first();

        return $data;
  }

  public function getAllItemCsv()
  {
    $itemID =(int) request()->segment(2);
    $conditions = "ic.is_active = 1";
    if (gettype($itemID) == "integer" && $itemID != 0) {
      $conditions.= ' and ic.id = '. $itemID .' or ic.parent_id = '. $itemID;
    }
    $dad =  DB::select("SELECT ic.`id` as item_id, ic.name, sc.name as category, pp.price as purcashe_price, rp.price as retail_price, wsp.price as wholesale_price FROM `items` as ic
    LEFT JOIN stock_categories as sc
    ON sc.`id` = ic.`stock_category_id`

    LEFT JOIN purchase_prices as pp
    ON pp.`item_id` = ic.`id`

    LEFT JOIN(SELECT * FROM `sale_prices` WHERE `sale_type_id` = 1)rp
    ON rp.item_id = ic.`id`

    LEFT JOIN(SELECT * FROM `sale_prices` WHERE `sale_type_id` =2 )wsp
    ON wsp.item_id = ic.`id`
    WHERE $conditions ORDER BY ic.id DESC
    ");
    return $dad;
  }

  public static function getItemNotifications()
  {
    $itemNotifications = DB::table('items')
                              ->select('items.*',DB::raw('SUM(stock_moves.quantity) as qty'))
                              ->leftJoin('stock_moves','stock_moves.item_id','=','items.id')
                              ->where('items.alert_quantity','>=',DB::raw('quantity'))
                              ->groupBy('items.id')
                              ->get();
    return $itemNotifications;
  }
}
