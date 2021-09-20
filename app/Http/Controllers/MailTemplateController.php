<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Language;
use App\Models\EmailTemplate;
use Cache;
use DB;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  index()
    {
        $data['menu'] = 'setting';
        $data['list_menu'] = 'mail_temp';
        
        return view('admin.mailTemp.temp_list', $data);
    }

    public function customerInvTemp($id)
    {
        $invalidEmailTemplateId = EmailTemplate::where(['template_id' => $id, 'template_type' => 'email'])->first();

        if (!empty($invalidEmailTemplateId)) {
            $data['menu'] = 'setting';
            $data['sub_menu'] = 'mail-temp';
            $data['page_title'] = __('Email Templates');
            $data['list_menu'] = 'menu-'.$id;
            $data['tempId'] = $id;
            
            // Please check the below if it is equivalent to above joining
            $data['temp_Data']  = EmailTemplate::with(['language' => function ($query) 
            {
                $query->where(['status'=>'Active']);
            }])->where(['template_type' => 'email','template_id'=>$id])->get();
            // check ENDS
            $data['languages'] = Language::where(['languages.status'=>'Active'])->orderBy('id')->get();
            return view('admin.mailTemp.customer_invoice', $data);
        } else {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        unset($request['_wysihtml5_mode']);
        unset($request['_token']);
        unset($request['nthLoop']);
        foreach ($request->all() as $key =>  $value) {
            $lang[] = $key;
            $lang_id[] = $value['id'];
            unset($value['id']);
            $data[] = $value;
        }
        for ($i=0; $i < count($lang_id); $i++) {
            $check = DB::table('email_templates')
                    ->where('language_id','=',$lang_id[$i])
                    ->where('template_id','=',$id)
                    ->where('template_type','=','email')
                    ->first();
            if ($check) {
                DB::table('email_templates')->where([['template_id',$id],['language_id', $lang_id[$i]],['template_type','email']])->update($data[$i]);
            } else {
                $data2['template_id'] = $id;
                $data2['subject'] = $data[$i]['subject'];
                $data2['body'] = $data[$i]['body'];
                $data2['language_short_name'] = $lang[$i];
                $data2['template_type'] = 'email';
                $data2['language_id'] = $lang_id[$i];

                DB::table('email_templates')->insert($data2);
            }
        }
        Cache::forget('gb-email_template');
        \Session::flash('success', __('Successfully updated'));
         return redirect()->intended("customer-invoice-temp/$id");
        
    }
}
