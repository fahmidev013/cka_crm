<?php
namespace App\DataTables;
use App\Http\Start\Helpers;
use App\Models\Project;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Services\DataTable;

class ProjectFileDataTable extends DataTable{
    public function ajax()
    {
        $projects   = $this->query();
        return datatables()
            ->of($projects)
            ->addColumn('action', function ($projects) {
                $download = $delete = '';

                    $download = '<a href="' . url("files/download/".  base64_encode($projects->id)) . '"  download="'. $projects->original_file_name .'" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-download"></i></a>';

                    $delete = (Helpers::has_permission(Auth::user()->id, 'delete_project_file')) ? '
                   <form method="post" action="' . url("project/files/delete") . '" class="display_inline" id="delete-item-'.$projects->id.'">
                ' . csrf_field() . '
                <input type="hidden" name="id" value="'. $projects->id .'">
                <input type="hidden" name="project_id" value="'. $projects->object_id .'">
                <input type="hidden" name="filePath" value="public/uploads/project_files">
                <input type="hidden" name="deleteFromList" value="List">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $projects->id .'" data-target="#theModal" data-label="Delete" data-title="' . __('Delete project files') . '" data-message="' . __('Are you sure to delete this file?') . '">
                                    <i class="feather icon-trash-2"></i> </button>
                </form>
                ' : '';
                
                return $download.$delete;
            })
            ->addColumn('uploadedBy', function ($projects) {
                if (!empty($projects->user)) {
                    $uploadedBy =  $projects->user->full_name;
                    $uploadedBy = "<a href='" . url("user/team-member-profile/". $projects->user->id). "'>". $uploadedBy ."</a>" ;
                } else {
                    $uploadedBy = '';
                }
              
                return $uploadedBy;
            })

             ->addColumn('created_at', function ($projects) {
                return formatDate($projects->created_at) ."&nbsp;".  date_format(date_create($projects->created_at), 'g:i A');
            })
           
            ->rawcolumns(['uploadedBy', 'action', 'created_at'])
            ->make(true);
        
    }

    public function query()
    {
        $id = $this->project_id;
        $projects = File::with('user')->where(['object_type' => "PROJECT", 'object_id' => $id]);
        return $this->applyScopes($projects);
    }

    public function html()
    {
        return $this->builder()
            
            ->addColumn(['data' => 'original_file_name', 'name' => 'original_file_name', 'title' => __('File name') ])

            ->addColumn(['data' => 'uploadedBy', 'name' => 'user.full_name', 'title' => __('Uploaded by') ])

            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => __('Date') ])
            
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [2, 'desc']
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
}