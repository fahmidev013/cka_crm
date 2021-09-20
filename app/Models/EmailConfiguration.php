<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Rules\{
    CheckValidEmail
};
use Validator;
use Cache;

class EmailConfiguration extends Model
{
    public $timestamps = false;
    protected function validation($data = [])
    {
        $validator = Validator::make($data, [
            'type' => 'required|in:smtp,sendmail',
            'encryption' => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_email' => ['required', 'email', new CheckValidEmail],
            'from_address' => ['required', 'email', new CheckValidEmail],
            'from_name' => ['required', 'email', new CheckValidEmail],
            'smtp_username' => ['required', 'email', new CheckValidEmail],
            'smtp_password' => 'required'
        ]);
        return $validator;
    }
    public static function getAll()
    {
        $data = Cache::get('gb-email_configurations');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-email_configurations', $data, 30 * 86400);
        }
        return $data;
    }
    public function store($request = [])
    {
        if (parent::updateOrInsert(['id' => 1], $request)) {
            Cache::forget('gb-email_configurations');
            return true;
        }
        return false;
    }

}
