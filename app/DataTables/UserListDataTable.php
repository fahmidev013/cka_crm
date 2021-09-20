<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use App\Models\{
    User,
    File
};
use DB;
use Auth;
use Helpers;
use Session;

class UserListDataTable extends DataTable
{
    public function ajax()
    {
        $users = $this->query();
        return datatables()
            ->of($users)
            ->addColumn('action', function ($users) {
                $delete = $edit = '';
                if ($users->id != Auth::user()->id && $users->id != 1) {
                    $edit = Helpers::has_permission(Auth::user()->id, 'edit_team_member') ? '<a href="'. url('user/team-member-profile/'. $users->id) .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
                    if (Helpers::has_permission(Auth::user()->id, 'delete_team_member')) {
                        $delete= '<form method="post" action="'. url('delete-user/'. $users->id) .'" id="delete-user-'. $users->id .'" accept-charset="UTF-8" class="display_inline">
                        '. csrf_field() .'
                        <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='.$users->id.' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete user') . '" data-message="' . __('Are you sure to delete this user?') . '">
                        <i class="feather icon-trash-2"></i>
                        </button>
                        </form>';
                    }
                }
                return $edit . $delete;
            })
            ->addColumn('img', function ($users) {
                if (isset($users->avatarFile->file_name)  && !empty($users->avatarFile->file_name)) {
                    if (file_exists('public/uploads/user/thumbnail/' . $users->avatarFile->file_name)) {
                        $img = '<img src="'. url("public/uploads/user/thumbnail/". $users->avatarFile->file_name) .'" alt="" width="50" height="50">';
                    } else {
                        $img = '<img src="'. url("public/dist/img/avatar.jpg") .'" alt="" width="50" height="50">';
                    }
                } else {
                        $img = '<img src="'. url("public/dist/img/avatar.jpg") .'" alt="" width="50" height="50">';
                }
                return $img;
            })
            ->addColumn('status', function ($users) {
                $status = '';
                if ($users->id != 1) {
                    if ($users->is_active == 1) {
                        $status = '<div class="switch d-inline m-r-10">
                                    <input type="checkbox" class="status" id="switch-'. $users->id .'" data-id="'. $users->id .'" checked="true">
                                    <label for="switch-'. $users->id .'" class="cr"></label>
                                   </div>';
                    } else {
                        $status = '<div class="switch d-inline m-r-10">
                                    <input class="status" type="checkbox" id="switch-'. $users->id .'" data-id="'. $users->id .'">
                                    <label for="switch-'. $users->id .'" class="cr"></label>
                                   </div>';
                    }
                }
                return $status;
            })
            ->addColumn('full_name', function ($users) {
                if (Helpers::has_permission(Auth::user()->id, 'edit_team_member')) {
                    return '<a href="'. url('user/team-member-profile/'. $users->id) .'">'. $users->full_name .'</a><br>';
                }
                return  $users->full_name;
            })
            ->addColumn('email', function ($users) {
                if (mb_strlen($users->email) > 20) {
                    $user_mail = mb_substr($users->email, 0, 20) . "..";
                } else {
                    $user_mail = $users->email;
                }
                $full_mail = $users->email;
                return mb_strlen($full_mail) > 20 ? '<span data-toggle="tooltip" data-placement="right" data-original-title="'. $full_mail .'">'. $user_mail .'</span>' : $user_mail;
            })
            ->addColumn('created_at', function ($users) {
                return timeZoneformatDate($users->created_at) .'<br>'. timeZonegetTime($users->created_at);
            })
            ->addColumn('role', function ($users) {
                return !empty($users->role) ? $users->role->display_name : '';
            })
            ->rawColumns(['action', 'img', 'full_name', 'email', 'status', 'created_at', 'role'])
            ->make(true);
    }

    public function query()
    {
        $user = isset($_GET['user']) ? $_GET['user'] : null;
        $users = User::with(['role', 'avatarFile:object_id,object_type,file_name'])->select();
        if (!empty($user) && $user == "inactive") {
            $users->where('is_active', 0);
        } else if (!empty($user) && $user == "total") {
            $users;
        } else {
            $users->where('is_active', 1);
        }

        return $this->applyScopes($users);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'id', "visible" => false])
            ->addColumn(['data' => 'img', 'name' => 'img', 'title' => __('Picture'), 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'full_name', 'name' => 'users.full_name', 'title' => __('Name')])
            ->addColumn(['data' => 'email', 'name' => 'users.email', 'title' => __('Email')])
            ->addColumn(['data' => 'role', 'name' => 'role.display_name', 'title' => __('Role')])
            ->addColumn(['data' => 'phone', 'name' => 'phone', 'title' => __('Phone')])
             ->addColumn(['data'=> 'status', 'name' => 'status', 'title' => __('Status'), 'orderable' => false, 'searchable' => false])
             ->addColumn(['data' => 'created_at', 'name' => 'users.created_at', 'title' => __('Created at')])
            ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [0, 'DESC']
            ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'customers_' . time();
    }
}
