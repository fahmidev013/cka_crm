<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class Permission extends Model
{
    public static function getAll()
    {
        $data = Cache::get('gb-permissions');
        if (empty($data)) {
            $data = DB::table('permissions')->get();
            Cache::put('gb-permissions', $data, 30 * 86400);
        }

        return $data;
    }
}
