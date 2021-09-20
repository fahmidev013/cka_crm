<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TagAssign extends Model
{
	public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag_id');
    }
}
