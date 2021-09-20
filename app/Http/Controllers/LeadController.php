<?php

/**
 * @package Leads
 * @author tehcvillage <support@techvill.org>
 * @contributor Md. Nobeul Islam <[nobeul.techvill@gmail.com]>
 * @modified 10-01-2021
 */

namespace App\Http\Controllers;

use App\DataTables\LeadListDataTable;
use App\Exports\allLeadExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CustBranch;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\SalesType;
use App\Models\Tag;
use App\Models\TagAssign;
use App\Models\User;
use App\Models\Preference;
use App\Models\SaleType;
use App\Models\CustomerBranch;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckValidEmail;
use Input;
use PDF;
use Validator;
use session;

class LeadController extends Controller
{
    /**
     * Display a listing of the Lead Status.
     *
     * @return Lead Status List page view
     */
    public function index(LeadListDataTable $dataTable)
    {
        $data['menu']         = 'lead';
        $data['page_title']   = __('Leads');
        $data['leadCount']    = Lead::all()->count();
        $data['leadActive']   = Lead::where('is_lost', 'no')->count();
        $data['leadInActive'] = Lead::where('is_lost', 'yes')->count();
        $data['users']        = User::where(['is_active' => 1, 'deleted_at' => null])->get();
        $data['allAssignee']  = isset($_GET['assignee']) ? $_GET['assignee'] : null;

        /* As customer maybe set as inactive by default,
           so to filter customer using orWhere */
        $data['leadStatus']  = LeadStatus::where('status', 'active')
                                        ->orWhere('name', '=', 'Customer')
                                        ->get();
        $data['allLeadStatus']  = isset($_GET['leadStatus']) ? $_GET['leadStatus'] : null;
        $data['leadSource']     = LeadSource::where('status', 'active')->get();
        $data['allLeadSource']  = isset($_GET['leadSource']) ? $_GET['leadSource'] : null;

        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']   = isset($_GET['to']) ? $_GET['to'] : null;

        if (isset($_GET['reset_btn'])) {
            $data['from']        = null;
            $data['to']          = null;
            $data['allAssignee'] = '';
            $data['allLeadStatus'] = '';
            $data['allLeadSource'] = '';
        }

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.lead.lead_list', $data);
    }

