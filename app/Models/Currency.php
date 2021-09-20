<?php

namespace App\Models;
use DB;
use Cache;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Preference,
    CurrencyConverterConfiguration
};

class Currency extends Model
{
    public $timestamps = false;

    public function accounts()
    {
        return $this->hasMany(Account::class,'currency_id');
    }

    public function bankTransfers()
    {
    	return $this->hasMany("App\Models\Account",'to_currency_id');
    }
    
    
    public function customers()
    {
        return $this->hasMany('App\Models\Customer','currency_id');
    }

    public function suppliers()
    {
        return $this->hasMany("App\Models\Supplier",'currency_id');
    }

    public function purchaseOrder()
    {
        return $this->hasOne('App\Models\PurchaseOrder', 'currency_id');
    }

    public function saleOrders()
    {
        return $this->hasMany('App\Models\SaleOrder', 'currency_id');
    }

    public function transactions()
    {
      return $this->hasMany("App\Models\Transaction", 'currency_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-currency');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-currency', $data, 30 * 86400);
        }
        return $data;
    }

    public static function getDefault($preference = array())
    {
        $data = Cache::get('gb-defaultCurrency');
        if (empty($data)) {
            $prefer = !empty($preference) ? $preference : Preference::getAll()->pluck('value', 'field')->toArray();
            $data = self::getAll()->where('id', $prefer['dflt_currency_id'])->first();
			if (empty($data)) {
				$data = self::getAll()->first();
				Preference::where('field', 'dflt_currency_id')->update(['field' => 'dflt_currency_id', 'value' => $data->id]);
				Cache::clear('gb-preferences');
			}
            Cache::put('gb-defaultCurrency', $data, 30 * 86400);
        }
        return $data;
    }

    public function getExchangeRate($toCurrency, $fromCurrency)
    {
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $currencies = $this->whereIn('id', [$preference['dflt_currency_id'], $fromCurrency, $toCurrency])->get();
        $currencyConverter = CurrencyConverterConfiguration::getAll()->where('status', 'active')->first();

        $to = $currencies->where('id', $toCurrency)->first();
        $from = $currencies->where('id', $fromCurrency)->first();
        if (empty($to) || empty($from)) {
            return 1;
        }
        $default = $currencies->where('id', $preference['dflt_currency_id'])->first();
        if ($to->id == $from->id) {
            return 1;
        }
        $fromExchangeRate = $from->exchange_from == 'api' ? getCurrencyRate($default->name, $from->name, $currencyConverter) : $from->exchange_rate ;
        $toExchangeRate = $to->exchange_from == 'api' ? getCurrencyRate($default->name, $to->name, $currencyConverter) : $to->exchange_rate;
        if ($fromExchangeRate == 0 || $toExchangeRate == 0) {
            return 0;
        }
        
        return number_format((float)($toExchangeRate / $fromExchangeRate), (float)$preference['exchange_rate_decimal_digits'], '.', '');
    }
}
