<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;

class EmailTemplate extends Model
{
	public $timestamps = false;

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-email_template');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-email_template', $data, 30 * 86400);
        }
        return $data;
    }
}
