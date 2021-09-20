<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Cache;

class RoleUser extends Model
{
    public static function getAll()
    {
        $data = Cache::get('gb-role_users');
        if (empty($data)) {
            $data = DB::table('role_users')->get();
            Cache::put('gb-role_users', $data, 30 * 86400);
        }

        return $data;
    }
}
