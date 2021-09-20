<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Validator;
use Illuminate\Validation\Rule;

class CurrencyConverterConfiguration extends Model
{
    public $timestamps = false;

    /**
     * Store Validation
     * @param  array  $data
     * @return mixed      
     */
    protected function storeValidation($data = []) 
    {
        $validator = Validator::make($data, [
            'currency_converter_api.api_key' => [
                Rule::requiredIf($data['currency_converter_api']['status'] == "active")
            ],
            'exchange_rate_api.api_key' => [
                Rule::requiredIf($data['exchange_rate_api']['status'] == "active")
            ],
        ]);
        
        return $validator;
    }


	/**
     * Caching email configuration data
     * @return object $data
    */
    public static function getAll()
    {
        $data = Cache::get('gb-currency_converter_configurations');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-currency_converter_configurations', $data, 30 * 86400);
        }
        
        return $data;
    }

    /**
     * Store
     * @param  array  $request
     * @return boolean         
     */
    public function store($request = [])
    {   
        if (parent::updateOrInsert(["slug" => $request['slug']], $request)) {
            Cache::forget('gb-currency_converter_configurations');
            return true;
        }

       return false;
    }
}
