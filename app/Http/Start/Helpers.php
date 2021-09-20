<?php

namespace App\Http\Start;

use View;
use Session;
use DB;
use App\Models\ProjectSetting;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\RoleUser;
class Helpers
{

	public static function has_permission($user_id, $permissions = '')
	{
		$permissions = explode('|', $permissions);
        $user_permissions = Permission::getAll()->whereIn('name', $permissions);
		$permission_id = [];
		$i = 0;
		foreach ($user_permissions as $value) {
			$permission_id[$i++] = $value->id;
		}
        $role = RoleUser::getAll()->where('user_id', $user_id)->first();

		if(count($permission_id) && isset($role->role_id)){
            $has_permit = PermissionRole::getAll()->where('role_id', $role->role_id)->whereIn('permission_id', $permission_id);
			return $has_permit->count();
		}
		else return 0;
	}


    public static function project_permission($project_id, $permissions = '')
    {
        $permission = ProjectSetting::where(['project_id'=> $project_id, 'setting_label'=> $permissions])->first();
        if (! is_null($permission)) {
            return 1;
        }
        return 0;
    }



    
}
