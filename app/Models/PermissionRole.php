<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class PermissionRole extends Model
{
	public $timestamps = false;

	public static function getAll()
    {
        $data = Cache::get('gb-permission_roles');
        if (empty($data)) {
            $data = DB::table('permission_roles')->get();
            Cache::put('gb-permission_roles', $data, 30 * 86400);
        }

        return $data;
    }
}
