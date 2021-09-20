<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Preference;
use App\Models\Role;
use App\Models\User;

use App\Http\Start\Helpers;
use Validator;
use DB;
use Session;
use Cache;

class RoleController extends Controller
{
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title'] = __('Roles');
        $data['list_menu'] = 'role';
        $data['roleData'] = Role::all();
        return view('admin.role.role', $data);
    }

    public function createOld()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'role';
        $data['permissions'] = DB::table('permissions')->get();
        return view('admin.role.role_add', $data);
    }

    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title'] = __('Create Role');
        $data['list_menu'] = 'role';
        $permission = Permission::orderBy('permission_group')->get();
        
        foreach ($permission as $object) {
            $szn[] = $object->toArray();
        }
        
        $permissions = array_group_by($szn, "permission_group");

        foreach ($permissions as $key => $value) {
            $final = [ 
                'manage' => null,
                'own' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
            ];
            foreach ($value as $val) {
                $final[explode('_', $val['name'])[0]] = $val;
            }
            $permissions[$key] = $final;
        }

        $data['permissions']  = $permissions;
        return view('admin.role.role_add', $data);
    }

    public function store(Request $request)
    {
        if (!empty($request->permissions)) {

            $rules = array(
                    'name'         => 'required|unique:roles',
                    'display_name' => 'required',
                    'description'  => 'required'
                    );

            $validator = Validator::make($request->all(),$rules);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $role['name']         = $request->name;
                $role['display_name'] = $request->display_name;
                $role['description']  = $request->description;
                //insert
                $newRoleToInsert               = new Role();
                $newRoleToInsert->name         =  $request->name;
                $newRoleToInsert->display_name = $request->display_name;
                $newRoleToInsert->description  = $request->description;
                $newRoleToInsert->save();

                $roleId                        = $newRoleToInsert->id;
                foreach ($request->permissions as $key => $value) {
                    $newPermissionRole                = new PermissionRole();
                    $newPermissionRole->permission_id = $value;
                    $newPermissionRole->role_id       = $roleId;
                    $newPermissionRole->save();
                }
                Cache::forget('gb-roles');
                Cache::forget('gb-role_users');
                Cache::forget('gb-permission_roles');
                Session::flash('success', __('Successfully Saved'));
                return redirect('role/list');
            }        
        } else {
            Session::flash('danger', __('Please give at least one permission'));
            return redirect()->back();
        }
    }

    public function edit_old($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'role';
        $data['role'] = DB::table('roles')->where('id',$id)->first();
        
        $data['permissions'] = DB::table('permissions')->get();
        $data['stored_permissions'] = DB::table('permission_role')->where('role_id',$id)->pluck('permission_id');
        return view('admin.role.role_edit', $data);
    }

     public function edit($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title'] = __('Edit Role');
        $data['list_menu'] = 'role';
        $data['role']  = Role::find($id);
        if ($id == 1 && strtolower($data['role']->name) === 'admin') {
            Session::flash('fail', __('Admin role is not editable.'));
            return redirect()->intended('role/list');
        }

        if (empty($data['role'])) {
            Session::flash('fail', __('Role does not exist.'));
            return redirect('role/list');
        }
        $data['stored_permissions'] = PermissionRole:: where('role_id', $id)->pluck('permission_id')->toArray();
         
        $permission   =    Permission::orderBy('permission_group')->get();
        
        foreach ($permission as $object) {
            $szn[] = $object->toArray();
        }
        
        $permissions = array_group_by($szn, "permission_group");
        foreach ($permissions as $key => $value) {
            $final = [ 
                'manage' => null,
                'own' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
            ];
            foreach ($value as $val) {
                $final[explode('_', $val['name'])[0]] = $val;
            }
            $permissions[$key] = $final;
        }

        $data['permissions']  = $permissions;

        return view('admin.role.role_edit', $data);
    }


    public function update(Request $request)
    {
        if (!empty($request->permissions)) {
            $data = [ 
                'type'    => 'fail',
                'message' => __('Something went wrong, please try again.')
            ];
    
            $rules = array(
                'name'         => 'required|unique:roles,name,'.$request->id,
                'display_name' => 'required',
                'description'  => 'required'
                );
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                // Form calling with Errors and Input values
                return back()->withErrors($validator)->withInput(); 
            } else {
                $role['name'] = $request->name;
                $role['display_name'] = $request->display_name;
                $role['description'] = $request->description;
                //update
                $roleToUpdate   = Role::find($request->id);
                $roleToUpdate->name = $role['name'];
                $roleToUpdate->display_name = $role['display_name'];
                $roleToUpdate->description = $role['description'];
                $roleToUpdate->save();
    
                $stored_permissions = PermissionRole::where('role_id',$request->id)->pluck('permission_id')->toArray();
                    
                $permission = isset($request->permissions) ? $request->permissions : [];
                if (!empty($stored_permissions)) {
                    foreach ($stored_permissions as $key => $value) {
                        if (!in_array($value, $permission)) {
                            $permissionRoleToDelete  = PermissionRole:: where(['permission_id' => $value, 'role_id' => $request->id]);
                            $permissionRoleToDelete->delete();
                        }
                         
                    }
                }
                if (!empty($permission)) {
                    foreach ($permission as $key => $value) {
                        if (!in_array($value, $stored_permissions)) {
                                
                            //insert
                            $newPermissionRoleToInsert  = new PermissionRole();
                            $newPermissionRoleToInsert->permission_id   = $value;
                            $newPermissionRoleToInsert->role_id         = $request->id;
                            $newPermissionRoleToInsert->save();
                        }
                    }
                }
                $data = [ 
                    'type'    => 'success',
                    'message' => __('Successfully updated')
                ];
            }
            Cache::forget('gb-roles');
            Cache::forget('gb-role_users');
            Cache::forget('gb-permission_roles');
            \Session::flash($data['type'],$data['message']);
            return redirect('role/list');

        } else {
            Session::flash('fail', __('Please give at least one permission'));
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $id = $request->id;
        $roleToDelete= Role::find($id);
        if ($id == 1 && strtolower($roleToDelete->name) === 'admin') {
            Session::flash('fail', __('Admin role can not be deleted.'));
            return redirect()->intended('role/list');
        }

        if (!empty($roleToDelete)) {
            $users = User::where('role_id', $id)->update(['role_id' => null]);
            $roleToDelete->delete();
            $permissionRoleToDelete = PermissionRole::where(['role_id'=>$id]);
            $permissionRoleToDelete->delete();

            $data = [ 
                    'type'    => 'success',
                    'message' => __('Deleted Successfully!')
                ];
        }
        Cache::forget('gb-roles');
        Cache::forget('gb-role_users');
        Cache::forget('gb-permission_roles');
        Session::flash($data['type'], $data['message']);
        return redirect()->intended('role/list');

    }

    public function validRoleName(Request $request)
    {
        $roleName = $_GET['name'];
        if (isset($_GET['role_id'])) {
            $id = $_GET['role_id'];
            $v  = Role::where('id', '!=', $id)
                       ->where('name', $roleName)->exists();
        } else {
            $v = Role::where('name', $roleName)
                      ->exists();
        }
        
        if ($v === true) {
            echo json_encode(__('That Role Name is already taken.'));
        } else {
            return 'true';
        }
    }
}