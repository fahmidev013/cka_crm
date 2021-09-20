<?php

namespace App\Http\Controllers;
use PDF;
use App\DataTables\CustomerNoteListDataTable;
use App\Http\Start\Helpers;
use App\Models\Note;
use App\Models\Customer;
use App\Models\Preference;
use App\Models\File;
use Illuminate\Http\Request;
use App\Exports\customerNoteExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class NoteController extends Controller
{
  public function __construct(CustomerNoteListDataTable $customerNoteListDataTable)
  {
    $this->customerNoteListDataTable = $customerNoteListDataTable;
  }

  public function index($id)
  {
      if (isset($_GET['customer'])) {
          $data['menu'] = 'relationship';
          $data['sub_menu'] = 'customer';
      } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
      } else {
          $data['menu']       = 'project';
      }
      $data['header'] = 'project';
      $data['page_title'] = __('Notes of Project');
      $data['navbar'] = 'note';
      $data['project'] = DB::table('projects')
                        ->select('projects.id','projects.name','ps.name as status_name')
                        ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
                        ->where('projects.id',$id)->first();
      $data['notes'] = DB::table('notes')
                        ->leftJoin('users', 'notes.user_id', 'users.id')
                        ->where(['notes.related_to_id' => $id, 'notes.related_to_type' => 'project'])
                        ->select('notes.id', 'notes.user_id', 'notes.subject', 'notes.content', 'notes.created_at', 'notes.related_to_id', 'users.full_name')
                        ->get();
      if (empty($data['project']) || empty($data['notes'])) {
        \Session::flash('fail', __('The data you are trying to access is not found.'));
        return redirect()->back();
      }
      return view('admin.project.note.list', $data);
  }

  public function edit(Request $request, $id)
  {
    $note = DB::table('notes')
                ->leftJoin('users', 'notes.related_to_id', 'users.id')
                ->where('notes.id', $id)
                ->select('notes.id', 'users.full_name', 'notes.subject', 'notes.content', 'notes.created_at', 'notes.related_to_id')
                ->first();
                
    return json_encode(['id' => $note->id, 'project_id' => $note->related_to_id, 'subject' => $note->subject, 'content' => $note->content, 'created_at' => $note->created_at]);
  }

  public function customerNotes($id)
  {
      $data['menu']       = 'relationship';
      $data['sub_menu']   = 'customer';
      $data['page_title'] = __('Customer Notes');
      $data['navbar']     = 'note';
      $data['customerData']   = Customer::find($id);

      $data['from'] = isset($_GET['from']) ? $_GET['from'] : null ;
      $data['to'] = isset($_GET['to']) ? $_GET['to'] : null ;

      $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        
      return $this->customerNoteListDataTable->with('row_per_page', $row_per_page)->with('customer_id',$id)->render('admin.customer.customer_notes', $data);
  }

  public function getNoteData(Request $request) {
    $note = Note::where('id', $request->noteId)->first();
    return json_encode($note);
  }

  public function store(Request $request) 
  {
    $this->validate($request, [
      'content' => 'required',
      'subject' => 'required'
    ]);
    $id = $request->id;
    $subject = stripBeforeSave($request->subject);
    $content    = $request->content;
    if ($content != '') {
      try {
        DB::beginTransaction();
        $note = new Note;
        $note->related_to_id = $id;
        $note->user_id = Auth::user()->id;
        $note->related_to_type = 'project';
        $note->subject = $subject;
        $note->content = $content;
        $note->save();
        DB::commit(); 
      } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(__('Something went wrong, please try again.'));
      } 
    }
    \Session::flash('success', __('Successfully updated'));
    return back();
  }

  public function storeCustomerNotes(Request $request){
    $customer_id = $request->customer_id;
    $subject = stripBeforeSave($request->subject);
    $content    = stripBeforeSave($request->note);
    
    if (!empty($subject) && !empty($content)) {
       $id = DB::table('notes')->insertGetId(['related_to_id'=> $customer_id, 'user_id'=> Auth::user()->id, 'related_to_type'=> 'customer','subject'=> $subject,'content'=> $content,'created_at'=>date('Y-m-d H:i:s') ]);

       \Session::flash('success', __('Successfully Saved'));   
      return redirect()->back();
    } else {
      \Session::flash('fail', __('Please enter valid data'));   
      return redirect()->back();
    }

      
}

  public function updateCustomerNotes(Request $request){
    $customer_id = $request->customer_id;
    $subject    = $request->subject;
    $content    = $request->note;
    $note_id    = $request->note_id;

    if ($content !='') {
      $updateNote = Note::find($note_id);
      $updateNote->subject = $subject;
      $updateNote->content = $content;
      $updateNote->save();
    }

    \Session::flash('success', __('Successfully updated'));   
      return redirect()->back();
  }

  public function deleteCustomerNote(Request $request){
    $note = Note::find($request->note_id);
    $note->delete();
    \Session::flash('success', __('Deleted Successfully.'));   
    return redirect()->back();
  }

  public function update(Request $request){
    $this->validate($request, [
        'content' => 'required',
        'subject' => 'required'
    ]);
    $id    = $request->id;
    $subject    = stripBeforeSave($request->subject);
    $content    = $request->content;
    if ($content != '') {
      try {
        DB::beginTransaction();
        $note = Note::where('id', $id)->update(['subject' => $subject, 'content' => $content]);
        DB::commit(); 
      } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(__('Something went wrong, please try again.'));
      } 
    }
    \Session::flash('success', __('Successfully updated'));
    return back();
  }

  public function destroy(Request $request)
  {
    $note = Note::find($request->id);
    $note->delete();
    \Session::flash('success', __('Deleted Successfully.'));
    return redirect()->back();
  }

  public function noteCsv()
  {
      return Excel::download(new customerNoteExport(), 'customer_notes'.time().'.csv');
  }
  public function notePdf()
  {
      $to               = isset($_GET['to']) ? $_GET['to'] : null ;
      $from             = isset($_GET['from']) ? $_GET['from'] : null ;
      $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
      $data['customers'] = Customer::where('id', $customer)->first();
      $data['noteList']  = (new Note)->getAllNoteByCustomerCSV($from, $to, $customer)->get();
      
      if ($from && $to) {
        $data['date_range'] = formatDate($from) . __('To') . formatDate($to);  
      } else {
        $data['date_range'] = __('No Date Selected');

      }

      return printPDF($data, 'notes_list_' . time() . '.pdf', 'admin.customer.customer_notes_pdf', view('admin.customer.customer_notes_pdf', $data), 'pdf', 'domPdf');
  }
}
