<?php
/**
 * @package KnowledgeBaseDataTable
 * @author tehcvillage <support@techvill.org>
 * @contributor Sakawat Hossain Rony <[sakawat.techvill@gmail.com]>
 * @created 19-06-2021
 */
namespace App\DataTables;

use App\Models\{
    Group,
    KnowledgeBase,
};
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;

class KnowledgeBaseDataTable extends DataTable
{
    public function ajax()
    {
        $knowledges = $this->query();
        return datatables()
            ->of($knowledges)
            ->addColumn('article_name', function ($knowledges) {
                return '<a href="' . url("knowledge-base/view/" . $knowledges->slug) . '">' . mb_substr($knowledges->subject, 0, 150) . '</a>';
            })
            ->addColumn('group', function ($knowledges) {
                $group = Group::getAll()->where('id', $knowledges->group_id)->first();
                return $group->name;
            })
            ->addColumn('status', function ($knowledges) {
                return ucfirst($knowledges->status);
            })
            ->addColumn('date_publish', function ($knowledges) {
                return !empty($knowledges->publish_date) ? formatDate($knowledges->publish_date) : __('Not Yet');
            })
            ->addColumn('action', function ($knowledges) {
                $delete = '';
                $edit = Helpers::has_permission(Auth::user()->id, 'edit_knowledge_base') ? '<a href="' . url('knowledge-base/edit/' . $knowledges->id) . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                if (Helpers::has_permission(Auth::user()->id, 'delete_knowledge_base')) {
                    $delete = '<form method="post" action="' . url('knowledge-base/delete') . '" accept-charset="UTF-8" class="display_inline" id="delete-item-' . $knowledges->id . '">
                        ' . csrf_field() . '
                        <input type="hidden" name="action_name" value="delete_knowledge_base">
                        <input type="hidden" name="knowledge_id" value="' . $knowledges->id . '">
                        <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="' . $knowledges->id . '" data-target="#theModal" data-label="Delete" data-title="' . __('Delete Knowledge Base') . '" data-message="' . __('Are you sure to delete this Knowledge Base?') . '">
                        <i class="feather icon-trash-2"></i>
                        </button>
                        </form>';
                }
                return $edit . $delete;
            })
            ->rawColumns(['article_name', 'group', 'status', 'date_publish', 'action'])
            ->make(true);
    }

    public function query()
    {
        $knowledges = KnowledgeBase::getAll();
        $group      = isset($_GET['group_id']) && !empty($_GET['group_id']) ? $_GET['group_id'] : null;
        $from       = isset($_GET['from']) && !empty($_GET['from']) ? DbDateFormat($_GET['from']) : null;
        $to         = isset($_GET['to']) && !empty($_GET['to']) ? DbDateFormat($_GET['to']) : null;
        $status     = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;
        if (!empty($group)) {
            $knowledges = $knowledges->where('group_id', $group);
        }
        if (!empty($status)) {
            $knowledges = $knowledges->where('status', $status);
        }
        if (!empty($from)) {
            $knowledges = $knowledges->where('publish_date', '>=', $from);
        }
        if (!empty($to)) {
            $knowledges = $knowledges->where('publish_date', '<=', $to);
        }
        return $this->applyScopes($knowledges);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'article_name', 'name' => 'subject', 'title' => __('Article Name')])
            ->addColumn(['data' => 'group', 'name' => 'group_id', 'title' => __('Group')])
            ->addColumn(['data' => 'status', 'name' => 'status', 'title' => __('Status')])
            ->addColumn(['data' => 'date_publish', 'name' => 'publish_date', 'title' => __('Date Published')])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                    'url' => url('/resources/lang/' . config('app.locale') . '.json'),
                ],
                'order' => [0, 'desc']
            ]);
    }
}
