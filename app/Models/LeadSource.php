<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
	public $timestamps = false;

    public function leads()
    {
        return $this->hasMany('App\Models\Lead', 'lead_source_id');
    }
}
