<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Session;
use DB;
use Cache;
use Cookie;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\{
    User,
    Currency,
    Customer,
    EmailConfiguration,
    EmailTemplate,
    PasswordReset,
    Preference,
    CaptchaConfiguration
};

use Illuminate\Support\Facades\Password;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\EmailController;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    protected $data = ['page_title' => 'Login In'];

    // use AuthenticatesUsers;

    public function __construct(EmailController $email) {
        $this->email = $email;
        $this->ckname = explode("_", Auth::getRecallerName())[2];
        $this->middleware('guest:user')->except('logout');
    }
   
    /**
     * @return login page view
     */
    public function showLoginForm(Request $request)
    {
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $this->data = [
            'companyData' => $prefer['company_name'],
            'captchaCredentials' => CaptchaConfiguration::getAll()->first(),
            'captcha' => isset($prefer['captcha']) ? $prefer['captcha'] : '',
        ];
        $value = Cookie::get($this->ckname);
        if (! is_null($value)) {
            $rememberedUser = explode(".", explode($this->ckname, decrypt($value))[1]);
            if ($rememberedUser[1] == 'user' && Auth::guard('user')->loginUsingId($rememberedUser[0])) 
            {
                $this->setCacheCredentials();
                $ckkey = encrypt($this->ckname.Auth::user()->id.".user");
                Cookie::queue($this->ckname, $ckkey, 2592000);
                return redirect()->intended('dashboard');
            }
        }
        return view('auth.login', $this->data);
    }

    /**
     * Login authenticate operation.
     *
     * @return redirect dashboard page
     */
    public function authenticate(Request $request)
    {
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $captchaCredentials = CaptchaConfiguration::getAll()->first();
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required',
            'g-recaptcha-response' => [ 
                Rule::requiredIf(function () use ($prefer, $captchaCredentials) {
                    return (isset($prefer['captcha']) && $prefer['captcha'] == "enable" && isset($captchaCredentials->plugin_url) && isset($captchaCredentials->site_verify_url));
                }), 
                new ReCaptcha($captchaCredentials)
            ]
        ], [
            'g-recaptcha-response.required' => __('Captcha field is required.'),
        ]);

        $userData = User::where(['email' => $request->email])->first();
        if ($userData['is_active'] == 0) {
            return back()->withInput()->withErrors(['email' => "Inactive User"]);
        } 
        $data = $request->only('email', 'password');
        if (Auth::guard('user')->attempt($data)) {
            $this->setCacheCredentials();
            if (! is_null($request->remember)) {
                $ckkey = encrypt($this->ckname.Auth::user()->id.".user");
                Cookie::queue($this->ckname, $ckkey, 2592000);
            }
            return redirect()->intended('dashboard');
        }
        return back()->withInput()->withErrors(['email' => __("Invalid email or password")]);
    }

    /**
     * logout operation.
     *
     * @return redirect login page view
     */
    public function logout()
    {
        $cookie = Cookie::forget($this->ckname);
        Auth::guard('user')->logout();
        Session::flush();
        return redirect('/login')->withCookie($cookie);
    }

    /**
     * forget password
     *
     * @return forget password form
     */
    public function reset(Request $request)
    {
        $this->data = ['page_title' => __('Reset Password')];
        $this->data['type'] = $request->type;
        return view('auth.passwords.email', $this->data);
    }

    /**
     * Send reset password link
     *
     * @return Null
     */
    public function sendResetLinkEmail(Request $request)
    {
        $userName = null;
        $customerName = null;
        if ($request->type == 'admin') {
            $this->validate($request, [
                'email' => 'required|email|exists:users', 
            ]);
            $userName = User::where('email', $request->email)->first()->full_name;
        }
        if ($request->type == 'customer') {
            $this->validate($request, [
                'email' => 'required|email|exists:customers', 
            ]);
            $customerName = Customer::where('email', $request->email)->first()->name;
        }
        $data['email'] = $request->email;
        $data['token'] = Password::getRepository()->createNewToken();
        $data['created_at'] = date('Y-m-d H:i:s');

        \DB::beginTransaction();
        PasswordReset::updateOrCreate(
            ['email'        => $data['email']], 
            ['token'        => $data['token'], 'created_at'   => $data['created_at']]
        );
        $data['type'] = $request->type;
        
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['from_email'] = (!empty($prefer['company_email']) ? $prefer['company_email'] : 'support@example.com');
        $data['from_name']  = (!empty($prefer['company_name']) ? $prefer['company_name'] : 'support');

        $linkFormat = 'password/resets/%s/%s';
        $link = url(sprintf($linkFormat, $data['type'], $data['token']));
        // Retrive subject and body from email template
        $data['emailInfo'] = EmailTemplate::where(['template_id' => 17, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);
        $subject =  (!empty($data['emailInfo']['subject'])) ? $data['emailInfo']['subject'] : "Reset Password!";
        $message =  $data['emailInfo']['body'];
        // Replacing template variable
        if ($request->type == 'admin') {
            $message = str_replace('{user_name}', $userName, $message);
        } else {
            $message = str_replace('{user_name}', $customerName, $message);
        }
        $message = str_replace('{password_reset_url}', $link, $message);
        $message = str_replace('{company_name}', $prefer['company_name'], $message);
        $message = str_replace('{company_email}', $prefer['company_email'], $message);
        $message = str_replace('{company_phone}', $prefer['company_phone'], $message);
        $message = str_replace('{company_street}', $prefer['company_street'], $message);
        $message = str_replace('{company_city}', $prefer['company_city'], $message);
        $message = str_replace('{company_state}', $prefer['company_state'], $message);

        $emailResponse = $this->email->sendEmail($request->email, $subject, $message, null, $prefer['company_name']);

        if ($emailResponse['status'] == false) {
             \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
         }
        
        \DB::commit();
        Session::flash('success', __('Password reset link sent to your email address.'));
        return back()->withInput();
    }

    /**
     * showResetForm method
     * 
     * 
     * @param  array $request 
     * @param  string $type
     * @param  string $tokens
     * @return if $type & $tokens null and userrole is user the redirect login.reset otherwise show reset password page view
     */
    public function showResetForm(Request $request, $type = null, $tokens = null)
    {  
        $token = PasswordReset::where('token', $tokens)->first();
        if (empty($token)) {
            return redirect()->route('login.reset', $type)->withErrors(['email' => __("Invalid password token")]);
        }

        if ($token->token == null) {
            return redirect()->route('login.reset', $type)->withErrors(['email' => __("Token has expired.")]);
        }

        $data = ['token' => $token->token, 'type' => $type];

        switch ($type) {
            case 'admin':
                $data['user'] = User::where('email', $token->email)->first();
                break;
            case 'customer':
                $data['user'] = Customer::where('email', $token->email)->first(); 
                break;
            default:
                # code...
                break;
        }

        if (!$data['user']) {
            return redirect()->route('login.reset', $type)->withErrors(['email' => __("Invalid password token")]);
        }

        return view('auth.passwords.reset', $data);
    }

    /**
     *
     * @return redirect login page view
     */
    public function setPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6'
        ]);

        $data = ['password' => Hash::make(trim($request->password))];

        if ($request->type == 'admin' && User::find($request->id)->update($data) > 0) {
                PasswordReset::where('token', $request->token)->update(['token' => null]);
                Session::flash('success', __('Password reset successfully'));
                return redirect()->intended('/');
        }

        if ($request->type == 'customer' && Customer::find($request->id)->update($data) > 0) {
                PasswordReset::where('token', $request->token)->update(['token' => null]);
                Session::flash('success', __('Password reset successfully'));
                return redirect()->intended('/customer');
        }
    }


    public function sendPhpEmail($to, $subject, $message, $fromEmail, $fromName)
    {
        $this->email->sendEmail($to, $subject, $message);
    }

    private function setCacheCredentials()
    {
        $currencyId = Preference::getAll()->where('field', 'dflt_currency_id')->first()->value;
        $curr = Currency::getDefault();
    }
}
