<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Cache;
use Auth;


class Customer extends Authenticatable
{
	protected $table = "customers";
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','email','is_activated','password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function customerBranch()
    {
        return $this->hasOne("App\Models\CustomerBranch", 'customer_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_id');
    }

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'customer_id');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'customer_id');
    }

    public function activity()
    {
        return $this->hasOne('App\Models\Activity', 'customer_id');
    }

    public static function getTimezone()
    {
        $loggedCustomer = Auth::guard('customer')->user()->id;
        $data = Cache::get('gb-dflt_timezone_customer'.$loggedCustomer);
        if (empty($data)) {
            $data = parent::find($loggedCustomer)->timezone;
            Cache::put('gb-dflt_timezone_customer'.$loggedCustomer, $data, 30 * 86400);
        }
        return $data;
    }
}