    /**
     * Show the form for creating a new Lead.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'lead';
        $data['page_title']   = __('Create Lead');
        $tags         = Tag::all()->pluck('name')->toArray();
        $data['tags'] = json_encode($tags);
        $data['countries'] = Country::getAll();
        $data['statuses']  = LeadStatus::where('status', 'active')->get();
        $data['sources']   = LeadSource::where('status', 'active')->get();
        $data['users']   = User::where(['is_active' => 1, 'deleted_at' => null])->get();

        return view('admin.lead.lead_add', $data);
    }

    /**
     * Store a newly created Lead Status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Lead Status List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'lead_status' => 'required',
            'lead_source' => 'required',
            'assigned' => 'required',
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'email' => ['nullable','email','unique:customers,email', new CheckValidEmail],
        ]);
         try {
            DB::beginTransaction();
                $lead = new Lead();
                $lead->first_name     = $request->first_name;
                $lead->last_name      = $request->last_name;
                $lead->email          = $request->email;
                $lead->street         = $request->street;
                $lead->city           = $request->city;
                $lead->state          = $request->state;
                $lead->zip_code       = $request->zipcode;
                $lead->country_id     = $request->country;
                $lead->phone          = $request->phone;
                $lead->website        = $request->website;
                $lead->company        = $request->company;
                $lead->description    = $request->description;
                $lead->lead_status_id = $request->lead_status;
                $lead->lead_source_id = $request->lead_source;
                $lead->assignee_id    = $request->assigned;
                $lead->user_id        = Auth::user()->id;
                $lead->last_contact   =  isset($request->contact_date) ? DbDateFormat($request->contact_date) : null;
                $lead->created_at     = DbDateTimeFormat(date("F d, Y h:i:s A"));
                if ($request->assigned) {
                    $lead->date_assigned = date('Y-m-d');
                }

                $lead->save();
                $id = $lead->id;

                // Tag Start
                if (!empty($request->tags)) {
                    $oldTag    = Tag::all()->pluck('name', 'id')->toArray();
                    $newTag    = $request->tags;
                    $newTagIds = [];
                    $new       = [];
                    if (!empty($oldTag)) {
                        foreach ($oldTag as $key => $value) {
                            if (in_array($value, $newTag)) {
                                $newTagIds[] = $key;
                            }
                        }

                        // Insert Old tag
                        if (!empty($newTagIds)) {
                            foreach ($newTagIds as $value) {
                                $newTagIn               = new TagAssign();
                                $newTagIn->tag_type     = "lead";
                                $newTagIn->tag_id       = $value;
                                $newTagIn->reference_id = $id;
                                $newTagIn->save();
                            }
                        }
                    }

                    // Insert New Tag
                    foreach ($newTag as $key => $value) {
                        if (!in_array($value, $oldTag)) {
                            $new[] = $value;
                        }
                    }

                    if (!empty($new)) {
                        foreach ($new as $value) {
                            $newTag = new Tag();
                            $newTag->name =   $value;
                            $newTag->save();

                            $lastInsertId = $newTag->id;
                            if ($lastInsertId) {
                                $newTagIntoInsert               = new TagAssign();
                                $newTagIntoInsert->tag_type     = 'lead';
                                $newTagIntoInsert->tag_id       =  $lastInsertId;
                                $newTagIntoInsert->reference_id = $id;
                                $newTagIntoInsert->save();
                            }
                        }
                    }
                }
                // Tag End
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('Failed To Add New Lead Information'));
        }

        if (!empty($id)) {
            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('lead/list');
        } else {
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $data['menu'] = 'lead';
        $data['page_title']   = __('View Lead');
        $tags         = Tag::all()->pluck('name')->toArray();

        // Eloquent Starts
        $tagsInResult = TagAssign::with(['tag'])
                ->where(['reference_id'=> $id, 'tag_type' => 'lead'])
                ->get();

        $oldTags = [];
        foreach ($tagsInResult as $tagIn) {
            $tagName = isset($tagIn->tag->name) && !empty($tagIn->tag->name) ? $tagIn->tag->name : '';
            $oldTags[] = '<span class="btn btn-outline-secondary custom-btn-small lead-view-tag"><span class="tag-in-result f-11">'. $tagName .'</span></span><br/>';
        }
        // Eloquent Ends

        $data['oldTags']   = !empty($oldTags) ? implode('<span class="hide"> </span>', $oldTags) : '';
        $data['tags']      = json_encode($tags);
        $data['leadData']  = Lead::find($id);
        $data['countries'] = Country::all();
        $data['statuses']  = LeadStatus::where('status','active')->get();
        $data['sources']   = LeadSource::where('status','active')->get();
        $data['users']     = User::where('is_active', 1)->get();
        if (empty($data['leadData'])) {
            \Session::flash('fail', __('Lead not found'));
            return redirect('lead/list');
        }
        $data['customer_id'] = Customer::where('email', $data['leadData']->email)->value('id');

        return view('admin.lead.lead_view', $data);
    }

    /**
     * Show the form for editing the specified Lead Status.
     *
     * @param  int  $id
     * @return Lead Status edit page view
     */
    public function edit($id)
    {

        $data['menu'] = 'lead';
        $data['page_title']   = __('Edit Lead');
        $tags         = Tag::all()->pluck('name')->toArray();
        $data['tags'] = TagAssign::with(['tag'])
                        ->where(['reference_id'=> $id, 'tag_type'=> 'lead'])
                        ->get();
        $data['leadData']   = Lead::find($id);
        if (empty($data['leadData'])) {
            \Session::flash('fail', __('Lead not found'));
            return redirect('lead/list');
        }
        $data['countries']  = Country::getAll();
        $data['statuses']   = LeadStatus::where('status','active')->get();
        $data['sources']    = LeadSource::where('status','active')->get();
        $data['users']      = User::where(['is_active' => 1, 'deleted_at' => null])->get();

        return view('admin.lead.lead_edit', $data);
    }

