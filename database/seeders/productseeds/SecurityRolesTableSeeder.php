<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class SecurityRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('security_roles')->delete();
        
        \DB::table('security_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role' => 'System Administrator',
                'description' => 'System Administrator',
                'sections' => 'a:26:{s:8:"category";s:3:"100";s:4:"unit";s:3:"600";s:3:"loc";s:3:"200";s:4:"item";s:3:"300";s:4:"user";s:3:"400";s:4:"role";s:3:"500";s:8:"customer";s:3:"700";s:8:"purchase";s:3:"900";s:8:"supplier";s:4:"1000";s:7:"payment";s:4:"1400";s:6:"backup";s:4:"1500";s:5:"email";s:4:"1600";s:9:"emailtemp";s:4:"1700";s:10:"preference";s:4:"1800";s:3:"tax";s:4:"1900";s:10:"currencies";s:4:"2100";s:11:"paymentterm";s:4:"2200";s:13:"paymentmethod";s:4:"2300";s:14:"companysetting";s:4:"2400";s:10:"iecategory";s:4:"2600";s:7:"expense";s:4:"2700";s:7:"deposit";s:4:"3000";s:9:"quotation";s:4:"2800";s:7:"invoice";s:4:"2900";s:12:"bank_account";s:4:"3100";s:21:"bank_account_transfer";s:4:"3200";}',
                'areas' => 'a:59:{s:7:"cat_add";s:3:"101";s:8:"cat_edit";s:3:"102";s:10:"cat_delete";s:3:"103";s:8:"unit_add";s:3:"601";s:9:"unit_edit";s:3:"602";s:11:"unit_delete";s:3:"603";s:7:"loc_add";s:3:"201";s:8:"loc_edit";s:3:"202";s:10:"loc_delete";s:3:"203";s:8:"item_add";s:3:"301";s:9:"item_edit";s:3:"302";s:11:"item_delete";s:3:"303";s:8:"user_add";s:3:"401";s:9:"user_edit";s:3:"402";s:11:"user_delete";s:3:"403";s:12:"customer_add";s:3:"701";s:13:"customer_edit";s:3:"702";s:15:"customer_delete";s:3:"703";s:12:"purchase_add";s:3:"901";s:13:"purchase_edit";s:3:"902";s:15:"purchase_delete";s:3:"903";s:12:"supplier_add";s:4:"1001";s:13:"supplier_edit";s:4:"1002";s:15:"supplier_delete";s:4:"1003";s:11:"payment_add";s:4:"1401";s:12:"payment_edit";s:4:"1402";s:14:"payment_delete";s:4:"1403";s:10:"backup_add";s:4:"1501";s:15:"backup_download";s:4:"1502";s:7:"tax_add";s:4:"1901";s:8:"tax_edit";s:4:"1902";s:10:"tax_delete";s:4:"1903";s:14:"currencies_add";s:4:"2101";s:15:"currencies_edit";s:4:"2102";s:17:"currencies_delete";s:4:"2103";s:15:"paymentterm_add";s:4:"2201";s:16:"paymentterm_edit";s:4:"2202";s:18:"paymentterm_delete";s:4:"2203";s:17:"paymentmethod_add";s:4:"2301";s:18:"paymentmethod_edit";s:4:"2302";s:20:"paymentmethod_delete";s:4:"2303";s:11:"expense_add";s:4:"2701";s:12:"expense_edit";s:4:"2702";s:14:"expense_delete";s:4:"2703";s:11:"deposit_add";s:4:"3001";s:12:"deposit_edit";s:4:"3002";s:14:"deposit_delete";s:4:"3003";s:13:"quotation_add";s:4:"2801";s:14:"quotation_edit";s:4:"2802";s:16:"quotation_delete";s:4:"2803";s:11:"invoice_add";s:4:"2901";s:12:"invoice_edit";s:4:"2902";s:14:"invoice_delete";s:4:"2903";s:16:"bank_account_add";s:4:"3101";s:17:"bank_account_edit";s:4:"3102";s:19:"bank_account_delete";s:4:"3103";s:25:"bank_account_transfer_add";s:4:"3201";s:26:"bank_account_transfer_edit";s:4:"3202";s:28:"bank_account_transfer_delete";s:4:"3203";}',
                'is_active' => 0,
                'created_at' => '2018-11-05 07:21:57',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}