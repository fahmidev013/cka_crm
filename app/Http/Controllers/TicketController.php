<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectTicketDataTable;
use App\DataTables\TicketDataTable;
use App\DataTables\CustomerPanelTicketListDataTable;
use App\Exports\projectTicketsExport;
use App\Exports\customerPanelTicketsExport;
use App\Http\Controllers\EmailController;
use App\Models\Preference;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Customer;
use App\Models\File;
use App\Models\Department;
use App\Models\TicketStatus;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDF;
use DB;
use Excel;
use Validator;
use App\Models\Note;

class TicketController extends Controller
{
    public function __construct(Request $request, Ticket $Ticket, TicketDataTable $TicketDataTable, EmailController $email, ProjectTicketDataTable $projectTicketDataTable)
    {
        $this->preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $this->request                = $request;
        $this->Ticket                 = $Ticket;
        $this->TicketDataTable        = $TicketDataTable;
        $this->email                  = $email;
        $this->projectTicketDataTable = $projectTicketDataTable;
    }

    public function index()
    {
        $data['menu']      = 'ticket';
        $data['header']    = 'ticket';
        $data['page_title'] = __('Tickets');

        $data['status']    = DB::table('ticket_statuses')->select('id', 'name')->get();
        $data['projects']  = DB::table('projects')->where('project_status_id', '!=', 6)->select('id', 'name')->get();
        $data['departments'] = Department::get();
        $data['assignees'] = Ticket::whereNotNull('assigned_member_id')
                            ->with('assignedMember:id,full_name')
                            ->distinct()->get(['assigned_member_id']);
        $data['from']        = $from        = isset($_GET['from'])     ? $_GET['from']     : null;
        $data['to']          = $to          = isset($_GET['to'])       ? $_GET['to']       : null;
        $data['allstatus']   = $allstatus   = isset($_GET['status'])   ? $_GET['status']   : '';
        $data['allproject']  = $allproject  = isset($_GET['project'])  ? $_GET['project']  : '';
        $data['alldepartment'] = $alldepartment = isset($_GET['department_id']) ? $_GET['department_id'] : null ;
        $data['allassignee'] = $allassignee = isset($_GET['assignee']) ? $_GET['assignee'] : null ;
        $data['summary']     = $summary = $this->Ticket->getTicketSummary($from, $to, $allstatus, $allproject, $alldepartment, null, $allassignee);

        if ((isset($from) && !empty($from)) || (isset($to) && !empty($to)) || (isset($allproject) && !empty($allproject)) || (isset($alldepartment) && !empty($alldepartment))) {
            $data['exceptClickedStatus'] = $exceptClickedStatus = $this->Ticket->getExceptClickedStatus($allstatus);
            if (!empty($data['exceptClickedStatus'])) {
                foreach ($summary as $key => $summ) {
                    foreach ($exceptClickedStatus as $key => $exceptClickedSts) {
                        if ($exceptClickedSts->name == $summ->name) {
                            $summ->total_status = $exceptClickedSts->total_status;
                        }
                    }
                }

            }
        } else {
            $data['filteredStatus'] = $filteredStatus = $this->Ticket->getFilteredStatus(['from' => $from, 'to' => $to, 'allstatus' => $allstatus, 'allproject' => $allproject, 'alldepartment' => $alldepartment, 'allassignee' => $allassignee]);
            if (! empty($data['filteredStatus'])) {
                foreach ($summary as $key => $summ) {
                    foreach ($filteredStatus as $key => $filtered) {
                        if ($filtered->name == $summ->name) {
                            $summ->total_status = $filtered->total_status;
                        }
                    }
                }
            }
        }

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        return $this->TicketDataTable->with('row_per_page', $row_per_page)->render('admin.ticket.list', $data);
    }

    public function create()
    {
        $data['projectPanel'] = 0;
        $data['menu']   = 'ticket';
        $data['header'] = 'ticket';
        $data['page_title'] = __('Create Ticket of project');
        $data['priorities']   = DB::table('priorities')->get();
        $data['assignees']    = DB::table('users')->where('is_active', 1)->get();
        $data['ticketStatus'] = DB::table('ticket_statuses')->get();
        $data['departments']  = DB::table('departments')->get();
        $data['countries']    = DB::table('countries')->get();
        $data['projects']     = Project::where('project_status_id', '!=', 6)->get();
        $data['customers']    = DB::table('customers')->where('is_active', 1)->get();
        if (!empty($_GET['project_id'])) {
            $data['getProject'] = $_GET['project_id'];
            $data['getCustomer'] = Project::where('id', $_GET['project_id'])->first(['customer_id']);
            if (is_null($data['getCustomer'])) {
                Session::flash('fail', __('Project not found'));
                return redirect()->back();
            }
        }

        return view('admin.ticket.add', $data);
    }

    public function checkInhouse(Request $request)
    {
        if (!empty($request->id)) {
            $project = Project::find($request->id);
            return ($project->project_type == 'in_house') ? 'yes' : 'no';
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject'       => 'required',
            'assign_id'     => 'required',
            'priority_id'   => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            $flag = $this->checkInhouse($request);
            if ($flag  == 'no') {
                if (! (isset($request->email) && ! empty($request->email))) {
                    $validator->errors()->add('email', __('Please add the customer email.'));
                }
            }
        });

