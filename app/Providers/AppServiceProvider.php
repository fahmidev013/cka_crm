<?php

namespace App\Providers;

use Config;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Schema;
use Validator;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Item;
use App\Models\Language;
use App\Models\File;
use App\Models\Preference;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\RoleUser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Check boot method is loaded or not.
     *
     * @var boolean
     */
    public $isBooted;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (! $this->app->runningInConsole() && env('APP_INSTALL') == true) {
            View::composer('*', function ($view) { 
                if (!$this->isBooted) {
                    $preference = Preference::getAll()->pluck('value', 'field')->toArray();
                    $currency   = Currency::getDefault($preference);
                    $languages  = Language::getAll()->where('status', 'Active');
                    if (mb_strlen($preference['company_name']) > 17) {
                        $preference['company_name'] = mb_substr($preference['company_name'], 0, 17).'...';
                    }
                    $data = [
                        'date_format_type'   => $preference['date_format_type'],
                        'row_per_page'       => $preference['row_per_page'],
                        'dflt_lang'          => $preference['dflt_lang'],
                        'company_name'       => $preference['company_name'],
                        'company_logo'       => !empty($preference['company_logo']) ? $preference['company_logo'] : '',
                        'company_street'     => $preference['company_street'],
                        'company_city'       => $preference['company_city'],
                        'company_state'      => $preference['company_state'],
                        'company_country_id' => $preference['company_country'],
                        'company_zipCode'    => $preference['company_zip_code'],
                        'currency_symbol'    => $currency->symbol,
                        'currency_name'      => $currency->names,
                        'languages'          => $languages,
                        'decimal_digits'     => $preference['decimal_digits'],
                        'thousand_separator' => $preference['thousand_separator'],
                        'symbol_position'    => $preference['symbol_position'],
                        'default_currency'   => $currency
                    ];
                    $json = \Cache::get('lanObject-' . config('app.locale'));
                    if (empty($json)) {
                        $json = file_get_contents(resource_path('lang/' . config('app.locale') . '.json'));
                        \Cache::put('lanObject-' . config('app.locale'), $json, 86400);
                        
                    }
                    $data['json'] = $json;
                    $data['company_country_name'] = Country::getCountry($data['company_country_id']);
                    $data['favicon']              = !empty($preference['company_icon']) ? $preference['company_icon'] : '';
                    $data['company_name']         = $preference['company_name'] ? $preference['company_name'] : '';
                    $view->with($data); 
                    $this->isBooted = true;
                }
            });
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
