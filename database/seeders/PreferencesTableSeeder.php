<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PreferencesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('preferences')->delete();
        
        \DB::table('preferences')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category' => 'preference',
                'field' => 'row_per_page',
                'value' => '25',
            ),
            1 => 
            array (
                'id' => 2,
                'category' => 'preference',
                'field' => 'date_format',
                'value' => '3',
            ),
            2 => 
            array (
                'id' => 3,
                'category' => 'preference',
                'field' => 'date_sepa',
                'value' => '-',
            ),
            3 => 
            array (
                'id' => 4,
                'category' => 'preference',
                'field' => 'soft_name',
                'value' => 'goBilling',
            ),
            4 => 
            array (
                'id' => 5,
                'category' => 'company',
                'field' => 'site_short_name',
                'value' => '1T',
            ),
            5 => 
            array (
                'id' => 6,
                'category' => 'preference',
                'field' => 'percentage',
                'value' => '0',
            ),
            6 => 
            array (
                'id' => 7,
                'category' => 'preference',
                'field' => 'quantity',
                'value' => '0',
            ),
            7 => 
            array (
                'id' => 8,
                'category' => 'preference',
                'field' => 'date_format_type',
                'value' => 'dd-M-yyyy',
            ),
            8 => 
            array (
                'id' => 9,
                'category' => 'company',
                'field' => 'company_name',
                'value' => 'roverCRM',
            ),
            9 => 
            array (
                'id' => 10,
                'category' => 'company',
                'field' => 'company_email',
                'value' => 'admin@techvill.net',
            ),
            10 => 
            array (
                'id' => 11,
                'category' => 'company',
                'field' => 'company_phone',
                'value' => '+12013828901',
            ),
            11 => 
            array (
                'id' => 12,
                'category' => 'company',
                'field' => 'company_street',
                'value' => 'City Hall Park Path',
            ),
            12 => 
            array (
                'id' => 13,
                'category' => 'company',
                'field' => 'company_city',
                'value' => 'New york',
            ),
            13 => 
            array (
                'id' => 14,
                'category' => 'company',
                'field' => 'company_state',
                'value' => 'New york',
            ),
            14 => 
            array (
                'id' => 15,
                'category' => 'company',
                'field' => 'company_zip_code',
                'value' => '',
            ),
            15 => 
            array (
                'id' => 17,
                'category' => 'company',
                'field' => 'dflt_lang',
                'value' => 'en',
            ),
            16 => 
            array (
                'id' => 18,
                'category' => 'company',
                'field' => 'dflt_currency_id',
                'value' => '3',
            ),
            17 => 
            array (
                'id' => 19,
                'category' => 'company',
                'field' => 'sates_type_id',
                'value' => '1',
            ),
            18 => 
            array (
                'id' => 21,
                'category' => 'company',
                'field' => 'company_gstin',
                'value' => '15',
            ),
            19 => 
            array (
                'id' => 22,
                'category' => 'gl_account',
                'field' => 'supplier_debit_gl_acc',
                'value' => '4',
            ),
            20 => 
            array (
                'id' => 23,
                'category' => 'gl_account',
                'field' => 'supplier_credit_gl_acc',
                'value' => '4',
            ),
            21 => 
            array (
                'id' => 24,
                'category' => 'gl_account',
                'field' => 'customer_debit_gl_acc',
                'value' => '4',
            ),
            22 => 
            array (
                'id' => 25,
                'category' => 'gl_account',
                'field' => 'customer_credit_gl_acc',
                'value' => '4',
            ),
            23 => 
            array (
                'id' => 26,
                'category' => 'gl_account',
                'field' => 'user_transaction_debit_gl_acc',
                'value' => '4',
            ),
            24 => 
            array (
                'id' => 27,
                'category' => 'gl_account',
                'field' => 'user_transaction_credit_gl_acc',
                'value' => '4',
            ),
            25 => 
            array (
                'id' => 28,
                'category' => 'gl_account',
                'field' => 'bank_charge_gl_acc',
                'value' => '3',
            ),
            26 => 
            array (
                'id' => 29,
                'category' => 'preference',
                'field' => 'default_timezone',
                'value' => 'Asia/Dhaka',
            ),
            27 => 
            array (
                'id' => 39,
                'category' => 'company',
                'field' => 'company_country',
                'value' => '2',
            ),
            28 => 
            array (
                'id' => 40,
                'category' => 'company',
                'field' => 'pic',
                'value' => '18a05b02131187c59cfa129159845d9f_1_2531033-1366x768-[desktopnexus.jpg',
            ),
            29 => 
            array (
                'id' => 43,
                'category' => 'company',
                'field' => 'icon',
                'value' => 'fav.1575289980.ico',
            ),
            30 => 
            array (
                'id' => 44,
                'category' => 'preference',
                'field' => 'thousand_separator',
                'value' => ',',
            ),
            31 => 
            array (
                'id' => 45,
                'category' => 'preference',
                'field' => 'decimal_digits',
                'value' => '3',
            ),
            32 => 
            array (
                'id' => 46,
                'category' => 'preference',
                'field' => 'symbol_position',
                'value' => 'after',
            ),
            33 => 
            array (
                'id' => 47,
                'category' => 'company',
                'field' => 'company_icon',
                'value' => '2ab717068febd8df9eaa02836da58b4c_1_faviconico.ico',
            ),
            34 => 
            array (
                'id' => 48,
                'category' => 'company',
                'field' => 'company_logo',
            'value' => '0f5af83d90766696bc8aa3e0af2a233e_1_rover_logo_transparent1png.png',
            ),
            35 => 
            array (
                'id' => 49,
                'category' => 'preference',
                'field' => 'pdf',
                'value' => 'mPdf',
            ),
            36 => 
            array (
                'id' => 50,
                'category' => 'preference',
                'field' => 'exchange_rate_decimal_digits',
                'value' => '4',
            ),
            37 => 
            array (
                'id' => 51,
                'category' => 'preference',
                'field' => 'file_size',
                'value' => '10',
            ),
            38 => 
            array (
                'id' => 52,
                'category' => 'preference',
                'field' => 'captcha',
                'value' => 'disable',
            ),
            39 => 
            array (
                'id' => 53,
                'category' => 'preference',
                'field' => 'facebook_comments',
                'value' => 'disable',
            ),
        ));
        
        
    }
}