<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App;
use App\Models\Preference;
use Auth;
use Cache;
use App\Models\Language;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $langData = Preference::getAll()->where('field', 'dflt_lang')->first()->value;

        if (empty(Auth::user()->id) && isset(Auth::guard('customer')->user()->id) && Cache::get('gb-customer-language-'. Auth::guard('customer')->user()->id)) {
            $langData = Cache::get('gb-customer-language-'. Auth::guard('customer')->user()->id);
        }
        if (isset(Auth::user()->id) && Cache::get('gb-user-language-'. Auth::user()->id)) {
            $langData = Cache::get('gb-user-language-'. Auth::user()->id);
        }
        $language = Language::where(['short_name' => $langData, 'status' => 'Active'])->get();

        if (!empty($language)) {
            App::setLocale($langData);
            $direction = !empty($language[0]['direction']) ? $language[0]['direction'] : 'ltr';
            Cache::put('gb-language-direction', $direction, 600);
        } else {
            $langData = 'en';
            App::setLocale($langData);
            Cache::put('gb-language-direction', 'ltr', 600);
        }
        
        return $next($request);
    }
}
