<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Validator;

class CaptchaConfiguration extends Model
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
            'site_key' => 'required',
            'secret_key' => 'required',
            'site_verify_url' => 'required',
            'plugin_url' => 'required',
        ]);

        return $validator;
    }

	/**
     * Caching email configuration data
     * @return object $data
    */
    public static function getAll()
    {
        $data = Cache::get('gb-captcha_configurations');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-captcha_configurations', $data, 30 * 86400);
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
        if (parent::updateOrInsert(['id' => 1], $request)) {
            Cache::forget('gb-captcha_configurations');
            return true;
        }

       return false;
    }
}
