<?php
namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Lead;
use App\Models\TagAssign;
use App\Models\Tag;
use DB;
use Auth;
use Helpers;
use Session;

class LeadListDataTable extends DataTable
{
    public function ajax()
    {
        $leads = $this->query();
        return datatables()
            ->of($leads)
            ->addColumn('name', function ($leads) {
                return '<a href="'. url("lead/view/". $leads->id) .'">'. $leads->first_name . ' ' . $leads->last_name .'</a>' ;
            })
            ->addColumn('company', function ($leads) {
                return $leads->company;
            })
            ->addColumn('assigned', function ($leads) {
                if (!($leads->user)) {
                    return "";
                } else if ($leads->user) {
                    $realName = mb_strlen($leads->user->full_name) > 15 ? mb_substr($leads->user->full_name, 0, 13) .'..' : $leads->user->full_name;

                    if (Helpers::has_permission(Auth::user()->id, 'edit_team_member')) {
                        $assigned = '<a href="'. url('user/team-member-profile/'. $leads->user->id) .'">'. $realName .'</a><br>';
                    } else {
                        $assigned = $realName;
                    }
                    return $assigned;
                }
            })
            ->addColumn('status', function ($leads) {
                $status = "";
                if (!empty($leads->leadStatus)) {
                    $statusHolder = isset($leads->leadStatus->name) ? $leads->leadStatus->name : '';
                    $colorHolder = isset($leads->leadStatus->color) ? $leads->leadStatus->color : '';
                    $status = "<div class='badge badge-primary lead-status' style='background-color:" . $colorHolder .";'><center><strong>". $statusHolder ."<strong></center></div>";
                }
                return $status;
            })
            ->addColumn('source', function ($leads) {
                $source = "";
                if (!empty($leads->leadSource)) {
                    $source = isset($leads->leadSource->name) ? $leads->leadSource->name : '';
                }
                return $source;
            })
            ->addColumn('tag', function ($leads) {
                    $tagsInResult = TagAssign::with(['tag'])
                                    ->where(['reference_id'=> $leads->id, 'tag_type'=> 'lead'])
                                    ->get();
                    $str2 = "";
                    $divStr = "<div class='col-md-12'>";
                    $final_str =  "<div class='tags-labels'>";
                    foreach ($tagsInResult as $tagIn) {
                        $tagName = isset($tagIn->tag->name) && !empty($tagIn->tag->name) ? $tagIn->tag->name : '';
                        $str = '<span class="label label-tag"><span class="tag-in-result">' . $tagName . '</span></span>';
                        if ($str2 == "") {
                            $str2 .= $str;
                        } else {
                            $str2 .= '<span class="hide"></span>'. $str;
                        }
                    }

                $final_str2 = $divStr . $final_str . $str2 . "</div>". "</div>";
                return $final_str2;
            })
            ->addColumn('created_at', function ($leads) {
                $startDate = timeZoneformatDate($leads->created_at);
                $startTime = timeZonegetTime($leads->created_at);
                return $startDate .'</br>'. $startTime;
            })
            ->addColumn('action', function ($leads) {
                $view = Helpers::has_permission(Auth::user()->id, 'edit_lead') ? '<a href="'. url('lead/view/'. $leads->id) .'" class="btn btn-xs btn-primary"><i class="feather icon-eye"></i></a>&nbsp;' : '';
                if ($leads->status_id == 1) {
                    $edit   = "";
                    $delete = "";
                } else {
                    $edit = Helpers::has_permission(Auth::user()->id, 'edit_lead') ? '<a href="'. url('lead/edit/'. $leads->id) .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                    if (Helpers::has_permission(Auth::user()->id, 'delete_lead')){
                        $delete = '<form method="post" action="'. url('lead/delete') .'" accept-charset="UTF-8" class="display_inline" id="delete-item-'. $leads->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="action_name" value="delete_lead">
                        <input type="hidden" name="lead_id" value="'. $leads->id .'">
                        <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $leads->id .'" data-target="#theModal" data-label="Delete" data-title="'. __('Delete lead') .'" data-message="'. __('Are you sure to delete this lead?') .'">
                        <i class="feather icon-trash-2"></i>
                        </button>
                        </form>';
                    }
                }
                return $edit . $delete;
            })
            ->rawColumns(['name', 'created_at', 'action', 'status', 'company', 'assigned', 'tag'])
            ->make(true);
    }

    public function query()
    {
        $leads = Lead::all();

        if (isset($_GET['btn'])) {
            if ($_GET['from'] && $_GET['to']) {
                $from   = DbDateFormat($_GET['from']);
                $to     = DbDateFormat($_GET['to']);
            }
            $assignee   = $_GET['assignee'];
            $leadStatus = isset($_GET['leadStatus']) ? $_GET['leadStatus'] : null;
            $leadSource = $_GET['leadSource'];
        }
        if (isset($from) && $from != null) {
            $leads  = $leads->where('created_at', '>=', $from .' 00:00:00');
        }
        if (isset($to) && $to != null) {
            $leads  = $leads->where('created_at', '<=', $to .' 23:59:59');
        }
        if (isset($assignee) && $assignee) {
            $leads  = $leads->where('assignee_id', $assignee);
        }
        if (isset($leadStatus) && $leadStatus) {
            $leads  = $leads->whereIn('lead_status_id', $leadStatus);
        }
        if (isset($leadSource) && $leadSource) {
            $leads  = $leads->where('lead_source_id', $leadSource);
        }

        return $this->applyScopes($leads);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'name', 'name' => 'first_name', 'title' => __('Lead Name')])
            ->addColumn(['data' => 'company', 'name' => 'company', 'title' => __('Lead Company')])
            ->addColumn(['data' => 'assigned', 'name' => 'assigned', 'title' => __('Assigned')])
            ->addColumn(['data' => 'status', 'name' => 'status', 'title' => __('Lead Status')])
            ->addColumn(['data' => 'source', 'name' => 'source', 'title' => __('Lead Source')])
            ->addColumn(['data' => 'tag', 'name' => 'tag', 'title' => __('Tags')])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => __('Created At')])
            ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'. config('app.locale') .'.json'),
                    ],
                'order' => [0, 'desc']
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
        return 'leads_' . time();
    }
}
