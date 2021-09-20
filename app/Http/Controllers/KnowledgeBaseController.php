<?php
/**
 * @package KnowledgeBaseController
 * @author tehcvillage <support@techvill.org>
 * @contributor Sakawat Hossain Rony <[sakawat.techvill@gmail.com]>
 * @created 19-06-2021
 */
namespace App\Http\Controllers;

use App\DataTables\KnowledgeBaseDataTable;
use App\Exports\knowledgeBaseExport;
use App\Models\{
    Group,
    KnowledgeBase,
    Preference,
};
use Illuminate\Http\Request;
use Excel;
use Auth;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the Knowledge Base.
     * @return  Knowledge Base List page view
     */
    public function index(Request $request, KnowledgeBaseDataTable $dataTable)
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('Knowledge Base');
        $data['groups']     = Group::getAll()->where('status', 'active')->all();
        $data['allgroups']  = isset($request->group_id) ? $request->group_id : null;
        $data['allstatus']  = isset($request->status) ? $request->status : null;
        $data['from'] = isset($request->from) ? $request->from : null;
        $data['to']   = isset($request->to) ? $request->to : null;
        $row_per_page       = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $dataTable->with('row_per_page', $row_per_page)->render('admin.knowledgeBase.index', $data);
    }

    /**
     * Show the form for creating a new Knowledge.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('Create Knowledge');
        $data['groups']     = Group::getAll()->where('status', "active")->all();
        $comments = Preference::getAll()->where('category', 'preference')->where('field', 'facebook_comments')->first();
        $data['comments']   = isset($comments->value) ? $comments->value : null;
        return view('admin.knowledgeBase.create', $data);
    }

    /**
     * Store a newly created Knowledge base in storage.
     * @param \Illuminate\Http\Request $request
     * @return redirect Knowledge base  List page view
     */
    public function store(Request $request)
    {
        $data      = ['status' => 'fail', 'message' => __("Invalid Request")];
        $validator = KnowledgeBase::storeValidation($request->all());
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->messages());
        }
        $request['publish_date'] = $request->status == 'publish' ? date('Y-m-d') : null;
        $slugReplace             = str_replace(' ', '-', stripBeforeSave($request->subject));
        $slugLower               = strtolower($slugReplace);
        $slug                    = preg_replace('/[^A-Za-z0-9\-]/', '', $slugLower);
        $request['slug']         = KnowledgeBase::getAll()->where('slug', $slug)->count() > 0 ? $slug . strtolower(str_random(4)) : $slug;
        $comments                = Preference::getAll()->where('category', 'preference')->where('field', 'facebook_comments')->first();
        $request['comments']     = isset($comments->value) && $comments->value == 'enable' && isset($request->comments) ? $request->comments : 'no';
        $request['subject']      = !empty(stripBeforeSave($request->subject)) ? stripBeforeSave($request->subject) : '';

        if ((new KnowledgeBase)->store($request->only('subject', 'slug', 'group_id', 'description', 'status', 'comments', 'publish_date'))) {
            $data['status']  = 'success';
            $data['message'] = __('Successfully Saved');
        }

        \Session::flash($data['status'], $data['message']);
        return redirect()->intended('knowledge-base');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function view($slug = null)
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('View Knowledge Base');
        $knowledgeData      = KnowledgeBase::getAll()->where('slug', $slug)->first();

        if (!empty($knowledgeData)) {
            $data['knowledgeData']  = $knowledgeData;
            $comments = Preference::getAll()->where('category', 'preference')->where('field', 'facebook_comments')->first();
            $data['preferenceComments'] = isset($comments->value) ? $comments->value : null;
            $data['relatedArticle'] = KnowledgeBase::getAll()->where('slug', "!=", $knowledgeData->slug)->where('status', 'publish')->where('group_id', $knowledgeData->group_id)->sortByDesc('id');

            return view('admin.knowledgeBase.view', $data);
        } else {
            \Session::flash('fail', __('Knowledge base not found.'));
            return redirect('knowledge-base');
        }
    }

    /**
     * Show the form for editing the specified knowledge base.
     *
     * @param int $id
     * @return Knowledge Base edit page view
     */
    public function edit($id)
    {
        $data['menu']          = 'knowledge_base';
        $data['page_title']    = __('Edit Knowledge Base');
        $data['knowledgeData'] = KnowledgeBase::getAll()->where('id', $id)->first();
        $data['comments']      = Preference::getAll()->where('category', 'preference')->where('field', 'facebook_comments')->first('value')->value;

        if (!empty($data['knowledgeData'])) {
            $data['groups'] = Group::getAll()->where('status', "active")->all();
            return view('admin.knowledgeBase.edit', $data);
        }

        \Session::flash('fail', __('Knowledge base not found.'));
        return redirect('knowledge-base');
    }

    /**
     * Update the specified Knowledge Base in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return redirect knowledge base page view
     */
    public function update(Request $request)
    {
        $data      = ['status' => 'fail', 'message' => __("Failed To Update Knowledge base Information")];
        $validator = KnowledgeBase::updateValidation($request->all(), $request->knowledge_id);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->messages());
        }

        $knowledgeInfo           = KnowledgeBase::getAll()->where('id', $request->knowledge_id)->first();
        $request['slug']         = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($request->slug));
        $request['publish_date'] = $knowledgeInfo->status == 'draft' && empty($knowledgeInfo->publish_date) && $request->status == 'publish' ? date('Y-m-d') : $knowledgeInfo->publish_date;
        $comments                = Preference::getAll()->where('category', 'preference')->where('field', 'facebook_comments')->first('value')->value;
        $request['comments']     = $comments == 'enable' && isset($request->comments) ? $request->comments : $knowledgeInfo->comments;
        $request['subject']      = stripBeforeSave($request->subject);

        if ((new KnowledgeBase)->updateKnowledgeBase($request->only('subject', 'group_id', 'description', 'slug', 'publish_date', 'comments', 'status'), $request->knowledge_id)) {
            $data['status']  = 'success';
            $data['message'] = __('Successfully updated');
        }

        \Session::flash($data['status'], $data['message']);
        return redirect()->intended('knowledge-base');
    }

    /**
     * Remove the specified knowledge base from storage.
     *
     * @param int $id
     * @return redirect knowledge base page view page view
     */
    public function destroy(Request $request)
    {
        $data = ['status' => 'fail', 'message' => __("Failed To Delete Knowledge Base Information")];

        if (isset($request->knowledge_id)) {
            if ((new KnowledgeBase)->remove($request->knowledge_id)) {
                $data['status'] = 'success';
                $data['message'] = __('Deleted Successfully.');
            }

            \Session::flash($data['status'], $data['message']);
            return redirect()->intended('knowledge-base');
        }
    }

    /**
     * Exports data a in csv format
     * @return mixed
     */
    public function knowledgeListCsv()
    {
        return Excel::download(new knowledgeBaseExport(), 'all_knowledge_details' . time() . '.csv');
    }

    /**
     * Export data in pdf format
     * @return mixed
     */
    public function knowledgeListPdf(Request $request)
    {
        $data['company_logo'] = Preference::where('category', 'company')
            ->where('field', 'company_logo')
            ->first(['value']);
        $knowledges           = KnowledgeBase::with('group');
        $group                = isset($request->group_id) && !empty($request->group_id) ? $request->group_id : null;
        $status               = isset($request->status) && !empty($request->status) ? $request->status : null;
        $data['from']         = $from = isset($request->from) && !empty($request->from) ? DbDateFormat($request->from) : null;
        $data['to']           = $to = isset($request->to) && !empty($request->to) ? DbDateFormat($request->to) : null;
        $data['date_range']   = ($data['from'] && $data['to']) ? formatDate($data['from']) . __('To') . formatDate($data['to']) : 'No Date Selected';

        if (!empty($group)) {
            $knowledges            = $knowledges->where('group_id', $group);
            $data['groupSelected'] = Group::getAll()->where('id', $group)->first();
        }
        if (!empty($status)) {
            $knowledges             = $knowledges->where('status', $status);
            $data['statusSelected'] = ucfirst($status);
        }
        if (!empty($from)) {
            $knowledges = $knowledges->where('publish_date', '>=', $from);
        }
        if (!empty($to)) {
            $knowledges = $knowledges->where('publish_date', '<=', $to);
        }
        $data['knowledgeList'] = $knowledges->get();
        return printPDF($data, 'knowledges_list_' . time() . '.pdf', 'admin.knowledgeBase.knowledge_pdf', view('admin.knowledgeBase.knowledge_pdf', $data), 'pdf', 'domPdf');
    }

    /**
     * knowledge lis in customer panel
     * @return \BladeView|false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function customerKnowledgeList()
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('Knowledge Base');
        $allGroups          = Group::getAll()->where('status', "active")->toArray();
        $knowledgeGroups    = KnowledgeBase::getAll()->where('status', 'publish')->pluck('id', 'group_id')->toArray();
        $groups             = [];
        foreach ($allGroups as $key => $group) {
            if (array_key_exists($group['id'], $knowledgeGroups)) {
                $groups[] = array(
                    "id" => $group['id'],
                    "name" => $group['name'],
                    "description" => $group['description'],
                );
            }
        }
        $data['groups'] = $groups;
        $data['groupCount'] = (new KnowledgeBase)->groupCount();
        return view('admin.customerPanel.knowledgeBase.index', $data);
    }

    /**
     * knowledge list in group wise
     * @param $name
     * @return \BladeView|false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function customerKnowledgeGroup($name)
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('Knowledge Base');
        $groupInfo          = Group::getAll()->where('name', $name)->first();
        if (!empty($groupInfo)) {
            $data['knowledgeData'] = KnowledgeBase::getAll()->where('status', 'publish')->where('group_id', $groupInfo->id)->all();
            $data['group']         = $groupInfo;
            return view('admin.customerPanel.knowledgeBase.group_knowledge', $data);
        } else {
            return redirect()->back()->withInput()->withErrors(['fail' => __('Something went wrong, please try again.')]);
        }
    }

    /**
     * Specifice knoeledge list
     * @param $slug
     * @return \BladeView|false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function customerKnowledgeArticle($slug = null)
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('Knowledge Base');
        $knowledgeData      = KnowledgeBase::getAll()->where('status', 'publish')->where('slug', $slug)->first();
        if (!empty($knowledgeData)) {
            $data['knowledgeData']      = $knowledgeData;
            $data['preferenceComments'] = Preference::getAll()->where('category', 'preference')->where('field', 'facebook_comments')->first('value')->value;
            $data['relatedArticle']     = KnowledgeBase::getAll()->whereNotIn('slug', [$knowledgeData->slug])->where('status', 'publish')->where('group_id', $knowledgeData->group_id)->sortByDesc('id');
            return view('admin.customerPanel.knowledgeBase.article', $data);
        } else {
            return redirect()->back()->withInput()->withErrors(['fail' => __('Something went wrong, please try again.')]);
        }
    }

    /**
     * Knowledge search in cutomer panel
     * @return \BladeView|false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function customerKnowledgeSearch(Request $request)
    {
        $data['menu']       = 'knowledge_base';
        $data['page_title'] = __('Knowledge Base');
        $data['group']      = Group::getAll()->where('status', 'active')->pluck('name', 'id')->toArray();
        $knowledge          = KnowledgeBase::where('status', 'publish');
        $keyword            = isset($request->q) && !empty($request->q) ? $request->q : null;
        $data['searchData'] = $keyword;
        if (!empty($keyword)) {
            if (preg_match('/^[1-9]+[0-9]*$/', $keyword)) {
                $knowledge->where('id', $keyword);
            } elseif (strlen($keyword) >= 2) {
                $knowledge->where(function ($query) use ($keyword) {
                    $query->where('subject', 'LIKE', '%' . $keyword . '%')
                        ->orwhere('slug', 'LIKE', '%' . $keyword . '%')
                        ->orwhere('description', 'LIKE', '%' . $keyword . '%')
                        ->orwhereHas("group", function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%" . $keyword . "%");
                        })->with('group');
                });
            }
        }
        $knowledgeData = $knowledge->get();
        $array         = [];
        foreach ($knowledgeData as $key => $value) {
            $array[$value->group_id][] = [
                'subject' => $value->subject,
                'slug' => $value->slug,
                'description' => $value->description,
            ];
        }
        $data['knowledgeData'] = $array;
        return view('admin.customerPanel.knowledgeBase.search', $data);
    }
}
