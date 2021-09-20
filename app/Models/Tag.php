<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	
	public $timestamps = false;
    
    public function tagAssigns()
    {
        return $this->hasMany('App\Models\TagAssign', 'tag_id');
    }

}
