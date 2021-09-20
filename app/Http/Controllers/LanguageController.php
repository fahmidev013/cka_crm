<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\EmailTemplate;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Cache;

class LanguageController extends Controller
{
    public function index()
    {
        $data['menu']               = 'setting';
        $data['sub_menu']           = 'general';
        $data['page_title']         = __('Languages');
        $data['list_menu']          = 'language';
        $data['languageList']          = Language::getAll();
        $data['languagesImgPath']   = 'public/uploads/flags';
        $data['languageShortName']  = getShortLanguageName(false, $data['languageList']);

        return view('admin.language.language_list', $data);
    }

    public function translation($id)
    {
        if (!is_writable(base_path('resources/lang/'))) {
            \Session::flash('fail', __('Need writable permission of language directory'));
            return back();
        }
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Translations');
        $data['list_menu'] = 'language';
        $data['language'] = $language = Language::getAll()->where('id', $id)->first();
        if (empty($language)) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        updateLanguageFile($language->short_name);
        $data['jsonData'] = openJSONFile($language->short_name);

        return view('admin.language.translation', $data);
    }

    public function translationStore(Request $request)
    {
        if (!is_writable(base_path('resources/lang/'))) {
            \Session::flash('fail', __('Need writable permission of language directory'));
            return back();
        }
        $language = Language::getAll()->where('id', $request->id)->first();
        $data = openJSONFile($language->short_name);

        foreach ($request->key as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $secondKey => $secondValue) {
                    $data[$key][$secondKey] = $request->key[$key][$secondKey];
                }
            } else {
                $data[$key] = $request->key[$key];
            }
        }
        
        saveJSONFile($language->short_name, $data);
        \Session::flash('success', __('Successfully Updated'));
        return back();
    }

    public function delete_language(Request $request)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $language = Language::getAll()->where('id', $request->id)->first();
        if (!empty($language)) {
            if ($language->short_name == 'en') {
                $data = [ 
                    'type'    => 'fail',
                    'message' => __('English language can not be deleted.')
                ];

                \Session::flash($data['type'], $data['message']);   
                return redirect()->back();
            }

            $isDefaultLanguage = Preference::where([ 
                                                    'category' => 'company',
                                                    'field' => 'dflt_lang',
                                                    'value' => $language->short_name
                                            ])->first();
            if ($isDefaultLanguage) {
                $data = [ 
                    'type'    => 'fail',
                    'message' => __('Default language can not be deleted.')
                ];
            } else {
                \DB::beginTransaction();
                try{
                    // delete email template of the language
                    EmailTemplate::where(['language_id' => $request->id])->delete();
                    Cache::forget('gb-email_template');
                    Language::where(['id' => $request->id])->delete();
                    \DB::commit();
    
                    Cache::forget('gb-languages');
                    $data = [ 
                       'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                } catch(\Exception $e) {
                    \DB::rollBack();
                }
            }
        }
        
        \Session::flash($data['type'], $data['message']);   
        return redirect()->back();
    }

    public function save_language(Request $request)
    {  
    	$this->validate($request, [
    		'language_name'    => 'required',
    		'status'        => 'required',
    		'direction'     => 'required'
        ]);
        if (!is_writable(base_path('resources/lang/'))) {
            \Session::flash('fail', __('Need writable permission of language directory'));
            return back();
        }
        $languages  = getShortLanguageName(true);
        if (!in_array(strtolower($request->language_name), array_keys($languages))) {
            \Session::flash('fail', __('Invalid Language'));   
        } else if (Language::where('short_name', $request->language_name)->exists()) {
            \Session::flash('fail', __('That language is already taken.'));   
        } else {
            \DB::beginTransaction();
            try {
                $language             = new Language();
                $language->name       = $languages[strtolower($request->language_name)];
                $language->short_name = strtolower($request->language_name);
                $language->direction  = $request->direction == 'rtl' ? 'rtl' : 'ltr';
                
                if (isset($request->default) && $request->default === "on") {
                    Language::where('is_default', 1)->update(['is_default' => 0]);
                    Preference::where('category', 'company')
                                ->where('field', 'dflt_lang')
                                ->update(['value' => $language->short_name]);
                    $language->is_default = 1;
                    $language->status     = "Active";
                } else {
                    $language->is_default = 0;
                    $language->status     = $request->status;
                }
                $language->save();
                $this->generateTemplates($language);
                $this->generateTemplates($language, 'sms');
                \DB::commit();

                Cache::forget('gb-languages');
                Cache::forget('gb-preferences');

                \Session::flash('success', __('Successfully Saved'));   
                return redirect('languages/translation/' . $language->id);

            } catch(\Exception $e) {
                \DB::rollBack();
            }
        }

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $language = Language::find($request->id);
            
            $return_lang['id'] = $language->id;
            $return_lang['language_name'] = $language->name;
            $return_lang['short_name'] = $language->short_name;
            $return_lang['flag'] = url("public/datta-able/fonts/flag/flags/4x3/". getSVGFlag($language->short_name) .".svg");
            $return_lang['status'] = $language->status;
            $return_lang['is_default'] = $language->is_default;
            $return_lang['direction'] = $language->direction;

            echo json_encode($return_lang);
        }
    }

    public function update_language(Request $request)
    {
        $this->validate($request, [
            'edit_direction'     => 'required',
            'edit_status' => 'required'
        ]);

        \DB::beginTransaction();
        try {
            $language             = Language::find($request->language_id);
            $language->direction  = $request->edit_direction == 'rtl' ? 'rtl' : 'ltr';
            if (isset($request->edit_default) && $request->edit_default === "on") {
                Language::where('is_default', 1)->where('id', '!=', $language->id)->update(['is_default' => 0]);
                $language->is_default = 1;
                $language->status     = "Active";
            } else {
                $language->is_default = 0;
                $language->status     = $request->edit_status;
            }
            if ($language->update()) {
                if ($language->status == 'Active' && $language->short_name <> 'en') {
                    updateLanguageFile($language->short_name);
                    $this->generateTemplates($language);
                    $this->generateTemplates($language, 'sms');
                }
                if (empty($language->is_default) && empty(Language::where('is_default', 1)->count())) {
                    Language::where('short_name', 'en')->update(['is_default' => 1]);
                    $language->short_name = 'en';
                }
                Preference::where('category', 'company')
                            ->where('field', 'dflt_lang')
                            ->update(['value' => $language->short_name]);

                Cache::forget('gb-languages');
                Cache::forget('gb-preferences');

                \DB::commit();

                \Session::flash('success', __('Successfully updated'));   
            }
            
        }  catch(\Exception $e) {
            \DB::rollBack();
            \Session::flash('fail', __('Something went wrong, please try again.')); 
        }
        return redirect()->back();
    }

    public function validLanguageShortName()
    {
        if (isset($_GET['short_name'])) {
            $shortName= $_GET['short_name'];            
        } else {
            $shortName= $_GET['edit_short_name'];
        }
        if (isset($_GET['language_id'])) {
            $language_id = $_GET['language_id'];
            $v = DB::table('languages')
                ->where('short_name',$shortName)
                ->where('id', "!=", $language_id)
                ->first();
        } else {
            $v = DB::table('languages')
                ->where('short_name',$shortName)
                ->first();
        }             
              
        if (!empty($v)) {
            echo json_encode(__('That Short Name is already taken.'));
        } else {
            echo "true";
        }
    }

    public function generateTemplates($language = null, $type = 'email')
    {
        if (!empty($language)) {
            $enTemplates = EmailTemplate::where(['language_short_name' => 'en', 'template_type' => $type])->get();
            $existTemplateIds = EmailTemplate::where(['language_short_name' => $language->short_name, 'template_type' => $type])->get()->pluck('template_id', 'id')->toArray();

            $newTemplates = [];

            foreach ($enTemplates as $key => $value) {

                if (in_array($value->template_id, $existTemplateIds)) {
                    continue;
                }

                $newTemplates[$key]['template_id'] = $value->template_id;
                $newTemplates[$key]['subject'] = $value->subject;
                $newTemplates[$key]['body'] = $value->body;
                $newTemplates[$key]['language_short_name'] = $language->short_name;
                $newTemplates[$key]['template_type'] = $value->template_type;
                $newTemplates[$key]['language_id'] = $language->id;
            }
            EmailTemplate::insert($newTemplates);
        }
    }
}
