<?php
namespace App\DataTables;
use App\Models\Note;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;
class CustomerNoteListDataTable extends DataTable
{
    public function ajax()
    {
        $notes   = $this->query();
        return datatables()
            ->of($notes)
            ->addColumn('action', function ($notes) {
                $edit = $delete = '';
                $edit = (Helpers::has_permission(Auth::user()->id, 'edit_customer_note')) ? '<button class="btn btn-xs btn-primary notecontent" data-toggle="modal" data-target="#editNote" id="edit-customer-note" data-note_id="' . $notes->id . '"><i class="feather icon-edit"></i></button>&nbsp;' : '';

                $delete = (Helpers::has_permission(Auth::user()->id, 'delete_customer_note')) ? '
            <form method="post" action="' . url("customer/note-delete") . '" id="delete-notes-'. $notes->id .'" class="display_inline_block">
            ' . csrf_field() . '
            <input type="hidden" name="note_id" value="'. $notes->id .'">
            <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='. $notes->id .' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete Note') . '" data-message="' . __('Are you sure to delete this note?') . '">
                            <i class="feather icon-trash-2"></i> 
                        </button>
            </form>
            ' : '';
            return $edit . $delete;
            })

            ->addColumn('created_at', function ($notes) {
                return timeZoneformatDate($notes->created_at) . '</br>' . timeZonegetTime($notes->created_at);
            })

            ->addColumn('subject', function ($notes) {
                return $notes->subject;
            })

            ->addColumn('content', function ($notes) {
                return $notes->content;
            })

            ->rawColumns(['action', 'created_at', 'subject', 'content'])

            ->make(true);
    }

    public function query()
    {
        $id = $this->customer_id;
        $from = isset($_GET['from']) ? $_GET['from'] : NULL;
        $to = isset($_GET['to']) ? $_GET['to'] : NULL;
        $customerNote = (new Note())->getAllNoteByCustomer($from, $to, $id);

        return $this->applyScopes($customerNote->get());
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'subject', 'name' => 'subject', 'title' => __('Subject') ])

            ->addColumn(['data' => 'content', 'name' => 'content', 'title' => __('Note') ])

            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => __('Created At') ])

            ->addColumn(['data' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

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

    protected function filename()
    {
        return 'customers_' . time();
    }
}