        if ($validator->fails()) {
            return redirect('ticket/add')->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $data['customer_id']        = !empty($request->customerId) ? $request->customerId : $request->customer_id;
            $data['email']              = isset($request->email) ? $request->email : null;
            $data['name']               = $request->to;
            $data['department_id']      = $department_id      = $request->department_id;
            $data['priority_id']        = $request->priority_id;
            $data['ticket_status_id']   = $request->status_id;
            $data['ticket_key']         = 'TIC-' . uniqid();
            $data['subject']            = stripBeforeSave($request->subject);
            $data['user_id']            = Auth::user()->id;
            $data['date']               = date('Y-m-d H:i:s');
            $data['project_id']         = !empty($request->projectId) ?  $request->projectId : $request->project_id;
            $data['last_reply']         = date('Y-m-d H:i:s');
            $data['assigned_member_id'] = $request->assign_id;
            $id = DB::table('tickets')->insertGetId($data);

            if (isset($id) && $id) {
                $replyData['ticket_id'] = $id;
                $replyData['user_id']   = $data['user_id'];
                $replyData['message']   = $request->message;
                $replyData['date']      = $data['date'];
                $reply_id               = DB::table('ticket_replies')->insertGetId($replyData);
            }
            # region store files
            if (isset($reply_id) && !empty($reply_id)) {
                if (!empty($request->attachments)) {
                    $path = createDirectory("public/uploads/tickets");
                    $replyFiles = (new File)->store($request->attachments, $path, 'Ticket Reply', $reply_id, ['isUploaded' => true, 'isOriginalNameRequired' => true, 'resize' => false]);
                }
            }
            # end region
            $ticketDetails = (new Ticket())->getAllTicketDetailsById($id);
            if ($request->customer_id) {
                $emailResponse = $this->mailToCustomer($ticketDetails, $request->customer_id, isset($replyFiles) ? $replyFiles : '');
                if ($emailResponse['status'] == false) {
                     \DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                 }
            }
            if ($request->assign_id) {
                    $emailResponse = $this->mailToAssignee($ticketDetails, $request->assign_id, $request->customer_id, isset($replyFiles) ? $replyFiles : '');
                    if ($emailResponse['status'] == false) {
                         \DB::rollBack();
                        return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                     }
            }
            DB::commit();

            Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('ticket/reply/' . base64_encode($id));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $data['menu']   = 'ticket';
        $data['page_title'] = __('Edit Ticket');
        $data['departments']   = DB::table('departments')->get();
        $data['projects']      = DB::table('projects')->where('project_status_id', '!=', 6)->get();
        $data['priorities']    = DB::table('priorities')->get();
        $data['assignees']     = DB::table('users')->where('is_active', 1)->get();
        $data['ticketStatus']  = DB::table('ticket_statuses')->get();
        $data['customers']     = DB::table('customers')->where('is_active', 1)->get();
        $data['ticketDetails'] = (new Ticket())->getAllTicketDetailsById($id);
        if (empty($data['ticketDetails'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        return view('admin.ticket.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'subject'     => 'required',
            'assign_id'   => 'required',
            'priority_id' => 'required',
        ]);
        \DB::beginTransaction();
        $data['customer_id']        = $request->customer_id;
        $data['email']              = $request->email;
        $data['name']               = $request->to;
        $data['department_id']      = $request->department_id;
        $data['priority_id']        = $request->priority_id;
        $data['project_id']         = $request->project_id;
        $data['ticket_status_id']   = $request->status_id;
        $data['ticket_key']         = $request->ticket_key;
        $data['subject']            = stripBeforeSave($request->subject);
        $data['assigned_member_id'] = $request->assign_id;

        DB::table('tickets')->where('id', $request->ticket_id)->update($data);
        $ticketDetails = (new Ticket())->getAllTicketDetailsById($request->ticket_id);

        if ($request->customer_id != $request->ticket_previous_customer_id) {
            $emailResponse = $this->mailToCustomer($ticketDetails, $request->customer_id);
            if ($emailResponse['status'] == false) {
                 \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
            }
        }

        if ($request->ticket_previous_assigne != $request->assign_id) {
            if ($request->assign_id) {
                if ($request->assign_id == 1) {
                   $emailResponse = $this->mailToAssignee($ticketDetails, 1, null);
                } else {
                    $emailResponse = $this->mailToAssignee($ticketDetails, 1, null);
                    $emailResponse = $this->mailToAssignee($ticketDetails, $request->assign_id, $request->customer_id);
                }
                if ($emailResponse['status'] == false) {
                     \DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                }
            }
        }

        \DB::commit();
        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('ticket/reply/' . base64_encode($request->ticket_id));
    }

    public function mailToAssignee($ticketDetails, $assigne_id, $customerId, $attachments = null)
    {
        $lang       = $this->preference['dflt_lang'];
        $email      = EmailTemplate::getAll()->where('template_id', 7)->where('language_short_name', $lang)->first();

        $assignData = DB::table('users')->where('id', $assigne_id)->first();
        $subject = $email->subject;
        $message = $email->body;
        $ticket_reply = url('ticket/reply/' . base64_encode($ticketDetails->id));
        $subject      = str_replace('{ticket_subject}', $ticketDetails->subject, $subject);
        $subject      = str_replace('{ticket_no}', $ticketDetails->id, $subject);

        $message = str_replace('{assignee_name}', $assignData->full_name, $message);
        $message = str_replace('{ticket_message}', $ticketDetails->ticketReplies[0]['message'], $message);
        $message = str_replace('{ticket_no}', $ticketDetails->id, $message);
        $message = str_replace('{customer_id}', $customerId, $message);
        $message = str_replace('{project_name}', !empty($ticketDetails->project->name) ? $ticketDetails->project->name : '' , $message);
        $message = str_replace('{ticket_subject}', $ticketDetails->subject, $message);
        $message = str_replace('{ticket_status}', !empty($ticketDetails->ticketStatus->name) ? $ticketDetails->ticketStatus->name : '', $message);
        $message = str_replace('{details}', $ticket_reply, $message);
        $message = str_replace('{assigned_by_whom}', Auth::user()->full_name, $message);
        $message = str_replace('{company_name}', $this->preference['company_name'], $message);

        return $this->email->sendTicketEmail($assignData->email, $subject, $message, Auth::user(), 'assignee', $attachments);
    }

    public function mailToCustomer($ticketDetails, $customer_id, $attachments = null)
    {
        $lang  = $this->preference['dflt_lang'];
        $email      = EmailTemplate::getAll()->where('template_id', 8)->where('language_short_name', $lang)->first();
        $subject = $email->subject;
        $message = $email->body;

        $customerData = DB::table('customers')->where('id', $customer_id)->first();
        \DB::beginTransaction();
        if (empty($customerData->password)) {
            $randomString = str_random(6);
            $hashPass     = \Hash::make($randomString);
            DB::table('customers')->where('id', $customer_id)->update(['password' => $hashPass, 'customer_type' => null]);
            $cusinfo = '<p><b>Note:</b> Your Login Details:<br> <b>Email:</b>' . ' ' . $customerData->email . ' and <br><b>Temporary Password:</b> <span class="color_00a65a f-16">' . $randomString . '</span>';


            $cusinfo = __('<p><b>Note:</b> Your Login Details:<br> <b>Email:</b> :? and <br><b>Temporary Password:</b> <span class="color_00a65a f-16"> :x </span>', ['?' => $customerData->email, 'x' => $randomString]);
        }

        if (isset($customerData->email) && !empty($customerData->email) && filter_var($customerData->email, FILTER_VALIDATE_EMAIL)) {

            $ticket_reply = url('customer-panel/support/reply/' . base64_encode($ticketDetails->id));

            $subject = str_replace('{ticket_subject}', $ticketDetails->subject, $subject);
            $subject = str_replace('{ticket_no}', $ticketDetails->id, $subject);
            $message = str_replace('{customer_name}', $ticketDetails->name, $message);
            $message = str_replace('{ticket_message}', $ticketDetails->ticketReplies[0]->message, $message);
            $message = str_replace('{ticket_no}', $ticketDetails->id, $message);
            $message = str_replace('{project_name}', !empty($ticketDetails->project->name) ? $ticketDetails->project->name : '' , $message);
            $message = str_replace('{ticket_subject}', $ticketDetails->subject, $message);
            $message = str_replace('{ticket_status}', !empty($ticketDetails->ticketStatus->name) ? $ticketDetails->ticketStatus->name : '', $message);
            $message = str_replace('{details}', $ticket_reply, $message);
            $message = str_replace('{team_member}', Auth::user()->full_name, $message);
            $message = str_replace('{company_name}', $this->preference['company_name'], $message);
            $emailResponse = $this->email->sendCustomerTicketEmail($customerData->email, $subject, $message, $attachments);

            if ($emailResponse['status'] == false) {
                \DB::rollBack();
                return $emailResponse;
             }
        }

        \DB::commit();
        return ['status' => true, 'message' => __(':? sent successfully.', ['?' => __('Email')])];

    }

    public function mailToTeamMember($ticketDetails, $member, $customer_id, $attachments)
    {
        $teamMembers = User::whereIn('id', $member)->get();
        $customerData = Customer::where('id', $customer_id)->first();
        if ($teamMembers) {
            foreach ($teamMembers as $data) {
                $lang = $this->preference['dflt_lang'];
                $email      = EmailTemplate::getAll()->where('template_id', 9)->where('language_short_name', $lang)->first();
                $subject = $email->subject;
                $message = $email->body;
                $ticket_reply = url('ticket/reply/' . base64_encode($ticketDetails->id));

                $subject = str_replace('{ticket_subject}', $ticketDetails->subject, $subject);
                $subject = str_replace('{ticket_no}', $ticketDetails->id, $subject);
                $message = str_replace('{member_name}', $data->full_name, $message);
                $message = str_replace('{company_name}', $this->preference['company_name'], $message);
                $message = str_replace('{ticket_message}', $ticketDetails->ticketReplies[0]->message, $message);
                $message = str_replace('{ticket_no}', $ticketDetails->id, $message);
                $message = str_replace('{project_name}', !empty($ticketDetails->project->name) ? $ticketDetails->project->name : '' , $message);
                $message = str_replace('{ticket_subject}', $ticketDetails->subject, $message);
                $message = str_replace('{ticket_status}', !empty($ticketDetails->ticketStatus->name) ? $ticketDetails->ticketStatus->name : '', $message);
                $message = str_replace('{customer_name}', $customerData->name, $message);
                $message = str_replace('{details}', $ticket_reply, $message);
                $emailResponse = $this->email->sendTicketEmail($data->email, $subject, $message, $customerData, null, $attachments);

                if ($emailResponse['status'] == false) {
                     return $emailResponse;
                 }
            }
        }
        return ['status' => true, 'message' => __(':? sent successfully.', ['?' => __('Email')])];
    }

    public function delete(Request $request)
    {
        if (isset($request->ticket_id)) {
            $data = DB::table('tickets')->where('id', $request->ticket_id)->first();
            if ($data) {
                \DB::table('tickets')->where('id', $request->ticket_id)->delete();
                \Session::flash('success', __('Deleted Successfully.'));
            }
        }
        return redirect()->back();
    }

    public function getAllStatus(Request $request)
    {
        $data = ['status' => 0];
        $data['output'] = '' ;
        $statusName    = $request->statusName;
        $ticketId   = $request->ticketId;
        if (!empty($statusName) && !empty($ticketId)) {
            $ticketStatus = DB::table('ticket_statuses')->where('name', '!=', $statusName)->orderBy('name')->get();
            foreach ($ticketStatus as $key => $value) {
                $data['output'] .= '<li class="properties"><a class="status_change f-14 color_black" ticket_id="'. $ticketId .'" data-id="'. $value->id .'" data-value="'. $value->name .'">'. $value->name .'</a></li>';

            }
            $data['status'] = 1;
        }
        return $data;
    }

    public function changeAssignee(Request $request)
    {
        $ticket_id       = $request->ticket_id;
        $new_assignee_id = $request->user_id;
        $data['status'] = 0;
        if (!empty($ticket_id)) {
            $confirm = DB::table('tickets')->where(['id' => $ticket_id])->update(['assigned_member_id' => $new_assignee_id]);
            if ($confirm) {
                $ticketDetails = (new Ticket())->getAllTicketDetailsById($ticket_id);
                if ($new_assignee_id) {
                    $emailResponse = $this->mailToAssignee($ticketDetails, $new_assignee_id, $request->customer_id);
                    if ($emailResponse['status'] == false) {
                        $data['status'] = 2;
                        return $data;
                     }
                }
                $data['status'] = 1;
            }
        }
        return $data;
    }

    public function reply($id)
    {
        if (isset($_GET['customer'])) {
            $data['menu'] = "relationship";
            $data['sub_menu'] = "customer";
        } else {
            $data['menu']   = 'ticket';
        }
        $data['page_title'] = __('Ticket Reply');
        $ticket_id   = base64_decode($id);
        $previousurl = url()->previous();

        $data['header'] = 'ticket';

        $data['ticketStatuses']      = TicketStatus::getAll();
        $data['ticketDetails']      = $this->Ticket->getAllTicketDetailsById($ticket_id);
        $data['priority']           = Priority::getAll()->where('id', '!=', $data['ticketDetails']->priority_id);
        $data['project']           = Project::getAll()->where('id', '!=', $data['ticketDetails']->project_id)->where('project_status_id', '!=', 6);
        if (empty($data['ticketDetails'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $data['ticketReplies']      = $this->Ticket->getAllTicketRepliersById($ticket_id);
        $replyFiles = [];
        foreach ($data['ticketReplies'] as  $ticketReply) {
            $replyFiles[$ticketReply->id] = (new File)->getFiles('Ticket Reply', $ticketReply->id);
        }
        $data['ticketStatus'] = DB::table('ticket_statuses')->where('id', '!=',  $data['ticketDetails']->ticketStatus->id)->orderBy('name')->get();
        $data['replyFiles'] = $replyFiles;
        $data['filePath'] = "public/uploads/tickets";
        $data['assignee'] = DB::table('users')->where('is_active', '!=', 0)->get();
        $data['note'] = Note::where(['related_to_id' => $ticket_id, 'related_to_type' => 'ticket'])->first();


        return view('admin.ticket.reply', $data);
    }

    public function adminReplyStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'message' => 'required'
            ]
        );
        if ($validator->fails()) {
            $url = url('ticket/reply/'. base64_encode($request->ticket_id));
            return redirect($url)->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $user_id   = Auth::user()->id;
            $status_id = $request->status_id;
            if (!empty($status_id)) {
                DB::table('tickets')
                    ->where('id', $request->ticket_id)
                    ->update([
                        'ticket_status_id'     => $status_id,
                        'last_reply'    => date('Y-m-d H:i:s'),
                    ]);
            }
            $data['ticket_id'] = $request->ticket_id;
            $data['user_id']   = $user_id;
            $data['message']   = $request->message;
            $data['date']      = date('Y-m-d H:i:s');
            $reply_id          = DB::table('ticket_replies')->insertGetId($data);
            DB::commit();

            # region store files
            if (isset($reply_id) && !empty($reply_id)) {
                if (!empty($request->attachments)) {
                    $path = createDirectory("public/uploads/tickets");
                    $replyFiles = (new File)->store($request->attachments, $path, 'Ticket Reply', $reply_id, ['isUploaded' => true, 'isOriginalNameRequired' => true, 'resize' => false]);
                }
            }
            # end region
            $ticketDetails = (new Ticket())->getAllTicketDetailsById($request->ticket_id);
            if ($request->customer_id) {
                $emailResponse = $this->mailToCustomer($ticketDetails, $request->customer_id, isset($replyFiles) ? $replyFiles : '');
                if ($emailResponse['status'] == false) {
                     \DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                 }
            }
            Session::flash('success', __('Successfully Saved'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function replyDelete(Request $request)
    {
        if (isset($request->id) && isset($request->ticket_id)) {
            // If file exeist then delete
            $file = DB::table('files')->where(['ticket_reply_id' => $request->id, 'ticket_id' => $request->ticket_id])->first();
            if (!empty($file)) {
                @unlink(public_path() . '/uploads/ticketFile/' . $file->file_name);
                DB::table('files')->where(['ticket_reply_id' => $request->id, 'ticket_id' => $request->ticket_id])->delete();

            }
            // Delete Ticket Reply
            $data = DB::table('ticket_replies')->where(['id' => $request->id, 'ticket_id' => $request->ticket_id])->first();
            if (!empty($data)) {
                \DB::table('ticket_replies')->where(['id' => $request->id, 'ticket_id' => $request->ticket_id])->delete();
                \Session::flash('success', __('Deleted Successfully.'));
                return redirect()->back();
            }
        }
    }

    public function updateReply(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        if (isset($request->id)) {
            if ($request->in_type == 'admin_replay') {
                DB::table('ticket_replies')
                    ->where(['id' => $request->id])
                    ->update([
                        'message' => $request->message,
                    ]);
            } else {
                DB::table('tickets')
                    ->where(['id' => $request->id])
                    ->update([
                        'message' => $request->message,
                    ]);
            }

            \Session::flash('success', __('Successfully Saved'));
            return redirect()->back();
        }
    }

    public function storeTicketNote(Request $request)
    {
        $this->validate($request, [
           'message' => 'required'
        ]);

        $saved = false;

        if (empty($request->note_id) && $request->message != '') {
            $note = new Note();
            $note->related_to_id = $request->ticket_id;
            $note->user_id = Auth::user()->id;
            $note->related_to_type = 'ticket';
            $note->subject = '';
            $note->content = $request->message;
            $saved = $note->save();
        } else if (!empty($request->note_id) && $request->message != '') {
            $saved = Note::where(['id'=> $request->note_id])->update([
                'user_id'    => Auth::user()->id,
                'content'    => $request->message
            ]);
        }

        if ($saved) {
            \Session::flash('success', __('Successfully saved'));
        }
        return back();
    }

    public function addCustomerTicket()
    {
        $id = Auth::guard('customer')->user()->id;
        $data['menu']        = 'customer-panel-support';
        $data['page_title'] = __('Add Customer Ticket');
        $data['customer']    = DB::table('customers')->where('id', $id)->first();
        $data['departments'] = DB::table('departments')->get();
        $data['priorities']  = DB::table('priorities')->get();
        $customerProject = Project::where(['customer_id' => $id, 'project_type' => 'customer'])->where('project_status_id', '!=', 6)->get();
        $productProject =  Project::where(['project_type' => 'product'])->where('project_status_id', '!=', 6)->get();
        $merged = $productProject->merge($customerProject);
        $data['project'] = $merged->all();

        return view('admin.customerPanel.ticket.add_ticket', $data);
    }

    public function storeCustomerTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject'       => 'required',
            'priority_id'   => 'required',
            'department_id' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            $files  = $request->file('upload_File');
            if (empty($files)) {
                return true;
            }

            foreach ($files as $key => $file) {
                if (checkFileValidationOne($file->getClientOriginalExtension()) == false) {
                    // return validator with error by file input name
                    $validator->errors()->add('upload_File', __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf'));
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $data['customer_id']      = $request->customer_id;
            $data['email']            = $request->customer_email;
            $data['name']             = $request->customer_name;
            $data['department_id']    = $department_id = $request->department_id;
            $data['priority_id']      = $request->priority_id;
            $data['project_id']       = !empty($request->project_id) ? $request->project_id : null;
            $data['ticket_status_id'] = $request->status_id;
            $data['ticket_key']       = 'TIC-' . md5(uniqid());
            $data['subject']          = stripBeforeSave($request->subject);
            $data['user_id']          = !empty(Auth::user()->id) ? Auth::user()->id : null;
            $data['customer_id']      = $request->customer_id;
            $data['date']             = date('Y-m-d H:i:s');
            $data['last_reply']       = date('Y-m-d H:i:s');
            $id                       = DB::table('tickets')->insertGetId($data);
            if (isset($id) && $id) {
                $replyData['ticket_id'] = $id;
                $replyData['customer_id']   = $request->customer_id;
                $replyData['message']   = $request->message;
                $replyData['date']      = $data['date'];
                $reply_id               = DB::table('ticket_replies')->insertGetId($replyData);
            }
            #region store files
            if (isset($reply_id) && !empty($reply_id)) {
                if (!empty($request->attachments)) {
                    $path = createDirectory("public/uploads/tickets");
                    $replyFiles = (new File)->store($request->attachments, $path, 'Ticket Reply', $reply_id, ['isUploaded' => true, 'isOriginalNameRequired' => true, 'resize' => false]);
                }
            }
            #end region
            $ticketDetails = $this->Ticket->getAllTicketDetailsById($id);
            if ($request->customer_id) {
                $emailResponse = $this->mailToTeamMember($ticketDetails, [1], $request->customer_id, isset($replyFiles) ? $replyFiles : '');
                if ($emailResponse['status'] == false) {
                     \DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                 }
            }
            DB::commit();

            Session::flash('success', __('Successfully Saved'));
            return redirect('customer-panel/support/reply/' . base64_encode($id));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

    }

    public function customerTicketList(CustomerPanelTicketListDataTable $dataTable)
    {
        $id = Auth::guard('customer')->user()->id;
        $data['menu']        = 'customer-panel-support';
        $data['page_title'] = __('Customer Supports');
        $data['cutomer_id']  = $id;
        $data['from']        = $from        = isset($_GET['from'])     ? $_GET['from']     : null;
        $data['to']          = $to          = isset($_GET['to'])       ? $_GET['to']       : null;
        $data['allstatus']   = $allstatus   = isset($_GET['status'])   ? $_GET['status']   : null;
        $data['allproject']  = $allproject  = isset($_GET['project'])  ? $_GET['project']  : null;
        $data['allassignee'] = $allassignee = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $data['alldepartment'] = $alldepartment = isset($_GET['department_id']) ? $_GET['department_id'] : null ;
        $data['departments'] = Department::get();

        $data['projects']       = Project::where('project_type', 'product')
                                    ->where('project_status_id', '!=', 6)
                                    ->orWhere('customer_id', $id)
                                    ->get(['id', 'name', 'project_type']);
        $data['status']    = TicketStatus::getAll()->pluck('name', 'id')->toArray();
        $data['settings']       = Preference::getAll();
        $data['customerData']   = $customerData = Customer::find($id);
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $dataTable->with('row_per_page', $row_per_page)->with('customerInfo', $customerData)->render('admin.customerPanel.ticket.ticket_list', $data);
    }

    public function customerReply($id)
    {
        $ticket_id             = base64_decode($id);
        $data['menu']          = 'customer-panel-support';
        $data['page_title'] = __('Customer Ticket Reply');
        $data['ticketDetails'] = $this->Ticket->getAllTicketDetailsById($ticket_id);

        if (empty($data['ticketDetails'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $data['assignee'] = User::where('id', $data['ticketDetails']->assigned_member_id)->first();
        if ($data['ticketDetails']['customer_id'] != Auth::guard('customer')->user()->id) {
            Session::flash('fail', __('Invalid Ticket Reply'));
            return redirect('customer/dashboard');
        }
        $data['ticketReplies'] = $this->Ticket->getAllTicketRepliersById($ticket_id);
        $replyFiles = [];
        foreach ($data['ticketReplies'] as  $ticketReply) {
            $replyFiles[$ticketReply->id] = (new File)->getFiles('Ticket Reply', $ticketReply->id);
        }
        $data['replyFiles'] = $replyFiles;
        $data['filePath'] = "public/uploads/tickets";

        return view('admin.customerPanel.ticket.reply', $data);

    }

    public function customerReplyStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'message' => 'required'
            ]
        );
        if ($validator->fails()) {
            $url = url('customer-panel/support/reply/'. base64_encode($request->ticket_id));
            return redirect($url)->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $user_id   = !empty(Auth::user()->id) ? Auth::user()->id : null;
            $status_id = $request->status_id;
            if (!empty($status_id)) {
                DB::table('tickets')
                    ->where('id', $request->ticket_id)
                    ->update([
                        'ticket_status_id'     => $status_id,
                        'last_reply'    => date('Y-m-d H:i:s'),
                    ]);
            }
            $data['ticket_id'] = $request->ticket_id;
            $data['customer_id'] = $request->customer_id;
            $data['message']   = $request->message;
            $data['date']      = date('Y-m-d H:i:s');
            $reply_id          = DB::table('ticket_replies')->insertGetId($data);
            DB::commit();

            # region store files
            if (isset($reply_id) && !empty($reply_id)) {
                if (!empty($request->attachments)) {
                    $path = createDirectory("public/uploads/tickets");
                    $replyFiles = (new File)->store($request->attachments, $path, 'Ticket Reply', $reply_id, ['isUploaded' => true, 'isOriginalNameRequired' => true, 'resize' => false]);
                }
            }
            # end region

            $ticketDetails = (new Ticket())->getAllTicketDetailsById($request->ticket_id);
            if ($request->assigned_member_id) {
                 if ($request->assigned_member_id == 1) {
                   $emailResponse = $this->mailToTeamMember($ticketDetails, [1], $request->customer_id, isset($replyFiles) ? $replyFiles : '');
                } else {
                    $emailResponse = $this->mailToTeamMember($ticketDetails, [1, $request->assigned_member_id], $request->customer_id, isset($replyFiles) ? $replyFiles : '');
                }
            } else {
                $emailResponse = $this->mailToTeamMember($ticketDetails, [1], $request->customer_id, isset($replyFiles) ? $replyFiles : '');
            }

            if ($emailResponse['status'] == false) {
                 \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }

            Session::flash('success', __('Successfully Saved'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Project Ticket
    public function projectTicketList($id)
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
        $data['header']    = 'project';
        $data['page_title'] = __('Tickets of project');
        $data['navbar']    = 'ticket';
        $data['status']    = DB::table('ticket_statuses')->select('id', 'name')->get();
        $data['assignees'] = DB::table('users')->where('is_active', 1)->get();
        if (isset($_GET['from'])) {
            $data['from'] = $_GET['from'];
        }
        if (isset($_GET['to'])) {
            $data['to'] = $_GET['to'];
        }
        $data['allstatus']   = $allstatus   = isset($_GET['status'])   ? $_GET['status']   : null;
        $data['alldepartment'] = $alldepartment = isset($_GET['department_id']) ? $_GET['department_id'] : null ;
        $data['departments'] = Department::get();
        $data['project'] = DB::table('projects')
            ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
            ->select('projects.id', 'projects.name', 'ps.name as status_name')
            ->where('projects.id', $id)->first();

        if (empty($data['project'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $data['summary'] = DB::table('tickets')
                                        ->rightJoin('ticket_statuses', ['ticket_statuses.id' => 'tickets.ticket_status_id', 'tickets.project_id' => DB::raw($id)])
                                        ->select(DB::raw('sum(tickets.project_id)/tickets.project_id as total_status'), 'ticket_statuses.name', 'ticket_statuses.id', 'ticket_statuses.color as color')
                                        ->groupBy('ticket_statuses.id')
                                        ->get();
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->projectTicketDataTable->with('row_per_page', $row_per_page)->with('project_id', $id)->render('admin.project.ticket.list', $data);
    }

    public function ticketEdit($project_id, $id)
    {
        $data['menu']    = 'project';
        $data['page_title'] = __('Edit Ticket of project');
        $data['header']  = 'project';
        $data['navbar']  = 'ticket';
        $data['project'] = DB::table('projects')->where('id', $project_id)->select('id')->first();

        if (empty($data['project'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $data['departments']   = DB::table('departments')->get();
        $data['priorities']    = DB::table('priorities')->get();
        $data['assignees']     = DB::table('users')->where('inactive', 0)->get();
        $data['ticketStatus']  = DB::table('ticket_statuses')->get();
        $data['customers']     = DB::table('customers')->where('inactive', 0)->get();
        $data['ticketDetails'] = (new Ticket())->getProjectAllTicketDetailsById($project_id, $id);
        if (empty($data['ticketDetails'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }
        return view('admin.project.ticket.edit', $data);
    }

    public function ticket_pdf()
    {
        $url_components = parse_url(url()->previous());
        $url_components = explode('/', $url_components['path']);
        $data['from'] = $from     = isset($_GET['from'])     ? $_GET['from']     : null;
        $data['to'] = $to       = isset($_GET['to'])       ? $_GET['to']       : null;
        $data['status'] = $status   = isset($_GET['status'])   ? $_GET['status']   : null;
        $data['project'] = $project  = isset($_GET['project'])  ? $_GET['project']  : null;
        $data['departmentId'] = $departmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;
        $data['assigneeId'] = $assigneeId = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $data['previousUrl'] = $url_components[2];
        $data['ticketList'] = $this->Ticket->getAllTicketDT($from, $to, $status, $project, $departmentId, null, null, $assigneeId)->orderBy('last_reply','desc')->get();
        $data['company_logo'] = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        $data['date_range'] = !empty($from) && !empty($to) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        $data['departmentSelected'] = Department::find($departmentId);
        $data['statusSelected'] = TicketStatus::find($status);
        $data['projectSelected'] = Project::find($project);
        $data['assigneeSelected'] = User::find($assigneeId);
        return printPDF($data, 'ticket_list_pdf' . time() . '.pdf', 'admin.ticket.list_pdf', view('admin.ticket.list_pdf', $data), 'pdf', 'domPdf');
    }

    public function ticket_csv()
    {
        return Excel::download(new projectTicketsExport(), 'tickets_list'. time() .'.csv');
    }

    public function customerTicketFilteringCsv()
    {
        return Excel::download(new customerPanelTicketsExport(), 'project_tickets_details'. time() .'.csv');
    }

    public function customerTicketFilteringPdf()
    {
        $data['from']       = $from       = isset($_GET['from']) ? $_GET['from'] : null ;
        $data['to']         = $to         = isset($_GET['to']) ? $_GET['to'] : null ;
        $status             = isset($_GET['status'])  ? $_GET['status']  : null ;
        $customer           = isset($_GET['customerID'])  ? $_GET['customerID']  : null ;
        $project            = isset($_GET['project'])  ? $_GET['project']  : null ;
        $departmentId       = isset($_GET['department_id']) ? $_GET['department_id'] : null;
        $data['customerSelected'] = Customer::find($customer);
        $data['departmentSelected'] = Department::find($departmentId);
        $data['statusSelected'] = TicketStatus::find($status);
        $data['projectSelected'] = Project::find($project);
        $data['ticketList'] = $this->Ticket->getAllTicketDT($from, $to, $status, $project, $departmentId, $customer, 'customer')->orderBy('date', 'desc')->get();
        $data['company_logo'] = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        $data['date_range'] = (!empty($from) && !empty($to)) ?  formatDate($from) . ' To ' . formatDate($to) : 'No date selected';
        return printPDF($data, 'ticket_list_pdf' . time() . '.pdf', 'admin.customerPanel.ticket.list_pdf', view('admin.customerPanel.ticket.list_pdf', $data), 'pdf', 'domPdf');
    }

    public function downloadAttachment($id)
    {
        $file = DB::table('files')->where('id', '=', $id)->first();

        if (empty($file)) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $dir_path = "public/uploads/ticketFile";
        $doc_path = $dir_path ."/". $file->file_name;
        $path = url('/') . $file->file_name;

        if (file_exists($doc_path)) {

             // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            switch (strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION))) {
                case 'pdf':
                    $mime = 'application/pdf';
                    break;
                case 'zip':
                    $mime = 'application/zip';
                    break;
                case 'jpeg':
                case 'jpg':
                    $mime = 'image/jpg';
                    break;
                default:
                    $mime = 'application/force-download';
            }
            // required
            header('Pragma: public');
            // no cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($doc_path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Content-Transfer-Encoding: binary');
            // provide file size*/
            header('Content-Length: ' . filesize($doc_path));
            header('Connection: close');
            ob_clean();
            flush();
            // push it out
            readfile($doc_path);
        } else {
            echo __("This file does not exist.");
        }
        exit();
    }

    public function changeStatus(Request $request)
    {
        $data = ['status' => 0];
        if (!empty($request->status_id) && !empty($request->ticketId)) {
            $previousStatus = Ticket::where('id', $request->ticketId)->first(['ticket_status_id']);
            $data['preStatusName'] = str_replace(' ', '', $previousStatus->ticketStatus->name);
            $update = Ticket::where(['id' => $request->ticketId])->update([
                    'ticket_status_id' => $request->status_id,
                ]);

            if ($update) {
                $newStatus = Ticket::where('id', $request->ticketId)->first(['ticket_status_id']);
                $ticktStatus = TicketStatus::getAll()->where('id', $newStatus->ticket_status_id)->pluck('color', 'name')->toArray();
                $data['newStatusName'] = str_replace(' ', '', $newStatus->ticketStatus->name);
                $data['newName'] = $newStatus->ticketStatus->name;
                $data['newStatusColor'] = array_values($ticktStatus)[0];
                $data['status']  = '1';
            }
        }
        return $data;
    }

    public function changePriority(Request $request)
    {
        $data = ['status' => 0];
        $data['output'] = '' ;
        if (!empty($request->priorityId) && !empty($request->ticketId)) {
            $update = DB::table('tickets')
                ->where(['id' => $request->ticketId])
                ->update([
                    'priority_id' => $request->priorityId,
                ]);

            if ($update) {
                $ticketStatus = DB::table('priorities')->where('id', '!=', $request->priorityId)->orderBy('name')->get();
                foreach ($ticketStatus as $key => $value) {
                    $data['output'] .= '<li class="properties"><a class="ticket_priority_change f-14 color_black" ticket_id="'. $request->ticketId .'" data-id="'. $value->id .'" data-value="'. $value->name .'">'. $value->name .'</a></li>';
                }
                $data['status']  = '1';
            }
        }
        return $data;
    }
    public function changeProject(Request $request)
    {
        $data = ['status' => 0];
        $data['output'] = '' ;
        if (isset($request->ticketId) && !empty($request->ticketId) && isset($request->projectId) && !empty($request->projectId)) {
            $update = Ticket::where('id', $request->ticketId)
                ->update(['project_id' => $request->projectId]);
            if ($update) {
                $ticketStatus = Project::where('id', '!=', $request->projectId)->where('project_status_id', '!=', 6)->orderBy('name')->get();
                foreach ($ticketStatus as $key => $value) {
                    $data['output'] .= '<li class="properties"><a class="project-name-change f-14 color_black" ticket_id="'. $request->ticketId .'" data-id="'. $value->id .'" data-value="'. $value->name .'">'. $value->name .'</a></li>';
                }
                $data['status']  = '1';
            }
        }
        return $data;
    }
}




