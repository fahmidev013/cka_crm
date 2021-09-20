<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cache;

class Department extends Model
{
	public $timestamps = false;

	public function tickets() {
        return $this->hasMany('App\Models\Ticket', 'department_id');
    }

    public function getdepartmentsCSV() {
    	$departments = DB::table('departments')->orderBy('id', 'desc');
    	return $departments;
    }
    public static function getAll()
    {
        $data = Cache::get('gb-departments');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-departments', $data, 30 * 86400);
        }
        return $data;
    }
}
