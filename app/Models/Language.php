<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;  

class Language extends Model
{
    public $timestamps = false;

    public function emailTemplateDetails()
    {
        return $this->hasMany('App\Models\EmailTemplate', 'language_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-languages');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-languages', $data, 30 * 86400);
        }
        return $data;
    }
}
