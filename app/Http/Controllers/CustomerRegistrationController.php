<?php
namespace App\Http\Controllers;
use App\Http\Controllers\EmailController;
use App\Http\Requests;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\{
    Country,
    Customer,
    EmailConfiguration,
    EmailTemplate,
    Preference,
    CaptchaConfiguration
};
use DB;
use Session;
use Hash;
use Validator;
use Auth;
use Mail;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;

class CustomerRegistrationController extends Controller
{
    protected $guard = 'customer';
    public function __construct(Request $request, EmailController $email)
    {
        $this->email = $email;
    }

    public function create()
    {
        $data['countries']   = Country::getAll()->pluck('name', 'id')->toArray();
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['companyData'] = $prefer['company_name'];
        $data['captcha'] = isset($prefer['captcha']) ? $prefer['captcha'] : '';
        $data['captchaCredentials'] = CaptchaConfiguration::getAll()->first();

        return view('auth.customer_register', $data);
    }

    public function store(Request $request)
    {
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $captchaCredentials = CaptchaConfiguration::getAll()->first();
        $this->validate($request, [
            'email' => 'required | email | unique:customers,email',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required | min:6',
            'password_confirmation' => 'required | same:password',
            'country' => 'required',
            'g-recaptcha-response' => [ 
                Rule::requiredIf(function () use ($prefer, $captchaCredentials) {
                    return (isset($prefer['captcha']) && $prefer['captcha'] == "enable" && isset($captchaCredentials->plugin_url) && isset($captchaCredentials->site_verify_url));
                }), 
                new ReCaptcha($captchaCredentials)
            ]
        ], [
            'g-recaptcha-response.required' => __('This field is required.'),
        ]);
        
        $timeZone = Preference::getAll()->where('category', 'preference')
                                        ->where('field', 'default_timezone')->first()->value;
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name'      =>  $request->first_name .' '. $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'timezone' => $timeZone,
            'currency_id' => 1,
            'is_active' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertId = DB::table('customers')->insertGetId($data);
        if ($insertId) {
            $data2 = [
                'customer_id' => $insertId,
                'name' => $request->first_name .' '. $request->last_name,
                'billing_state' => !empty($request->state) ? $request->state : null,
                'billing_country_id' => $request->country
            ];
            DB::table('customer_branches')->insert($data2);
        }

        $customer_active = ['token_link' => str_random(30)];

        DB::table('customer_activations')->insert(['customer_id' => $insertId, 'token' => $customer_active['token_link']]);
        $emailData = EmailTemplate::where(['language_short_name' => 'en', 'template_id' => 25])->first();
        $subject = !empty ($emailData->subject) ? $emailData->subject : '';
        $subject = str_replace('{company_name}',  $prefer['company_name'], $subject);
        $body = !empty ($emailData->body) ? $emailData->body : '';
        $body = str_replace('{customer_name}',  $request->first_name .' '. $request->last_name, $body);
        $body = str_replace('{activation_link}',  url('customer/activation/' . $customer_active['token_link']), $body);
        $body = str_replace('{company_name}', $prefer['company_name'], $body);
        $body = str_replace('{company_email}', $prefer['company_email'], $body);
        $body = str_replace('{company_phone}', $prefer['company_phone'], $body);
        $body = str_replace('{company_street}', $prefer['company_street'], $body);
        $body = str_replace('{company_city}', $prefer['company_city'], $body);
        $body = str_replace('{company_state}', $prefer['company_state'], $body);

        $response = $this->email->sendEmail($request->email, $subject, $body, null, !empty($prefer['company_name']) ? $prefer['company_name'] : '');

        if ($response['status'] == false) {
            return redirect('/customer/register')->with('fail', __('Verification email can not be sent, please try agian.'));
        }
        return redirect('/customer')->with('success', __("We sent you an activation link. Check your email and click on the link to verify."));
    }

    public function sendPhpEmail($to, $subject, $message, $fromEmail, $fromName)
    {
        require 'vendor/autoload.php';
        $mail           = new PHPMailer(true);
        $mail->From     = $fromEmail;
        $mail->FromName = $fromName;
        $mail->AddAddress($to);
        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags("Message");
        $mail->Send();
    }

    public function customerActivation($token){
        $check = DB::table('customer_activations')->where('token', $token)->first();
        if (!is_null($check)) {
            $customer = Customer::find($check->customer_id);
            if (!is_null($customer)) {
                if ($customer->is_active == 0 || $customer->is_verified == 0) {
                    $customer->is_active = 1;
                    $customer->is_verified = 1;
                    $customer->save();
                    DB::table('customer_activations')->where('token', $token)->delete();
                    return redirect('/customer')->with('success', __("Success! Your email address has been verified."));
                }
            }
        }
        return redirect('/customer')->with('Warning', __("Your token is invalid."));
    }
}