    /**
     * Update the specified Lead Status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Lead Status List page view
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'lead_status' => 'required',
            'lead_source' => 'required',
            'assigned' => 'required',
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'email' => ['nullable','email','unique:customers,email,$request->customer_id,id', new CheckValidEmail],
        ]);

        $id = $request->lead_id;
        try {
            DB::beginTransaction();
                $leadToUpdate                 = Lead::find($request->lead_id);
                $last_status_id               = $leadToUpdate->status_id;
                $leadToUpdate->first_name     = $request->first_name;
                $leadToUpdate->last_name      = $request->last_name;
                $leadToUpdate->email          = $request->email;
                $leadToUpdate->street         = $request->street;
                $leadToUpdate->city           = $request->city;
                $leadToUpdate->state          = $request->state;
                $leadToUpdate->zip_code       = $request->zipcode;
                $leadToUpdate->country_id     = $request->country;
                $leadToUpdate->phone          = $request->phone;
                $leadToUpdate->website        = $request->website;
                $leadToUpdate->company        = $request->company;
                $leadToUpdate->description    = $request->description;
                $leadToUpdate->lead_source_id = $request->lead_source;
                $leadToUpdate->last_contact   = isset($request->contact_date) && !empty($request->contact_date) ? DbDateFormat($request->contact_date) : null;

                // When lead status has been changed in edit view
                if ($leadToUpdate->lead_status_id != $request->lead_status) {
                    $value = date('Y-m-d H:i:s');
                    $dateWithTime = subtractZonegetTime($value);
                    $leadToUpdate->lead_status_id     = isset($request->lead_status) ? $request->lead_status : 1 ;
                    $leadToUpdate->last_status_change  = $dateWithTime;
                    $leadToUpdate->last_lead_status  = $last_status_id;
                }

                // when lead assigned to someone
                if ($leadToUpdate->assignee_id != $request->assigned && $request->assigned != null) {
                    $leadToUpdate->assignee_id   = $request->assigned;
                    $leadToUpdate->date_assigned = date('Y-m-d');
                }

                // when lead assigned to no one
                if (empty($request->assigned)) {
                    $leadToUpdate->assignee_id   = null;
                    $leadToUpdate->date_assigned = null;
                }

                $leadToUpdate->save();
                $id = $leadToUpdate->id;

                // Update Tag Starts
                $newTag = $request->tags;
                if (!empty($newTag)) {
                    $newTag        = $request->tags;
                    $oldTag        = Tag::all()->pluck('name', 'id')->toArray();
                    $equalTagId    = [];
                    $notEqualTag   = [];
                    $notEqualTagId = [];
                    $newArry       = [];
                    foreach ($oldTag as $key => $value) {
                        if (in_array($value, $newTag)) {
                            // If tag exist in tags table then get it's id
                            $equalTagId[] = $key;
                        }
                    }

                    foreach ($newTag as $key => $value) {
                        if (!in_array($value, $oldTag)) {
                            $notEqualTag[] = $value;
                        }
                    }

                    if (!empty($notEqualTag)) {
                        foreach ($notEqualTag as $key => $value) {
                            $newTag       = new Tag();
                            $newTag->name = $value;
                            $newTag->save();

                            $insertTagId = $newTag->id;
                            if (!empty($insertTagId)) {
                                $notEqualTagId[] = $insertTagId;
                            }
                        }
                    }

                    $allTags     = array_merge($equalTagId, $notEqualTagId);
                    // Fetch tag from tags_in tb
                    $insertedTag = TagAssign::where('reference_id', $id)->pluck('tag_id')->toArray();

                    if (!empty($insertedTag)) {
                        foreach ($allTags as $key => $value) {
                            if (!in_array($value, $insertedTag)) {
                                $newTagIn               = new TagAssign();
                                $newTagIn->tag_type     = 'lead';
                                $newTagIn->tag_id       = $value;
                                $newTagIn->reference_id = $id;
                                $newTagIn->save();
                            }
                        }

                        foreach ($insertedTag as $key => $value) {
                            if (!in_array($value, $allTags)) {
                                TagAssign::where(array('reference_id' => $id, 'tag_id' => $value))->delete();
                                $tagToDelete = Tag::find($value);
                                $tagToDelete->delete();
                            }
                        }
                    } else {
                        foreach ($allTags as $key => $value) {
                            $newTagInNew               = new TagAssign();
                            $newTagInNew->tag_type     = 'lead';
                            $newTagInNew->tag_id       = $value;
                            $newTagInNew->reference_id = $id;
                            $newTagInNew->save();
                        }
                    }
                } else {
                    TagAssign::where(['reference_id' => $id, 'tag_type' => 'lead'])->delete();
                }
                // Update Tag Ends

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('Failed To Update Lead Information'));
        }

        \Session::flash('success', __('Successfully updated'));
        return redirect()->intended('lead/list');
    }

    /**
     * Remove the specified Lead Status from storage.
     *
     * @param  int  $id
     * @return redirect Lead Status List page view
     */
    public function destroy(Request $request)
    {
        if (isset($request->lead_id)) {
            try {
                DB::beginTransaction();
                $leadToDelete = Lead::find($request->lead_id);
                if ($leadToDelete) {
                    $allTagsOfRelatdLead = TagAssign::where('reference_id', $request->lead_id)->get();
                    foreach ($allTagsOfRelatdLead as $value) {
                       $tagToDelete = Tag::find($value->tag_id);
                       $tagToDelete->delete();
                    }
                    TagAssign::where(array('reference_id' => $request->lead_id))->delete();
                    $leadToDelete->delete();
                    DB::commit();
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withErrors(__('Failed To Delete Lead Information'));
            }
            \Session::flash('success', __('Deleted Successfully.'));
            return redirect()->intended('lead/list');
        }

    }

    /**
     * Convert to Customer View
     *
     * @param  int  $id
     * @return redirect Convert To Customer page view
     */
    public function createCustomer($id)
    {
        $data['menu']     = 'lead';
        $data['page_title']   = __('Lead Conversion');
        $data['leadData']   = Lead::find($id);
        if (empty($data['leadData'])) {
            \Session::flash('fail', __('Lead not found'));
            return redirect('lead/list');
        }
        $data['countries']   = Country::getAll();
        $data['sales_types'] = SaleType::all();
        $data['currencies']  = Currency::getAll();

        return view('admin.lead.lead_convert_customer', $data);
    }

    /**
     * Convert to Customer Store
     *
     * @param  int  $id
     * @return Store Converted Customer
     */
    public function storeCustomer(Request $request)
    {
        $this->validate($request, [
            'first_name'      => 'required',
            'last_name'       => 'required',
            'currency_id'     => 'required',
            'email'           => ['nullable','unique:customers,email', new CheckValidEmail]
        ]);

        // changing information in leads table
        $leadToUpdateConverting     = Lead::find($request->lead_id);
        if (!empty($leadToUpdateConverting)) {
            $leadToUpdateConverting->delete();
        } else {
            \Session::flash('fail', __('Lead not found'));
            return redirect('lead/list');
        }

        // insert
        if (isset($request->password) && !empty($request->password)) {
            $password = \Hash::make(trim($request->password));
        } else {
            $password = '';
        }
        $newCustomer    = new Customer();
        $newCustomer->name          = $request->first_name.' '. $request->last_name;
        $newCustomer->first_name    = $request->first_name;
        $newCustomer->last_name     = $request->last_name;
        $newCustomer->email         = $request->email;
        $newCustomer->phone         = $request->phone;
        $newCustomer->tax_id        = $request->tax_id;
        $newCustomer->currency_id   = $request->currency_id;
        $newCustomer->password      = $password;
        $newCustomer->save();
        $id = $newCustomer->id;

        if (!empty($id)) {
            // insert
            $newCustBranch  = new CustomerBranch();
            $newCustBranch->customer_id         = $id;
            $newCustBranch->name             = $request->first_name.' '.$request->last_name;
            $newCustBranch->billing_street      = $request->bill_street;
            $newCustBranch->billing_city        = $request->bill_city;
            $newCustBranch->billing_state       = $request->bill_state;
            $newCustBranch->billing_zip_code    = $request->bill_zipCode;
            $newCustBranch->billing_country_id  = $request->bill_country_id;
            $newCustBranch->shipping_street     = $request->ship_street;
            $newCustBranch->shipping_city       = $request->ship_city;
            $newCustBranch->shipping_state      = $request->ship_state;
            $newCustBranch->shipping_zip_code   = $request->ship_zipCode;
            $newCustBranch->shipping_country_id = $request->ship_country_id;
            $newCustBranch->save();

            if ($request->type == 'sale') {
                \Session::flash('success', __('Successfully Saved'));
                return redirect()->intended("invoice/add");
            } elseif ($request->type == 'quotation') {
                \Session::flash('success', __('Successfully Saved'));
                return redirect()->intended("order/add");
            } else {
                \Session::flash('success', __('Lead converted to customer successfully'));
                return redirect()->intended("customer/edit/". $id);
            }
        } else {
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    public function leadListCsv()
    {
        return Excel::download(new allLeadExport(), 'all_lead_details'. time() .'.csv');
    }

    public function leadListPdf()
    {
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']   = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['date_range'] = ($data['from'] && $data['to']) ? formatDate($data['from']) . __('To') . formatDate($data['to']) : 'No Date Selected';
        $data['company_logo']   = Preference::where('category','company')
                                            ->where('field', 'company_logo')
                                            ->first(['value']);
        $leads = Lead::all();

        if ($from && $to) {
            $from   = DbDateFormat($from);
            $to     = DbDateFormat($to);
        }
        $assignee   = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $leadStatus = isset($_GET['leadStatus']) ? $_GET['leadStatus'] : null;
        $leadSource = isset($_GET['leadSource']) ? $_GET['leadSource'] : null;
        $data['sourceSelected'] = LeadSource::find($leadSource);
        $data['userSelected'] = User::find($assignee);
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
        $data['leadsList'] =  $leads;
        return printPDF($data, 'leads_list_' . time() . '.pdf', 'admin.lead.lead_list_pdf', view('admin.lead.lead_list_pdf', $data), 'pdf', 'domPdf');
    }
}
