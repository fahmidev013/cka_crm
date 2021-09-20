<?php

namespace App\Http\Controllers\CustomerAuth;

use DB;
use Auth;
use Cache;
use Cookie;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\{
    Customer,
    Preference,
    CaptchaConfiguration
};
use App\Rules\Recaptcha;
use Session;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/customer/dashboard';
    protected $guard = 'customer';
    protected $data = ['page_title' => 'Login In'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ckname = explode("_", Auth::getRecallerName())[2];
        $this->middleware('guest:customer')->except('logout');
    }

    public function showLoginForm()
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
            if ($rememberedUser[1] == 'customer' && Auth::guard('customer')->loginUsingId($rememberedUser[0])) {
                $ckkey = encrypt($this->ckname.Auth::guard('customer')->user()->id.".customer");
                Cookie::queue($this->ckname, $ckkey, 2592000);
                return redirect()->intended('customer/dashboard');
            }
        }

        return view('customer.login', $this->data);
    }
    
    public function login(Request $request)
    {
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $captchaCredentials = CaptchaConfiguration::getAll()->first();
        $this->validate($request, [
            'email' => 'required|email|exists:customers',
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

        $data = $request->only('email', 'password');
        $customerExist = Customer::where(['email' => $request->email, 'is_active' => 0])->exists();
        if ($customerExist) {
            Session::flash('fail', __('Account has been deactivated.'));
            return redirect()->back();
        }
        $unverifiedCustomer = Customer::where('email', $request->email)->first();
        if (!empty($unverifiedCustomer) && $unverifiedCustomer->is_verified == 0) {
            Session::flash('fail', __('The account has to be verified.'));
            return redirect()->back();
        }
        if (empty($unverifiedCustomer)) {
            Session::flash('fail', __('Unregistered user.'));
            return redirect()->back();
        }
        if (Auth::guard('customer')->attempt($data)) {
            if (!is_null($request->remember)) {
                $ckkey = encrypt($this->ckname.Auth::guard('customer')->user()->id.".customer");
                Cookie::queue($this->ckname, $ckkey, 2592000);
            }
            return redirect('customer/dashboard');
        }
        return back()->withInput()->withErrors(['email' => __("Invalid email or password")]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $cookie = Cookie::forget($this->ckname);
        Cache::forget('gb-dflt_timezone_customer'.Auth::guard('customer')->user()->id);
        Auth::guard('customer')->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/customer')->withCookie($cookie);
    }

    protected function guard(){
        return Auth::guard('customer');
    }
}
