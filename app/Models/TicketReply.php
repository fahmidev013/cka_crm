<?php

namespace App\Models;

use App\Http\Start\Helpers;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Null_;

class TicketReply extends Model
{
  	public function ticket()
	{
	    return $this->belongsTo('App\Models\Ticket','ticket_id');
	}

	public function user()
	{
	    return $this->belongsTo('App\Models\User','user_id');
	}

	public function customer()
	{
	    return $this->belongsTo('App\Models\Customer','customer_id');
	}
}
