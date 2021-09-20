<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Models\Customer;
use App\Models\Preference;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/customer/dashboard';
    protected $guard = 'customer';
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }

    public function showLoginForm()
    {
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        $data = [
            'companyData' => $prefer['company_name'],
            'title' => 'Login In'
        ];

        return view('customer.login',$data);
    }

    public function login(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $data = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($data, $request->remember)) {
            return redirect()->intended('/customer/dashboard');
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
        Auth::guard('customer')->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/customer');
    }
}
