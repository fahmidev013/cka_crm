<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmailController;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\{
    Account,
    Department,
    File,
    Language,
    PaymentGateway,
    EmailConfiguration,
    SmsConfig,
    Currency,
    Transaction,
    Preference,
    PaymentMethod,
    Country,
    SaleType,
    PaymentTerm,
    UserDepartment,
    SaleOrder,
    PurchaseOrder,
    Ticket,
    UrlShortner,
    Customer,
    Supplier,
    IpAddress,
    CaptchaConfiguration,
    CurrencyConverterConfiguration
};
use Config;
use DB;
use Illuminate\Http\Request;
use Image;
use Mail;
use Session;
use Cache;
use Exception;
use Validator;
use App\Rules\CheckValidEmail;

class SettingController extends Controller
{
    public function __construct(EmailController $email)
    {
        $this->email = $email;
    }


    public function mailTemp()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'mail-temp';
        $data['tempData'] = DB::table('email_temp')->get();

        return view('admin.mailTemp.temp_list', $data);
    }

    public function finance()
    {
        $data['menu'] = 'setting';
        $data['page_title']   = __('Finance');
        $data['sub_menu'] = 'finance';
        $data['list_menu'] = 'tax';

        return view('admin.setting.finance', $data);
    }

    public function currency()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'finance';
        $data['page_title']   = __('Currencies');
        $data['list_menu'] = 'currency';
        $data['currencyData'] = Currency::getAll();
        $data['currencyConverter'] = CurrencyConverterConfiguration::getAll()->where('status', 'active')->count();
        $data['default_currency'] = Currency::select('currencies.*','preferences.value')
                                        ->where(['preferences.category' => 'company', 'preferences.field' => 'dflt_currency_id'])
                                        ->leftjoin('preferences', 'preferences.value', '=' , 'currencies.id')->first();
        return view('admin.setting.currency', $data);
    }

    public function validCurrencyName(Request $request)
    {
        $query = DB::table('currencies')->where('name', $_GET['name']);
        if (isset($_GET['currency_id']) && !empty($_GET['currency_id'])) {
            $query->where('id', "!=", $_GET['currency_id']);
        }
        $result = $query->first();

        if (!empty($result)) {
            echo json_encode(__('Currency name is already taken.'));
        } else {
            echo "true";
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|min:2',
            'symbol'        => 'required',
            'exchange_rate' => 'required',
            'exchange_from' => 'required',
        ]);
        $currencyConverter = CurrencyConverterConfiguration::getAll()->where('status', 'active')->count();
        if ($request->exchange_from == "api" && $currencyConverter == 0) {
            Session::flash('fail', __('No currency converter is active.'));
            return redirect()->intended('currency');
        }
        // insert
        $newCurrency    = new Currency();
        $newCurrency->name            = $request->name;
        $newCurrency->symbol          = $request->symbol;
        $newCurrency->exchange_rate   = validateNumbers($request->exchange_rate);
        $newCurrency->exchange_from   = $request->exchange_from;
        $newCurrency->save();
        $id = $newCurrency->id;

        Cache::forget('gb-currency');

        if (! empty($id)) {
            Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('currency');
        } else {
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $result = [];
        if (isset($request->id) && !empty($request->id)) {
            $currData = Currency::find($request->id);
            $result['currencyConverter'] = CurrencyConverterConfiguration::getAll()->where('status', 'active')->count();
            $result['name']          = $currData->name;
            $result['symbol']        = $currData->symbol;
            $result['exchange_rate'] = $currData->exchange_rate;
            $result['id']            = $currData->id;
            $result['exchange_from'] = $currData->exchange_from;
        }
        echo json_encode($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'symbol' => 'required',
            'exchange_rate' => 'required',
            'exchange_from' => 'required',
            'curr_id' => 'required',
        ]);

        $currencyConverter = CurrencyConverterConfiguration::getAll()->where('status', 'active')->count();
        if ($request->exchange_from == "api" && $currencyConverter == 0) {
            Session::flash('fail', __('No currency converter is active.'));
            return redirect()->intended('currency');
        }

        $id = $request->curr_id;
        $currencyToUpdate               = Currency::find($id);
        $currencyToUpdate->name         = $request->name;
        $currencyToUpdate->symbol       = $request->symbol;
        $currencyToUpdate->exchange_rate= validateNumbers($request->exchange_rate);
        $currencyToUpdate->exchange_from= $request->exchange_from;
        $currencyToUpdate->save();

        Cache::forget('gb-currency');

        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('currency');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $requsest)
    {
        $id = $requsest->id;
        $currencyTrans = Transaction::getCurrencyTransection($requsest->id);
        if (!isset($currencyTrans->id)) {
            $record = Currency::find($id);
            $customerCurrency = Customer::where('currency_id', $id)->exists();
            $supplierCurrency = Supplier::where('currency_id', $id)->exists();
            if ($customerCurrency === false && $supplierCurrency === false && !empty($record)) {
                $pref = Preference::getAll()->where('category', 'company')->where('field', 'dflt_currency_id')->pluck('value', 'field')->toArray();
                if ($pref['dflt_currency_id'] != $id) {
                    $record->delete();
                    Cache::forget('gb-currency');
                    $data = [ 'type' => 'success', 'message' => __('Deleted Successfully.')];
                } else {
                    $data = [ 'type' => 'fail', 'message' => __('Can not be deleted. This is default currency.')];
                }
            } else {
                $data = [ 'type' => 'fail', 'message' => __('Can not be deleted. This currency has records.')];
            }
        } else {
            $data = [ 'type' => 'danger', 'message' => __('Can not be deleted. This currency has records.')];
        }

        Session::flash($data['type'], $data['message']);
        return redirect()->intended('currency');
    }

    public function preference(Request $request)
    {
        $data = [];

        $data['menu'] = 'setting';
        $data['sub_menu'] = 'preference';
        $data['page_title']   = __('General Preferences');
        $data['list_menu'] = 'general_preference';
        $data['currencyData'] = Currency::getAll();
        $pref                 = Preference::getAll()->where('category', 'preference')->pluck('value', 'field')->toArray();
        $data['prefData']['preference'] = $pref;

        if (Helpers::has_permission(\Auth::user()->id, 'manage_company_setting')) {
            return view('admin.setting.preference', $data);
        }

        return redirect(getRouteAccordingToPermission([
                'manage_theme_preference' => 'setting-appearance'
            ]));
    }

    public function appearance()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'preference';
        $data['page_title']   = __('Theme Preferences');
        $data['list_menu'] = 'theme_preference';
        $themePreferences = Preference::getAll()->where('field', 'theme_preference')->where('category', 'preference')->first();
        $themeOptions = [
            'theme_mode' => 'default',
            'menu_brand_background' => 'default',
            'menu_background' => 'default',
            'menu_item_color' => 'default',
            'navbar_image' => 'default',
            'menu-icon-colored' => 'default',
            'menu-fixed' => 'default',
            'menu_list_icon' => 'default',
            'menu_list_icon' => 'default',
            'menu_dropdown_icon' => 'default',
            'header_background' => 'default',
            'theme_layout' => 'default'
        ];

        if (!empty($themePreferences['value'])) {
            $themeOptions = array_merge($themeOptions, json_decode($themePreferences['value'], true));
        }
        $data['appear'] = $themeOptions;
        return view('admin.setting.appearance', $data);
    }

    public function savePreference(Request $request)
    {
        $this->validate($request, [
            'file_size' => 'required|integer|min:1',
        ]);
        $post = $request->all();
        unset($post['_token']);

        if ($post['date_format'] == 0) {
            $post['date_format_type'] = 'yyyy' . $post['date_sepa'] . 'mm' . $post['date_sepa'] . 'dd';
        } elseif ($post['date_format'] == 1) {
            $post['date_format_type'] = 'dd' . $post['date_sepa'] . 'mm' . $post['date_sepa'] . 'yyyy';
        } elseif ($post['date_format'] == 2) {
            $post['date_format_type'] = 'mm' . $post['date_sepa'] . 'dd' . $post['date_sepa'] . 'yyyy';
        } elseif ($post['date_format'] == 3) {
            $post['date_format_type'] = 'dd' . $post['date_sepa'] . 'M' . $post['date_sepa'] . 'yyyy';
        } elseif ($post['date_format'] == 4) {
            $post['date_format_type'] = 'yyyy' . $post['date_sepa'] . 'M' . $post['date_sepa'] . 'dd';
        }

        $i = 0;
        foreach ($post as $key => $value) {
            $data[$i]['category'] = "preference";
            $data[$i]['field'] = $key;
            $data[$i++]['value'] = $value;
        }
        foreach ($data as $key => $value) {
            $category = $value['category'];
            $field    = $value['field'];
            $val      = $value['value'];
            $res      = Preference::getAll()->where('field', $field)->count();
            if ($res == 0) {
                $newPreference= new Preference();
                $newPreference->category = $category;
                $newPreference->field    = $field;
                $newPreference->value    = $val;
                $newPreference->save();
            } else {
                $preferenceToUpdate = Preference::where('category', 'preference')
                                                ->where('field', $field)
                                                ->update(['value' => $val]);
            }
        }
        Cache::forget('gb-preferences');
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        if (!empty($prefer)) {
            Session::put($prefer);
        }
        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('setting-preference');
    }

    public function backupDB()
    {
        $backup_name    = backup_tables(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
        $checkExtension = explode(".", $backup_name);
        if ($checkExtension[count($checkExtension) - 1] == 'sql') {
            DB::table('backups')->insert(['name' => $backup_name, 'created_at' => date('Y-m-d H:i:s')]);
            \Session::flash('success', __('Successfully Saved'));
        } else {
            \Session::flash('fail', __('Enable Write Permission'));
            return redirect()->back();
        }
        return redirect()->intended('backup/list');
    }

    public function backupList()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title']   = __('Backups');
        $data['list_menu'] = 'backup';
        $data['path'] = storage_path();
        $data['backupData'] = DB::table('backups')->orderBy('id', 'desc')->get();
        return view('admin.setting.backupList', $data);
    }

    public function emailSetup()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title']   = __('Email Setup');
        $data['list_menu'] = 'email_setup';
        $data['emailConfigData'] = EmailConfiguration::first();
        return view('admin.setting.emailConfig', $data);
    }

    public function emailSaveConfig(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = ['status' => 'fail', 'message' => __('Invalid Request')];
            if (isset($request->type) && array_key_exists(strtolower($request->type), ['smtp' => 'smtp', 'sendmail' => 'sendmail'])) {
                $request['protocol'] = strtolower($request->type);
                $request['status'] = $request->type == 'smtp' ? 1 : 0;
            }
            $validator =  EmailConfiguration::validation($request->all());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($request->type == 'smtp') {
                /* Start Checking SMTP */
                Config::set([
                    'mail.driver'     => isset($request->protocol) ? $request->protocol : '',
                    'mail.host'       => isset($request->smtp_host) ? $request->smtp_host : '',
                    'mail.port'       => isset($request->smtp_port) ? $request->smtp_port : '',
                    'mail.from'       => ['address' => isset($request->from_address) ? $request->from_address : '',
                    'name'            => isset($request->from_name) ? $request->from_name : '' ],
                    'mail.encryption' => isset($request->encryption) ? $request->encryption : '',
                    'mail.username'   => isset($request->smtp_username) ? $request->smtp_username : '',
                    'mail.password'   => isset($request->smtp_password) ? $request->smtp_password : ''
                ]);
            }
            try {
                if (isset($request->protocol) && $request->smtp_password == "xxxxxxxxxxxx") {
                    $request['smtp_password'] = EmailConfiguration::getAll()->where('id', 1)->first()->smtp_password;
                }

                if ((new EmailConfiguration)->store($request->except('_token', 'type'))) {
                    $data['status'] = 'success';
                    $data['message'] = __('Successfully Saved');
                }
            } catch (\Exception $e) {
                $data['message'] = $e->getMessage();
            }

            Session::flash($data['status'], $data['message']);
            return redirect()->intended('email/setup');
        }
    }

    public function paymentTerm()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'finance';
        $data['page_title']   = __('Payment Terms');
        $data['list_menu'] = 'payment_term';
        $data['paymentTermData'] = PaymentTerm::getAll();
        return view('admin.payment.paymentTerm', $data);
    }

    public function validPaymentTerm(Request $request)
    {
        $query = DB::table('payment_terms')->where('name', $_GET['terms']);
        if (isset($_GET['term_id']) && !empty($_GET['term_id'])) {
            $query->where('id', "!=", $_GET['term_id']);
        }
        $result = $query->first();

        if (!empty($result)) {
            echo json_encode(__('That Payment Term is already taken.'));
        } else {
            echo "true";
        }
    }

    public function addPaymentTerms(Request $request)
    {
        $this->validate($request, [
            'terms' => 'required',
            'days_before_due' => 'required|integer|min:0',
        ]);
        if ($request->defaults == 1) {
            $defaultToUpdate = PaymentTerm::where('is_default', 1)->first();
            if (!empty($defaultToUpdate)) {
                $defaultToUpdate->is_default  = 0;
                $defaultToUpdate->save();
            }
        }
        // insert
        $newPaymentTerm = new PaymentTerm();
        $newPaymentTerm->name  = $request->terms;
        $newPaymentTerm->days_before_due  = $request->days_before_due;
        $newPaymentTerm->is_default  = $request->defaults;
        $newPaymentTerm->save();

        Cache::forget('gb-payment_terms');

        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('payment/terms');
    }

    public function editPaymentTerms(Request $request)
    {
        $result = [];
        if (!empty($request->id)) {
            $termData = PaymentTerm::find($request->id);

            $result['id'] = $termData->id;
            $result['terms'] = $termData->name;
            $result['days_before_due'] = $termData->days_before_due;
            $result['defaults'] = $termData->is_default;
        }
        echo json_encode($result);
    }

    public function updatePaymentTerms(Request $request)
    {
        $this->validate($request, [
            'terms' => 'required',
            'days_before_due' => 'required|integer|min:0',
        ]);
        $id = $request->id;
        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);

        if ($request->defaults == 1) {
            $defaultToUpdate = PaymentTerm::where('is_default', 1)->first();
            if (!empty($defaultToUpdate)) {
                $defaultToUpdate->is_default  = 0;
                $defaultToUpdate->save();
            }
        }
        // update
        $paymentTermToUpdate                  = PaymentTerm::find($id);
        $paymentTermToUpdate->name            = $data['terms'];
        $paymentTermToUpdate->days_before_due = $data['days_before_due'];
        $paymentTermToUpdate->is_default      = $data['defaults'];
        $paymentTermToUpdate->save();

        Cache::forget('gb-payment_terms');

        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('payment/terms');
    }

    public function deletePaymentTerm($id)
    {
        $data = [
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $record = PaymentTerm::find($id);
        if (!empty($record)) {
            $quotationTerm = SaleOrder::where('payment_term_id', $id)->exists();
            $purchaseTerm = PurchaseOrder::where('payment_term_id', $id)->exists();
            if ($quotationTerm === true ||  $purchaseTerm === true) {
                $data['message'] = __('Can not be deleted. This term has records.');
            } else {
                if ($record->delete()) {
                    $data = [
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                }
            }
        }
        Cache::forget('gb-payment_terms');
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('payment/terms');

    }

    public function paymentMethod()
    {
        $data['menu']              = 'setting';
        $data['sub_menu']          = 'finance';
        $data['page_title']        = __('Payment Methods');
        $data['list_menu']         = 'payment_method';
        $data['paymentMethodData'] = PaymentMethod::getAll();
        $data['accounts'] = Account::where('is_deleted', '!=', 1)->get();
        $tranMethodData            = Transaction::all('payment_method_id');
        $result = [];
        foreach ($tranMethodData as $key => $value) {
            $result[] = $value->payment_method;
        }

        $data['payMeth']     = !empty($result) ? $result : '';
        return view('admin.payment.paymentMethod', $data);
    }

    public function validPaymentMethod(Request $request)
    {
        $query = DB::table('payment_methods')->where('name', $_GET['name']);
        if (isset($_GET['method_id']) && !empty($_GET['method_id'])) {
            $query->where('id', "!=", $_GET['method_id']);
        }
        $result = $query->first();

        if (!empty($result)) {
            echo json_encode(__('That Payment Method is already taken.'));
        } else {
            echo "true";
        }
    }

    public function editPaymentMethod(Request $request)
    {
        $result = [];
        if (!empty($request->id)) {
            $methodData = PaymentMethod::find($request->id);

            $result['id'] = $methodData->id;
            $result['name'] = $methodData->name;
            $result['defaults'] = $methodData->is_default;
            $result['is_active'] = $methodData->is_active;
        }
        echo json_encode($result);
    }

    public function updatePaymentMethod(Request $request)
    {
        $data = [
                    'type'    => 'fail',
                    'message' => __('Opps!, Bank name can not be edited.')
                ];

        $id = $request->id;
        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);

        $paymentMethodToUpdate   = PaymentMethod::find($id);

        if ($request->defaults == 1 && $paymentMethodToUpdate->is_default != 1) {
            $defaultToUpdate    = PaymentMethod::where('is_default', 1)->first();
            if (! empty($defaultToUpdate)) {
                $defaultToUpdate->is_default = 0;
                $defaultToUpdate->save();
            }
        }

        if ($paymentMethodToUpdate->name == 'Bank' && $data['name'] != 'Bank') {
            $paymentMethodToUpdate->name = 'Bank';
            $data = [
                    'type'    => 'fail',
                    'message' => __('Opps!, Bank name can not be edited.')
                ];
        } else {
            $paymentMethodToUpdate->name = $data['name'];
            $data = [
                    'type'    => 'success',
                    'message' => __('Successfully updated')
                ];
        }

        $paymentMethodToUpdate->is_default = $request->defaults;
        $paymentMethodToUpdate->is_active = $request->status;
        $paymentMethodToUpdate->save();
        Cache::forget('gb-payment_methods');

        Session::flash($data['type'], $data['message']);
        return redirect()->intended('payment/method');
    }

    public function paymentMethodSettings(Request $request)
    {
        $id = $request->id;
        $result = [];
        if (!empty($id)) {
            $methodData = PaymentMethod::find($id);
            if ($id == 1) {
                $result['client_id'] = $methodData->client_id;
                $result['mode'] = $methodData->mode;
                $result['consumer_secret'] = $methodData->consumer_secret;
            } else if ($id == 3) {
                $result['consumer_key'] = $methodData->consumer_key;
                $result['consumer_secret'] = $methodData->consumer_secret;
            } else {
                $account = Account::find($methodData->client_id);
                $result['bank'] = !empty($account->bank_name) ? $account->bank_name : '-';
                $result['branch'] = !empty($account->branch_name) ? $account->branch_name : '-';
                $result['city'] = !empty($account->branch_city) ? $account->branch_city : '-';
                $result['code'] = !empty($account->swift_code) ? $account->swift_code : '-';
                $result['address'] = !empty($account->bank_address) ? $account->bank_address : '-';
                $result['id'] = $methodData->client_id;
                $result['approve'] = $methodData->approve;
            }
        }
        echo json_encode($result);
    }

    public function updatePaymentMethodSettings(Request $request)
    {
        $data = [
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];

        if (!empty($request->paymentId)) {
            try {
                DB::beginTransaction();
                if (!empty($request->client_id) && $request->paymentId == 1 ) {
                    $value = $request->client_id;
                } else if (!empty($request->account) && $request->paymentId == 2 ) {
                    $value = $request->account;
                } else {
                    $value = null;
                }
                $paymentMethod = PaymentMethod::where('id', $request->paymentId)
                                ->update([
                                    'mode' => (!empty($request->modeVal) && $request->paymentId == 1)? $request->modeVal : null,
                                    'client_id' => $value,
                                    'consumer_key' => (!empty($request->consumer_key) && $request->paymentId == 3) ? $request->consumer_key : null,
                                    'consumer_secret' => ($request->paymentId == 1 || $request->paymentId == 3) ? $request->consumer_secret : null,
                                    'approve' => in_array($request->approval, ['auto', 'manual']) ? $request->approval : null
                                ]);
                DB::commit();
                Cache::forget('gb-payment_methods');
                $data = [
                    'type'    => 'success',
                    'message' => __('Successfully updated')
                ];
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }

        Session::flash($data['type'], $data['message']);
        return redirect()->intended('payment/method');
    }

    public function companySetting()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['page_title']   = __('Company Settings');
        $data['list_menu'] = 'sys_company';
        $data['paymentMethodData'] = PaymentMethod::getAll();
        $data['countries'] = Country::getAll();
        $data['currencyData'] = Currency::getAll();
        $data['saleTypes'] = SaleType::getAll();
        $pref              = Preference::getAll()
                                ->where('category', 'company')
                                ->pluck('value', 'field')
                                ->toArray();
        $data['languages'] = Language::where('status', 'Active')->orderBy('name')->get();
        $data['companyData']["company"] = $pref;
        $data['companyData']['imgDir'] = "public/uploads/companyPic";
        $data['companyData']['icon'] = "public/uploads/companyIcon";

        if (Helpers::has_permission(\Auth::user()->id, 'manage_company_setting')) {
            return view('admin.setting.companySetting', $data);
        }

        return redirect(getRouteAccordingToPermission([
                'manage_department' => 'department-setting',
                'manage_role'       => 'role/list',
                'manage_location'   => 'location'
            ]));
    }

    public function companySettingSave(Request $request)
    {

        $this->validate($request, [
            'company_logo' => 'max:2048',
        ]);

        $post = $request->all();
        unset($post['_token']);
        unset($post['company_logo']);
        unset($post['company_icon']);
        $updatedLogo  = false;
        $updatedIcon  = false;
        $path = createDirectory("public/uploads/companyPic");

        if (!empty($request->file('company_logo'))) {
          $data['companyLogo']     = Preference::getAll()->where('field', 'company_logo')->where('category', 'company')->first();

          $updatedLogo = (new File)->store([$request->file('company_logo')], $path, 'Company', $data['companyLogo']->id, ['isUploaded' => false, 'isOriginalNameRequired' => true, 'size' => [80, 80]]);
        }

        if (!empty($updatedLogo)) {
            $lastUploadedLogo = File::find($updatedLogo[0]);
            if (!empty($lastUploadedLogo)) {
                $updatedPreference = Preference::updateOrCreate(
                                        ['category' => 'company', 'field' => 'company_logo'],
                                        ['category' => 'company', 'field' => 'company_logo', 'value' => $lastUploadedLogo->file_name]
                                    );
                $changes = $updatedPreference->getChanges();
                if (!empty($changes) && $changes['value'] == $lastUploadedLogo->file_name) {
                    $result = (new File)->deleteFiles('Company', $updatedPreference->id, ['ids' => [$lastUploadedLogo->id], 'isExcept' => true], $path);
                }
            }
        }

        if (!empty($request->file('company_icon'))) {
          $pathOfIcon = createDirectory("public/uploads/companyIcon");
          $data['companyIcon']     = Preference::getAll()->where('field', 'company_icon')->where('category', 'company')->first();

          $updatedIcon = (new File)->store([$request->file('company_icon')], $pathOfIcon, 'Company', $data['companyIcon']->id, ['isUploaded' => false, 'isOriginalNameRequired' => true]);
        }
        if (!empty($updatedIcon)) {
            $lastUploadedIcon = File::find($updatedIcon[0]);
            if (!empty($lastUploadedIcon)) {
                $updatedPreference = Preference::updateOrCreate(
                                        ['category' => 'company', 'field' => 'company_icon'],
                                        ['category' => 'company', 'field' => 'company_icon', 'value' => $lastUploadedIcon->file_name]
                                    );
                $changes = $updatedPreference->getChanges();
                if (!empty($changes) && $changes['value'] == $lastUploadedIcon->file_name) {
                    $result = (new File)->deleteFiles('Company', $updatedPreference->id, ['ids' => [$lastUploadedIcon->id], 'isExcept' => true], $path);
                }
            }
        }
        unset($data);

        $i = 0;
        foreach ($post as $key => $value) {
            $data[$i]['category'] = 'company';
            $data[$i]['field'] = $key;
            if ($key =='company_logo') {
                $data[$i]['value'] = $filename;
            } else if ($key =='company_icon') {
                $data[$i]['value'] = isset($iconName) ? $iconName : '';
            } else {
                $data[$i]['value'] = $value;
            }
            $i++;
        }
        foreach ($data as $key => $value) {
            $category = $value['category'];
            $field    = $value['field'];
            $val      = $value['value'];
            $res      = Preference::getAll()->where('field', $field)->count();
            if ($res == 0) {
                $newPreferenceToInsert              = new Preference();
                $newPreferenceToInsert->category    = $category;
                $newPreferenceToInsert->field       = $field;
                $newPreferenceToInsert->value       = $val;
                $newPreferenceToInsert->save();
            } else {
                $preferenceToUpdate = Preference::where('category', 'company')
                                        ->where('field', $field)
                                        ->update(['field' => $field, 'value' => $val]);
            }
        }
        Cache::forget('gb-preferences');
        Cache::forget('gb-defaultCurrency');
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();

        if (!empty($prefer)) {
            $curr = Currency::getDefault();
        }
        if ($request->dflt_lang) {
            $language   = Language::where('is_default', 1)->first();
            $language->is_default  = 0;
            $language->update();

            $updatelanguage = Language::where('short_name', $request->dflt_lang)->first();
            $updatelanguage->is_default    = 1;
            $updatelanguage->update();
        }
        Cache::forget('gb-preferences');
        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('company/setting');
    }

    public function deleteImage(Request $request)
    {
        $logo = $request->company_logo;
        if (isset($logo)) {
            $record = Preference::getAll()
                                ->where('category', 'company')
                                ->where('field', 'company_logo')
                                ->first();
            if (!empty($record)) {
                $record->value = '';
                $record->save();
                Cache::forget('gb-preferences');
                if (!empty($logo)) {
                    $dir = public_path("uploads/companyPic/". $logo);
                    if (file_exists($dir)) {
                       unlink($dir);
                    }
                }
                $data['success']  = 1;
                $data['message'] = __('Deleted Successfully.');
            } else {
                $data['success']  = 0;
                $data['message'] = __('No record found');
            }
        }
        echo json_encode($data);
        exit();
    }

    public function destroyBackup($id)
    {
        if (isset($id)) {
            $record = DB::table('backups')->where('id', $id)->first();
            if ($record) {
                DB::table('backups')->where('id', '=', $id)->delete();
                Session::flash('success', __('Deleted Successfully.'));
                if (isset($record->name) && !empty($record->name) && file_exists(storage_path("laravel-backups/" . $record->name))) {
                    unlink(storage_path("laravel-backups/" . $record->name));
                }
            }
        }
        return redirect()->intended('backup/list');
    }


    public function smsSetup(Request $request)
    {
        if (!$request->all()) {
            $data['menu'] = 'setting';
            $data['sub_menu'] = 'general';
            $data['page_title']   = __('SMS Setup');
            $data['list_menu'] = 'sms_setup';
            $data['smsConfig'] = SmsConfig::where('type', 'twilio')->first();

            return view('admin.setting.smsConfig', $data);
        } else {
            $data['type']       = $request->type;
            $data['key']        = $request->key;
            $data['secretkey']  = $request->secret_key;
            $data['default']    = $request->default;
            $data['default_number']  = $request->number;
            $data['status']     = $request->status;

            $smsConfig = SmsConfig::where('type', 'twilio')->first();
            if (!empty($smsConfig)) {
                $smsConfig->type      = $data['type'];
                $smsConfig->key       = $data['key'];
                $smsConfig->secretkey = $data['secretkey'];
                $smsConfig->default   = $data['default'];
                $smsConfig->default_number = $data['default_number'];
                $smsConfig->status    = $data['status'];
                $smsConfig->save();
                Cache::forget('gb-url_shortner');
            } else {
                $NewSmsConfig = new SmsConfig();
                $NewSmsConfig->type      = $data['type'];
                $NewSmsConfig->key       = $data['key'];
                $NewSmsConfig->secretkey = $data['secretkey'];
                $NewSmsConfig->default   = $data['default'];
                $NewSmsConfig->default_number = $data['default_number'];
                $NewSmsConfig->status    = $data['status'];
                $NewSmsConfig->save();
                Cache::forget('gb-url_shortner');
            }

            Session::flash('success', __('Successfully Saved'));
            return redirect()->back();
        }
    }

    public function shortUrlSetup(Request $request)
    {
        // If $_POST not set then retrive data from database
        if (empty($request->all())) {
            $data['menu'] = 'setting';
            $data['sub_menu'] = 'general';
            $data['page_title']   = __('URL Shortner');
            $data['list_menu'] = 'short_url_setup';
            $data['shortUrlConfigs'] = UrlShortner::all();

            return view('admin.setting.shortUrlConfig', $data);
        } else {
            $data['type']       = $request->type;
            $data['key']        = $request->key;
            $data['secretkey']  = $request->secret_key;
            $data['default']    = $request->default;
            $data['status']     = $request->status;
            $data['messageStatus'] = 'error';
            $data['message'] = __('Permission Denied');

            $shortUrlConfig = UrlShortner::find($request->id);
            // If data exist then update, else create new one
            if (Helpers::has_permission(Auth::user()->id, 'edit_url_shortner')) {
                if (!empty($shortUrlConfig)) {
                    $shortUrlConfig->key       = $data['key'];
                    $shortUrlConfig->secretkey = $data['secretkey'];

                    $urlConfigDefault   = UrlShortner::where('default', 'Yes')->first();
                    if ($data['default'] == 'Yes' && $shortUrlConfig->default != 'Yes' && (!empty($urlConfigDefault))) {
                        $urlConfigDefault->default  = 'No';
                        $urlConfigDefault->update();
                    }

                    $shortUrlConfig->default   = $data['default'];
                    $shortUrlConfig->status    = $data['status'];
                    $shortUrlConfig->save();
                } else {
                    $NewShortUrlConfig = new UrlShortner();
                    $NewShortUrlConfig->key       = $data['key'];
                    $NewShortUrlConfig->type      = $data['type'];
                    $NewShortUrlConfig->secretkey = $data['secretkey'];
                    $NewShortUrlConfig->default   = $data['default'];
                    $NewShortUrlConfig->status    = $data['status'];
                    $NewShortUrlConfig->save();
                }
                $data['messageStatus'] = 'success';
                $data['message'] = __('Successfully Saved');
            }
            Cache::forget('gb-url_shortner');
            Session::flash($data['messageStatus'], $data['message']);
            return redirect()->back();
        }
    }

    public function shortUrlCreate()
    {
        // Check long_url set or not, if not then return false
        if (isset($_GET['long_url'])) {
            $long_url = $_GET['long_url'];
            // Convert long_url into short URL
            $shortURL = UrlShortner::shortURL($long_url);
            return $shortURL;
        } else {
            return false;
        }
    }

    public function bank_gateway(Request $request)
    {
        $check = PaymentGateway::where(['name' => 'bank_account_id', 'site' => 'Bank'])->first();
        if ($check) {
            $data['value'] = $request->bank_id;
            $check->update($data);
        } else {
            $payment_gateway = new PaymentGateway();
            $payment_gateway->name = 'bank_account_id';
            $payment_gateway->value = $request->bank_id;
            $payment_gateway->site = 'Bank';
            $payment_gateway->save();
            Cache::forget('gb-payment_gateways');
        }

        Session::flash('success', __('Successfully Saved'));
        return redirect()->back();
    }

    public function deleteIcon(Request $request)
    {
        $icon             = $request->company_icon;

        if (isset($icon)) {
            $record = Preference::getAll()
                                ->where('category', 'company')
                                ->where('field', 'company_icon')
                                ->first();
            if (!empty($record)) {
                $record->value = '';
                $record->save();
                Cache::forget('gb-preferences');
                if (!empty($icon)) {
                    $dir = public_path("uploads/companyIcon/" . $icon);
                    if (file_exists($dir)) {
                       unlink($dir);
                    }
                }
                $data['success']  = 1;
                $data['message'] = __('Deleted Successfully.');
            } else {
                $data['success']  = 0;
                $data['message'] = __("No record found");
            }
        }
        echo json_encode($data);
        exit();
    }

    public function saveAppearance(Request $request)
    {
        $themePreferences = $request->except('_token');

        $themePreferencesJsonFormated = json_encode($themePreferences);
        Preference::updateOrCreate(
            ['category' => 'preference', 'field' => 'theme_preference'],
            ['category' => 'preference', 'field' => 'theme_preference', 'value' => $themePreferencesJsonFormated]
         );

        Cache::forget('gb-preferences');
        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('setting-appearance');
    }

    public function captchaSetup(Request $request)
    {
        $data = ['status' => 'fail', 'message' => __('Invalid Request')];
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title']   = __('Captcha Setup');
        $data['list_menu'] = 'captcha_setup';
        if ($request->isMethod('get')) {
            $data['captcha'] = CaptchaConfiguration::getAll()->first();
            return view('admin.setting.captcha', $data);
        }  else if ($request->isMethod('post')) {
            $validator =  CaptchaConfiguration::storeValidation($request->all());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            try {
                if ((new CaptchaConfiguration)->store($request->only('site_key', 'secret_key', 'site_verify_url', 'plugin_url'))) {
                    $data['status'] = 'success';
                    $data['message'] = __('Successfully Saved');
                }
            } catch (\Exception $e) {
                $data['message'] = $e->getMessage();
            }
            Session::flash($data['status'], $data['message']);
            return redirect()->intended('captcha/setup');
        }
    }

    public function currencyConverterSetup(Request $request)
    {
        $data = ['status' => 'fail', 'message' => __('Invalid Request')];
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title']   = __('Curreny Converter Setup');
        $data['list_menu'] = 'currency_converter';
        if ($request->isMethod('get')) {
            $config = CurrencyConverterConfiguration::getAll();
            $data['currency_converter_api'] = $config->where('slug', 'currency_converter_api')->first();
            $data['exchange_rate_api'] = $config->where('slug', 'exchange_rate_api')->first();
            return view('admin.setting.currencyConverter', $data);
        }  else if ($request->isMethod('post')) {
            if (isset($request->customRadio) && $request->customRadio == "on") {
                $validator =  CurrencyConverterConfiguration::storeValidation($request->all());
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
            } else {
                Session::flash('fail', __('Please setup currency converter API settings first.'));
                return redirect()->intended('currency-converter/setup');
            }
            try {
                $currency_converter_api = $exchange_rate_api = false;
                foreach ($request->only('currency_converter_api', 'exchange_rate_api') as $key => $value) {
                    if ($key == "currency_converter_api") {
                        $value['slug'] = "currency_converter_api";
                        $currency_converter_api = (new CurrencyConverterConfiguration)->store($value);
                    } else if ($key == "exchange_rate_api") {
                        $value['slug'] = "exchange_rate_api";
                        $exchange_rate_api = (new CurrencyConverterConfiguration)->store($value);
                    }
                }

                if ($currency_converter_api === true &&  $exchange_rate_api === true) {
                    $data['status'] = 'success';
                    $data['message'] = __('Successfully Saved');
                }
            } catch (\Exception $e) {
                $data['message'] = $e->getMessage();
            }
            Session::flash($data['status'], $data['message']);
            return redirect()->intended('currency-converter/setup');
        }
    }
}
