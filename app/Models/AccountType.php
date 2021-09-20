<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    public $timestamps = false;

    //Relation Start
    public function accounts()
    {
        return $this->hasMany("App\Models\Account", 'account_type_id');
    }
    //Relation End

}
