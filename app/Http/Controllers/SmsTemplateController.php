<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Language;
use App\Models\EmailTemplate;
use Cache;
use DB;
use Auth;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index(Request $request, $id)
    {
        $invalidEmailTemplateId = EmailTemplate::where(['template_id' => $id, 'template_type' => 'sms'])->first();
        if (empty($invalidEmailTemplateId)) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }

    	if (empty($request->all())) {
        	$data['menu'] = 'setting';
            $data['sub_menu'] = 'sms-temp';
            $data['page_title'] = __('SMS Templates');
            $data['list_menu'] = 'menu-'.$id;
            $data['tempId'] = $id;
            
            //Please check the below if it is equivalent to above joining
            $data['temp_Data']  = EmailTemplate::with(['language' => function ($query) 
            {
                $query->where(['status'=>'Active']);
            }])->where(['template_type' => 'sms','template_id'=>$id])->get();
            //check ENDS
            
            $data['languages'] = Language::where(['languages.status'=>'Active'])->orderBy('id')->get();
            return view('admin.smsTemplate.template', $data);

    	} else {

            unset($request['_wysihtml5_mode']);
            unset($request['_token']);

            foreach ($request->all() as $key =>  $value) {
                $lang[] = $key;
                $lang_id[] = $value['id'];
                unset($value['id']);
                $data[] = $value;
            }
            for ($i=0; $i < count($lang_id) ; $i++) {
            $check = DB::table('email_templates')
                    ->where('language_id','=',$lang_id[$i])
                    ->where('template_id','=',$id)
                    ->where('template_type','=','sms')
                    ->first();
            if ($check) {
                DB::table('email_templates')->where([['template_id',$id],['language_id', $lang_id[$i]],['template_type','sms']])->update($data[$i]);
            } else {
                $data2['template_id']         = $id;
                $data2['body']                = $data[$i]['body'];
                $data2['language_short_name'] = $lang[$i];
                $data2['template_type']       = 'sms';
                $data2['language_id']         = $lang_id[$i];
                DB::table('email_templates')->insert($data2);
            }
        }

    	Cache::forget('gb-email_template');
        \Session::flash('success', __('Successfully Saved'));
         return redirect()->intended("customer-sms-temp/$id");
    	}
       
    }
}
