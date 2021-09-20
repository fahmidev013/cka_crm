<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
	protected $table = 'lead_statuses';
	public $timestamps = false;

    public function leads()
    {
        return $this->hasMany('App\Models\Lead', 'lead_status_id');
    }
}
