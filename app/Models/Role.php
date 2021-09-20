<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Role extends Model
{
    public function users()
    {
		return $this->hasMany('App\Models\User', 'role_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-roles');
        if (empty($data)) {
            $data = DB::table('roles')->get();
            Cache::put('gb-roles', $data, 30 * 86400);
        }

        return $data;
    }
}
