<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'manage_customer',
                'display_name' => 'Manage Customers',
                'description' => 'Manage Customers',
                'permission_group' => 'Customers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'add_customer',
                'display_name' => 'Add Customer',
                'description' => 'Add Customer',
                'permission_group' => 'Customers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'edit_customer',
                'display_name' => 'Edit Customer',
                'description' => 'Edit Customer',
                'permission_group' => 'Customers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'delete_customer',
                'display_name' => 'Delete Customer',
                'description' => 'Delete Customer',
                'permission_group' => 'Customers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'manage_supplier',
                'display_name' => 'Manage Suppliers',
                'description' => 'Manage Suppliers',
                'permission_group' => 'Suppliers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'add_supplier',
                'display_name' => 'Add Supplier',
                'description' => 'Add Supplier',
                'permission_group' => 'Suppliers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            6 => 
            array (
                'id' => 8,
                'name' => 'edit_supplier',
                'display_name' => 'Edit Supplier',
                'description' => 'Edit Supplier',
                'permission_group' => 'Suppliers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            7 => 
            array (
                'id' => 9,
                'name' => 'delete_supplier',
                'display_name' => 'Delete Supplier',
                'description' => 'Delete Supplier',
                'permission_group' => 'Suppliers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            8 => 
            array (
                'id' => 10,
                'name' => 'manage_item',
                'display_name' => 'Manage Items',
                'description' => 'Manage Items',
                'permission_group' => 'Items',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            9 => 
            array (
                'id' => 11,
                'name' => 'add_item',
                'display_name' => 'Add Item',
                'description' => 'Add Item',
                'permission_group' => 'Items',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            10 => 
            array (
                'id' => 12,
                'name' => 'edit_item',
                'display_name' => 'Edit Item',
                'description' => 'Edit Item',
                'permission_group' => 'Items',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            11 => 
            array (
                'id' => 13,
                'name' => 'delete_item',
                'display_name' => 'Delete Item',
                'description' => 'Delete Item',
                'permission_group' => 'Items',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            12 => 
            array (
                'id' => 15,
                'name' => 'manage_quotation',
                'display_name' => 'Manage Quotations',
                'description' => 'Manage Quotations',
                'permission_group' => 'Quotations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            13 => 
            array (
                'id' => 16,
                'name' => 'add_quotation',
                'display_name' => 'Add Quotation',
                'description' => 'Add Quotation',
                'permission_group' => 'Quotations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            14 => 
            array (
                'id' => 17,
                'name' => 'edit_quotation',
                'display_name' => 'Edit Quotation',
                'description' => 'Edit Quotation',
                'permission_group' => 'Quotations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            15 => 
            array (
                'id' => 18,
                'name' => 'delete_quotation',
                'display_name' => 'Delete Quotation',
                'description' => 'Delete Quotation',
                'permission_group' => 'Quotations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            16 => 
            array (
                'id' => 19,
                'name' => 'manage_invoice',
                'display_name' => 'Manage Invoices',
                'description' => 'Manage Invoices',
                'permission_group' => 'Invoices',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            17 => 
            array (
                'id' => 20,
                'name' => 'add_invoice',
                'display_name' => 'Add Invoice',
                'description' => 'Add Invoice',
                'permission_group' => 'Invoices',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            18 => 
            array (
                'id' => 21,
                'name' => 'edit_invoice',
                'display_name' => 'Edit Invoice',
                'description' => 'Edit Invoice',
                'permission_group' => 'Invoices',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            19 => 
            array (
                'id' => 22,
                'name' => 'delete_invoice',
                'display_name' => 'Delete Invoice',
                'description' => 'Delete Invoice',
                'permission_group' => 'Invoices',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            20 => 
            array (
                'id' => 23,
                'name' => 'manage_payment',
                'display_name' => 'Manage Payment',
                'description' => 'Manage Payment',
                'permission_group' => 'Customer Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            21 => 
            array (
                'id' => 24,
                'name' => 'add_payment',
                'display_name' => 'Add Payment',
                'description' => 'Add Payment',
                'permission_group' => 'Customer Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            22 => 
            array (
                'id' => 25,
                'name' => 'edit_payment',
                'display_name' => 'Edit Payment',
                'description' => 'Edit Payment',
                'permission_group' => 'Customer Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            23 => 
            array (
                'id' => 26,
                'name' => 'delete_payment',
                'display_name' => 'Delete Payment',
                'description' => 'Delete Payment',
                'permission_group' => 'Customer Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            24 => 
            array (
                'id' => 27,
                'name' => 'manage_purchase',
                'display_name' => 'Manage Purchase',
                'description' => 'Manage Purchase',
                'permission_group' => 'Purchases',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            25 => 
            array (
                'id' => 28,
                'name' => 'add_purchase',
                'display_name' => 'Add Purchase',
                'description' => 'Add Purchase',
                'permission_group' => 'Purchases',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            26 => 
            array (
                'id' => 29,
                'name' => 'edit_purchase',
                'display_name' => 'Edit Purchase',
                'description' => 'Edit Purchase',
                'permission_group' => 'Purchases',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            27 => 
            array (
                'id' => 30,
                'name' => 'delete_purchase',
                'display_name' => 'Delete Purchase',
                'description' => 'Delete Purchase',
                'permission_group' => 'Purchases',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            28 => 
            array (
                'id' => 31,
                'name' => 'manage_banking_transaction',
                'display_name' => 'Manage Banking & Transactions',
                'description' => 'Manage Banking & Transactions',
                'permission_group' => 'Banking and Transactions',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            29 => 
            array (
                'id' => 32,
                'name' => 'manage_bank_account',
                'display_name' => 'Manage Bank Accounts',
                'description' => 'Manage Bank Accounts',
                'permission_group' => 'Bank Accounts',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            30 => 
            array (
                'id' => 33,
                'name' => 'add_bank_account',
                'display_name' => 'Add Bank Account',
                'description' => 'Add Bank Account',
                'permission_group' => 'Bank Accounts',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            31 => 
            array (
                'id' => 34,
                'name' => 'edit_bank_account',
                'display_name' => 'Edit Bank Account',
                'description' => 'Edit Bank Account',
                'permission_group' => 'Bank Accounts',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            32 => 
            array (
                'id' => 35,
                'name' => 'delete_bank_account',
                'display_name' => 'Delete Bank Account',
                'description' => 'Delete Bank Account',
                'permission_group' => 'Bank Accounts',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            33 => 
            array (
                'id' => 36,
                'name' => 'manage_deposit',
                'display_name' => 'Manage Deposit',
                'description' => 'Manage Deposit',
                'permission_group' => 'Bank Account Deposits',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            34 => 
            array (
                'id' => 37,
                'name' => 'add_deposit',
                'display_name' => 'Add Deposit',
                'description' => 'Add Deposit',
                'permission_group' => 'Bank Account Deposits',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            35 => 
            array (
                'id' => 38,
                'name' => 'edit_deposit',
                'display_name' => 'Edit Deposit',
                'description' => 'Edit Deposit',
                'permission_group' => 'Bank Account Deposits',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            36 => 
            array (
                'id' => 39,
                'name' => 'delete_deposit',
                'display_name' => 'Delete Deposit',
                'description' => 'Delete Deposit',
                'permission_group' => 'Bank Account Deposits',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            37 => 
            array (
                'id' => 40,
                'name' => 'manage_balance_transfer',
                'display_name' => 'Manage Balance Transfer',
                'description' => 'Manage Balance Transfer',
                'permission_group' => 'Bank Account Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            38 => 
            array (
                'id' => 41,
                'name' => 'add_balance_transfer',
                'display_name' => 'Add Balance Transfer',
                'description' => 'Add Balance Transfer',
                'permission_group' => 'Bank Account Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            39 => 
            array (
                'id' => 42,
                'name' => 'edit_balance_transfer',
                'display_name' => 'Edit Balance Transfer',
                'description' => 'Edit Balance Transfer',
                'permission_group' => 'Bank Account Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            40 => 
            array (
                'id' => 43,
                'name' => 'delete_balance_transfer',
                'display_name' => 'Delete Balance Transfer',
                'description' => 'Delete Balance Transfer',
                'permission_group' => 'Bank Account Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            41 => 
            array (
                'id' => 44,
                'name' => 'manage_transaction',
                'display_name' => 'Manage Transactions',
                'description' => 'Manage Transactions',
                'permission_group' => 'Transactions',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            42 => 
            array (
                'id' => 45,
                'name' => 'manage_expense',
                'display_name' => 'Manage Expense',
                'description' => 'Manage Expense',
                'permission_group' => 'Expenses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            43 => 
            array (
                'id' => 46,
                'name' => 'add_expense',
                'display_name' => 'Add Expense',
                'description' => 'Add Expense',
                'permission_group' => 'Expenses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            44 => 
            array (
                'id' => 47,
                'name' => 'edit_expense',
                'display_name' => 'Edit Expense',
                'description' => 'Edit Expense',
                'permission_group' => 'Expenses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            45 => 
            array (
                'id' => 48,
                'name' => 'delete_expense',
                'display_name' => 'Delete Expense',
                'description' => 'Delete Expense',
                'permission_group' => 'Expenses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            46 => 
            array (
                'id' => 50,
                'name' => 'manage_stock_on_hand',
                'display_name' => 'Manage Inventory Stock On Hand',
                'description' => 'Manage Inventory Stock On Hand',
                'permission_group' => 'Stock On Hand',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            47 => 
            array (
                'id' => 51,
                'name' => 'manage_sale_report',
                'display_name' => 'Manage Sales Report',
                'description' => 'Manage Sales Report',
                'permission_group' => 'Sale Reports',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            48 => 
            array (
                'id' => 52,
                'name' => 'manage_sale_history_report',
                'display_name' => 'Manage Sales History Report',
                'description' => 'Manage Sales History Report',
                'permission_group' => 'Sale History Reports',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            49 => 
            array (
                'id' => 53,
                'name' => 'manage_purchase_report',
                'display_name' => 'Manage Purchase Report',
                'description' => 'Manage Purchase Report',
                'permission_group' => 'Purchase Reports',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            50 => 
            array (
                'id' => 54,
                'name' => 'manage_team_report',
                'display_name' => 'Manage Team Member Report',
                'description' => 'Manage Team Member Report',
                'permission_group' => 'Team Reports',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            51 => 
            array (
                'id' => 55,
                'name' => 'manage_expense_report',
                'display_name' => 'Manage Expense Report',
                'description' => 'Manage Expense Report',
                'permission_group' => 'Expense Reports',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            52 => 
            array (
                'id' => 56,
                'name' => 'manage_income_report',
                'display_name' => 'Manage Income Report',
                'description' => 'Manage Income Report',
                'permission_group' => 'Income Reports',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            53 => 
            array (
                'id' => 57,
                'name' => 'manage_income_vs_expense',
                'display_name' => 'Manage Income vs Expense',
                'description' => 'Manage Income vs Expense',
                'permission_group' => 'Income vs Expense',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            54 => 
            array (
                'id' => 58,
                'name' => 'manage_setting',
                'display_name' => 'Manage Settings',
                'description' => 'Manage Settings',
                'permission_group' => 'Settings',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            55 => 
            array (
                'id' => 59,
                'name' => 'manage_company_setting',
                'display_name' => 'Manage Company Setting',
                'description' => 'Manage Company Setting',
                'permission_group' => 'Company Settings',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            56 => 
            array (
                'id' => 60,
                'name' => 'manage_team_member',
                'display_name' => 'Manage Team Member',
                'description' => 'Manage Team Member',
                'permission_group' => 'Team Members',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            57 => 
            array (
                'id' => 61,
                'name' => 'add_team_member',
                'display_name' => 'Add Team Member',
                'description' => 'Add Team Member',
                'permission_group' => 'Team Members',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            58 => 
            array (
                'id' => 62,
                'name' => 'edit_team_member',
                'display_name' => 'Edit Team Member',
                'description' => 'Edit Team Member',
                'permission_group' => 'Team Members',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            59 => 
            array (
                'id' => 63,
                'name' => 'delete_team_member',
                'display_name' => 'Delete Team Member',
                'description' => 'Delete Team Member',
                'permission_group' => 'Team Members',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            60 => 
            array (
                'id' => 64,
                'name' => 'manage_role',
                'display_name' => 'Manage Roles',
                'description' => 'Manage Roles',
                'permission_group' => 'Roles',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            61 => 
            array (
                'id' => 65,
                'name' => 'add_role',
                'display_name' => 'Add Role',
                'description' => 'Add Role',
                'permission_group' => 'Roles',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            62 => 
            array (
                'id' => 66,
                'name' => 'edit_role',
                'display_name' => 'Edit Role',
                'description' => 'Edit Role',
                'permission_group' => 'Roles',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            63 => 
            array (
                'id' => 67,
                'name' => 'delete_role',
                'display_name' => 'Delete Role',
                'description' => 'Delete Role',
                'permission_group' => 'Roles',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            64 => 
            array (
                'id' => 68,
                'name' => 'manage_location',
                'display_name' => 'Manage Location',
                'description' => 'Manage Location',
                'permission_group' => 'Locations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            65 => 
            array (
                'id' => 69,
                'name' => 'add_location',
                'display_name' => 'Add Location',
                'description' => 'Add Location',
                'permission_group' => 'Locations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            66 => 
            array (
                'id' => 70,
                'name' => 'edit_location',
                'display_name' => 'Edit Location',
                'description' => 'Edit Location',
                'permission_group' => 'Locations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            67 => 
            array (
                'id' => 71,
                'name' => 'delete_location',
                'display_name' => 'Delete Location',
                'description' => 'Delete Location',
                'permission_group' => 'Locations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            68 => 
            array (
                'id' => 72,
                'name' => 'manage_general_setting',
                'display_name' => 'Manage General Settings',
                'description' => 'Manage General Settings',
                'permission_group' => 'General Settings',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            69 => 
            array (
                'id' => 73,
                'name' => 'manage_item_category',
                'display_name' => 'Manage Item Category',
                'description' => 'Manage Item Category',
                'permission_group' => 'Item Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            70 => 
            array (
                'id' => 74,
                'name' => 'add_item_category',
                'display_name' => 'Add Item Category',
                'description' => 'Add Item Category',
                'permission_group' => 'Item Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            71 => 
            array (
                'id' => 75,
                'name' => 'edit_item_category',
                'display_name' => 'Edit Item Category',
                'description' => 'Edit Item Category',
                'permission_group' => 'Item Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            72 => 
            array (
                'id' => 76,
                'name' => 'delete_item_category',
                'display_name' => 'Delete Item Category',
                'description' => 'Delete Item Category',
                'permission_group' => 'Item Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            73 => 
            array (
                'id' => 77,
                'name' => 'manage_income_expense_category',
                'display_name' => 'Manage Income Expense Category',
                'description' => 'Manage Income Expense Category',
                'permission_group' => 'Income Expense Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            74 => 
            array (
                'id' => 78,
                'name' => 'add_income_expense_category',
                'display_name' => 'Add Income Expense Category',
                'description' => 'Add Income Expense Category',
                'permission_group' => 'Income Expense Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            75 => 
            array (
                'id' => 79,
                'name' => 'edit_income_expense_category',
                'display_name' => 'Edit Income Expense Category',
                'description' => 'Edit Income Expense Category',
                'permission_group' => 'Income Expense Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            76 => 
            array (
                'id' => 80,
                'name' => 'delete_income_expense_category',
                'display_name' => 'Delete Income Expense Category',
                'description' => 'Delete Income Expense Category',
                'permission_group' => 'Income Expense Categories',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            77 => 
            array (
                'id' => 81,
                'name' => 'manage_unit',
                'display_name' => 'Manage Unit',
                'description' => 'Manage Unit',
                'permission_group' => 'Units',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            78 => 
            array (
                'id' => 82,
                'name' => 'add_unit',
                'display_name' => 'Add Unit',
                'description' => 'Add Unit',
                'permission_group' => 'Units',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            79 => 
            array (
                'id' => 83,
                'name' => 'edit_unit',
                'display_name' => 'Edit Unit',
                'description' => 'Edit Unit',
                'permission_group' => 'Units',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            80 => 
            array (
                'id' => 84,
                'name' => 'delete_unit',
                'display_name' => 'Delete Unit',
                'description' => 'Delete Unit',
                'permission_group' => 'Units',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            81 => 
            array (
                'id' => 85,
                'name' => 'manage_db_backup',
                'display_name' => 'Manage Database Backup',
                'description' => 'Manage Database Backup',
                'permission_group' => 'Database Backups',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            82 => 
            array (
                'id' => 86,
                'name' => 'add_db_backup',
                'display_name' => 'Add Database Backup',
                'description' => 'Add Database Backup',
                'permission_group' => 'Database Backups',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            83 => 
            array (
                'id' => 87,
                'name' => 'delete_db_backup',
                'display_name' => 'Delete Database Backup',
                'description' => 'Delete Database Backup',
                'permission_group' => 'Database Backups',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            84 => 
            array (
                'id' => 88,
                'name' => 'manage_email_setup',
                'display_name' => 'Manage Email Setup',
                'description' => 'Manage Email Setup',
                'permission_group' => 'Email Setup',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            85 => 
            array (
                'id' => 89,
                'name' => 'manage_finance',
                'display_name' => 'Manage Finance',
                'description' => 'Manage Finance',
                'permission_group' => 'Finances',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            86 => 
            array (
                'id' => 90,
                'name' => 'manage_tax',
                'display_name' => 'Manage Taxs',
                'description' => 'Manage Taxs',
                'permission_group' => 'Taxes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            87 => 
            array (
                'id' => 91,
                'name' => 'add_tax',
                'display_name' => 'Add Tax',
                'description' => 'Add Tax',
                'permission_group' => 'Taxes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            88 => 
            array (
                'id' => 92,
                'name' => 'edit_tax',
                'display_name' => 'Edit Tax',
                'description' => 'Edit Tax',
                'permission_group' => 'Taxes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            89 => 
            array (
                'id' => 93,
                'name' => 'delete_tax',
                'display_name' => 'Delete Tax',
                'description' => 'Delete Tax',
                'permission_group' => 'Taxes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            90 => 
            array (
                'id' => 94,
                'name' => 'manage_currency',
                'display_name' => 'Manage Currency',
                'description' => 'Manage Currency',
                'permission_group' => 'Currencies',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            91 => 
            array (
                'id' => 95,
                'name' => 'add_currency',
                'display_name' => 'Add Currency',
                'description' => 'Add Currency',
                'permission_group' => 'Currencies',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            92 => 
            array (
                'id' => 96,
                'name' => 'edit_currency',
                'display_name' => 'Edit Currency',
                'description' => 'Edit Currency',
                'permission_group' => 'Currencies',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            93 => 
            array (
                'id' => 97,
                'name' => 'delete_currency',
                'display_name' => 'Delete Currency',
                'description' => 'Delete Currency',
                'permission_group' => 'Currencies',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            94 => 
            array (
                'id' => 98,
                'name' => 'manage_payment_term',
                'display_name' => 'Manage Payment Term',
                'description' => 'Manage Payment Term',
                'permission_group' => 'Payment Terms',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            95 => 
            array (
                'id' => 99,
                'name' => 'add_payment_term',
                'display_name' => 'Add Payment Term',
                'description' => 'Add Payment Term',
                'permission_group' => 'Payment Terms',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            96 => 
            array (
                'id' => 100,
                'name' => 'edit_payment_term',
                'display_name' => 'Edit Payment Term',
                'description' => 'Edit Payment Term',
                'permission_group' => 'Payment Terms',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            97 => 
            array (
                'id' => 101,
                'name' => 'delete_payment_term',
                'display_name' => 'Delete Payment Term',
                'description' => 'Delete Payment Term',
                'permission_group' => 'Payment Terms',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            98 => 
            array (
                'id' => 102,
                'name' => 'manage_payment_method',
                'display_name' => 'Manage Payment Method',
                'description' => 'Manage Payment Method',
                'permission_group' => 'Payment Methods',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            99 => 
            array (
                'id' => 104,
                'name' => 'edit_payment_method',
                'display_name' => 'Edit Payment Method',
                'description' => 'Edit Payment Method',
                'permission_group' => 'Payment Methods',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            100 => 
            array (
                'id' => 107,
                'name' => 'manage_email_template',
                'display_name' => 'Manage Email Template',
                'description' => 'Manage Email Template',
                'permission_group' => 'Email Templates',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            101 => 
            array (
                'id' => 108,
                'name' => 'manage_quotation_email_template',
                'display_name' => 'Manage Quotation Template',
                'description' => 'Manage Quotation Email Template',
                'permission_group' => 'Quotation Email Templates',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            102 => 
            array (
                'id' => 111,
                'name' => 'manage_preference',
                'display_name' => 'Manage Preference',
                'description' => 'Manage Preference',
                'permission_group' => 'Preferences',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            103 => 
            array (
                'id' => 112,
                'name' => 'manage_barcode',
                'display_name' => 'Manage barcode/label',
                'description' => 'Manage barcode/label',
                'permission_group' => 'Barcode',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            104 => 
            array (
                'id' => 113,
                'name' => 'edit_db_backup',
                'display_name' => 'Download Database Backup',
                'description' => 'Download Database Backup',
                'permission_group' => 'Database Backups',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            105 => 
            array (
                'id' => 114,
                'name' => 'manage_purch_payment',
                'display_name' => 'Manage Purchase Payment',
                'description' => 'Manage Purchase Payment',
                'permission_group' => 'Purchase Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            106 => 
            array (
                'id' => 115,
                'name' => 'add_purch_payment',
                'display_name' => 'Add Purchase Payment',
                'description' => 'Add Purchase Payment',
                'permission_group' => 'Purchase Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            107 => 
            array (
                'id' => 116,
                'name' => 'edit_purch_payment',
                'display_name' => 'Edit Purchase Payment',
                'description' => 'Edit Purchase Payment',
                'permission_group' => 'Purchase Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            108 => 
            array (
                'id' => 117,
                'name' => 'delete_purch_payment',
                'display_name' => 'Delete Purchase Payment',
                'description' => 'Delete Purchase Payment',
                'permission_group' => 'Purchase Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            109 => 
            array (
                'id' => 118,
                'name' => 'manage_ticket',
                'display_name' => 'Manage Ticket',
                'description' => 'Manage Ticket',
                'permission_group' => 'Tickets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            110 => 
            array (
                'id' => 119,
                'name' => 'add_ticket',
                'display_name' => 'Add Ticket',
                'description' => 'Add Ticket',
                'permission_group' => 'Tickets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            111 => 
            array (
                'id' => 120,
                'name' => 'edit_ticket',
                'display_name' => 'Edit Ticket',
                'description' => 'Edit Ticket',
                'permission_group' => 'Tickets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            112 => 
            array (
                'id' => 121,
                'name' => 'delete_ticket',
                'display_name' => 'Delete Ticket',
                'description' => 'Delete Ticket',
                'permission_group' => 'Tickets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            113 => 
            array (
                'id' => 122,
                'name' => 'manage_project',
                'display_name' => 'Manage Project',
                'description' => 'Manage Project',
                'permission_group' => 'Projects',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            114 => 
            array (
                'id' => 123,
                'name' => 'add_project',
                'display_name' => 'Add Project',
                'description' => 'Add Project',
                'permission_group' => 'Projects',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            115 => 
            array (
                'id' => 124,
                'name' => 'edit_project',
                'display_name' => 'Edit Project',
                'description' => 'Edit Project',
                'permission_group' => 'Projects',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            116 => 
            array (
                'id' => 125,
                'name' => 'delete_project',
                'display_name' => 'Delete Project',
                'description' => 'Delete Project',
                'permission_group' => 'Projects',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            117 => 
            array (
                'id' => 126,
                'name' => 'manage_task',
                'display_name' => 'Manage Task',
                'description' => 'Manage Task',
                'permission_group' => 'Tasks',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            118 => 
            array (
                'id' => 127,
                'name' => 'add_task',
                'display_name' => 'Add Task',
                'description' => 'Add Task',
                'permission_group' => 'Tasks',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            119 => 
            array (
                'id' => 128,
                'name' => 'edit_task',
                'display_name' => 'Edit Task',
                'description' => 'Edit Task',
                'permission_group' => 'Tasks',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            120 => 
            array (
                'id' => 129,
                'name' => 'delete_task',
                'display_name' => 'Delete Task',
                'description' => 'Delete Task',
                'permission_group' => 'Tasks',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            121 => 
            array (
                'id' => 130,
                'name' => 'manage_milestone',
                'display_name' => 'Manage Milestone',
                'description' => 'Manage Milestone',
                'permission_group' => 'Milestones',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            122 => 
            array (
                'id' => 131,
                'name' => 'add_milestone',
                'display_name' => 'Add Milestone',
                'description' => 'Add Milestone',
                'permission_group' => 'Milestones',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            123 => 
            array (
                'id' => 132,
                'name' => 'edit_milestone',
                'display_name' => 'Edit Milestone',
                'description' => 'Edit Milestone',
                'permission_group' => 'Milestones',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            124 => 
            array (
                'id' => 133,
                'name' => 'delete_milestone',
                'display_name' => 'Delete Milestone',
                'description' => 'Delete Milestone',
                'permission_group' => 'Milestones',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            125 => 
            array (
                'id' => 134,
                'name' => 'manage_department',
                'display_name' => 'Manage Department',
                'description' => 'Manage Department',
                'permission_group' => 'Departments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            126 => 
            array (
                'id' => 135,
                'name' => 'add_department',
                'display_name' => 'Add Department',
                'description' => 'Add Department',
                'permission_group' => 'Departments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            127 => 
            array (
                'id' => 136,
                'name' => 'edit_department',
                'display_name' => 'Edit Department',
                'description' => 'Edit Department',
                'permission_group' => 'Departments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            128 => 
            array (
                'id' => 137,
                'name' => 'delete_department',
                'display_name' => 'Delete Department',
                'description' => 'Delete Department',
                'permission_group' => 'Departments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            129 => 
            array (
                'id' => 140,
                'name' => 'manage_purch_receive',
                'display_name' => 'Manage Purchase Receive',
                'description' => 'Manage Purchase Receive',
                'permission_group' => 'Purchase Receive',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            130 => 
            array (
                'id' => 141,
                'name' => 'edit_purchase_receive',
                'display_name' => 'Edit Purchase Receive',
                'description' => 'Edit Purchase Receive',
                'permission_group' => 'Purchase Receive',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            131 => 
            array (
                'id' => 142,
                'name' => 'delete_purchase_receive',
                'display_name' => 'Delete Purchase Receive',
                'description' => 'Delete Purchase Receive',
                'permission_group' => 'Purchase Receive',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            132 => 
            array (
                'id' => 143,
                'name' => 'add_calendar_event',
                'display_name' => 'Add Calendar Event',
                'description' => 'Add Calendar Event',
                'permission_group' => 'Calendar',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            133 => 
            array (
                'id' => 144,
                'name' => 'edit_calendar_event',
                'display_name' => 'Edit Calendar Event',
                'description' => 'Edit Calendar Event',
                'permission_group' => 'Calendar',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            134 => 
            array (
                'id' => 145,
                'name' => 'delete_calendar_event',
                'display_name' => 'Delete Calendar Event',
                'description' => 'Delete Calendar Event',
                'permission_group' => 'Calendar',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            135 => 
            array (
                'id' => 146,
                'name' => 'edit_task_comment',
                'display_name' => 'Edit Task Comment',
                'description' => 'Edit Task Comment',
                'permission_group' => 'Task Comments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            136 => 
            array (
                'id' => 147,
                'name' => 'delete_task_comment',
                'display_name' => 'Delete Task Comment',
                'description' => 'Delete Task Comment',
                'permission_group' => 'Task Comments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            137 => 
            array (
                'id' => 151,
                'name' => 'add_task_assignee',
                'display_name' => 'Add Task Assignee',
                'description' => 'Add Task Assignee',
                'permission_group' => 'Task Assignees',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            138 => 
            array (
                'id' => 152,
                'name' => 'delete_task_assignee',
                'display_name' => 'Delete Task Assignee',
                'description' => 'Delete Task Assignee',
                'permission_group' => 'Task Assignees',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            139 => 
            array (
                'id' => 153,
                'name' => 'delete_project_file',
                'display_name' => 'Delete Project File',
                'description' => 'Delete Project File',
                'permission_group' => 'Project Files',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            140 => 
            array (
                'id' => 154,
                'name' => 'add_project_note',
                'display_name' => 'Add Project Note',
                'description' => 'Add Project Note',
                'permission_group' => 'Project Notes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            141 => 
            array (
                'id' => 155,
                'name' => 'edit_project_note',
                'display_name' => 'Edit Project Note',
                'description' => 'Edit Project Note',
                'permission_group' => 'Project Notes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            142 => 
            array (
                'id' => 158,
                'name' => 'manage_sms_setup',
                'display_name' => ' Manage SMS Setup',
                'description' => ' Manage SMS Setup',
                'permission_group' => 'SMS Setup',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            143 => 
            array (
                'id' => 159,
                'name' => 'own_quotation',
                'display_name' => 'View Own Quotations',
                'description' => 'View Own Quotations',
                'permission_group' => 'Quotations',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            144 => 
            array (
                'id' => 160,
                'name' => 'own_invoice',
                'display_name' => 'View Own Invoice',
                'description' => 'View Own Invoice',
                'permission_group' => 'Invoices',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            145 => 
            array (
                'id' => 161,
                'name' => 'own_payment',
                'display_name' => 'View Own Payment',
                'description' => 'View Own Payment',
                'permission_group' => 'Customer Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            146 => 
            array (
                'id' => 162,
                'name' => 'own_purchase',
                'display_name' => 'View Own Purchase',
                'description' => 'View Own Purchase',
                'permission_group' => 'Purchases',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            147 => 
            array (
                'id' => 163,
                'name' => 'own_deposit',
                'display_name' => 'View Own Deposit',
                'description' => 'View Own Deposit',
                'permission_group' => 'Bank Account Deposits',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            148 => 
            array (
                'id' => 164,
                'name' => 'own_balance_transfer',
                'display_name' => 'View Own Balance Transfer',
                'description' => 'View Own Balance Transfer',
                'permission_group' => 'Bank Account Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            149 => 
            array (
                'id' => 165,
                'name' => 'own_transaction',
                'display_name' => 'View Own Transactions',
                'description' => 'View Own Transactions',
                'permission_group' => 'Transactions',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            150 => 
            array (
                'id' => 166,
                'name' => 'own_expense',
                'display_name' => 'View Own Expense',
                'description' => 'View Own Expense',
                'permission_group' => 'Expenses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            151 => 
            array (
                'id' => 167,
                'name' => 'own_purchase_payment',
                'display_name' => 'View Own Purchase Payment',
                'description' => 'View Own Purchase Payment',
                'permission_group' => 'Purchase Payments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            152 => 
            array (
                'id' => 168,
                'name' => 'own_purchase_receive',
                'display_name' => 'View Own Purchase Receive',
                'description' => 'View Own Purchase Receive',
                'permission_group' => 'Purchase Receives',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            153 => 
            array (
                'id' => 169,
                'name' => 'delete_stock_transfer',
                'display_name' => 'Delete Stock Transfer',
                'description' => 'Delete Stock Transfer',
                'permission_group' => 'Stock Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            154 => 
            array (
                'id' => 170,
                'name' => 'edit_stock_adjustment',
                'display_name' => 'Edit Stock Adjustment',
                'description' => 'Edit Stock Adjustment',
                'permission_group' => 'Stock Adjustments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            155 => 
            array (
                'id' => 171,
                'name' => 'delete_stock_adjustment',
                'display_name' => 'Delete Stock Adjustment',
                'description' => 'Delete Stock Adjustment',
                'permission_group' => 'Stock Adjustments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            156 => 
            array (
                'id' => 172,
                'name' => 'own_task',
                'display_name' => 'View Own Task',
                'description' => 'View Own Task',
                'permission_group' => 'Tasks',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            157 => 
            array (
                'id' => 173,
                'name' => 'own_ticket',
                'display_name' => 'View Own Ticket',
                'description' => 'View Own Ticket',
                'permission_group' => 'Tickets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            158 => 
            array (
                'id' => 174,
                'name' => 'own_project',
                'display_name' => 'View Own Project',
                'description' => 'View Own Project',
                'permission_group' => 'Projects',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            159 => 
            array (
                'id' => 175,
                'name' => 'manage_language',
                'display_name' => 'Manage Languages',
                'description' => 'Manage Languages',
                'permission_group' => 'Languages',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            160 => 
            array (
                'id' => 176,
                'name' => 'add_language',
                'display_name' => 'Add Language',
                'description' => 'Add Language',
                'permission_group' => 'Languages',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            161 => 
            array (
                'id' => 177,
                'name' => 'delete_language',
                'display_name' => 'Delete Languages',
                'description' => 'Delete Languages',
                'permission_group' => 'Languages',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            162 => 
            array (
                'id' => 178,
                'name' => 'edit_language',
                'display_name' => 'Edit Language',
                'description' => 'Edit Language',
                'permission_group' => 'Languages',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            163 => 
            array (
                'id' => 179,
                'name' => 'add_task_timer',
                'display_name' => 'Add Task Timer',
                'description' => 'Add Task Timer',
                'permission_group' => 'Task Timers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            164 => 
            array (
                'id' => 180,
                'name' => 'delete_task_timer',
                'display_name' => 'Delete Task Timer',
                'description' => 'Delete Task Timer',
                'permission_group' => 'Task Timers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            165 => 
            array (
                'id' => 191,
                'name' => 'add_customer_note',
                'display_name' => 'Add Customer Note',
                'description' => 'Add Customer Note',
                'permission_group' => 'Customer Notes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            166 => 
            array (
                'id' => 192,
                'name' => 'edit_customer_note',
                'display_name' => 'Edit Customer Note',
                'description' => 'Edit Customer Note',
                'permission_group' => 'Customer Notes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            167 => 
            array (
                'id' => 193,
                'name' => 'delete_customer_note',
                'display_name' => 'Delete Customer Note',
                'description' => 'Delete Customer Note',
                'permission_group' => 'Customer Notes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            168 => 
            array (
                'id' => 194,
                'name' => 'manage_lead_status',
                'display_name' => 'Manage Lead Status',
                'description' => 'Manage Lead Status',
                'permission_group' => 'Lead Statuses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            169 => 
            array (
                'id' => 195,
                'name' => 'add_lead_status',
                'display_name' => 'Add Lead Status',
                'description' => 'Add Lead Status',
                'permission_group' => 'Lead Statuses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            170 => 
            array (
                'id' => 196,
                'name' => 'edit_lead_status',
                'display_name' => 'Edit Lead Status',
                'description' => 'Edit Lead Status',
                'permission_group' => 'Lead Statuses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            171 => 
            array (
                'id' => 197,
                'name' => 'delete_lead_status',
                'display_name' => 'Delete Lead Status',
                'description' => 'Delete Lead Status',
                'permission_group' => 'Lead Statuses',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            172 => 
            array (
                'id' => 198,
                'name' => 'manage_lead_source',
                'display_name' => 'Manage Lead Source',
                'description' => 'Manage Lead Source',
                'permission_group' => 'Lead Sources',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            173 => 
            array (
                'id' => 199,
                'name' => 'add_lead_source',
                'display_name' => 'Add Lead Source',
                'description' => 'Add Lead Source',
                'permission_group' => 'Lead Sources',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            174 => 
            array (
                'id' => 200,
                'name' => 'edit_lead_source',
                'display_name' => 'Edit Lead Source',
                'description' => 'Edit Lead Source',
                'permission_group' => 'Lead Sources',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            175 => 
            array (
                'id' => 201,
                'name' => 'delete_lead_source',
                'display_name' => 'Delete Lead Source',
                'description' => 'Delete Lead Source',
                'permission_group' => 'Lead Sources',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            176 => 
            array (
                'id' => 202,
                'name' => 'manage_lead',
                'display_name' => 'Manage Lead',
                'description' => 'Manage Lead',
                'permission_group' => 'Leads',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            177 => 
            array (
                'id' => 203,
                'name' => 'add_lead',
                'display_name' => 'Add Lead',
                'description' => 'Add Lead',
                'permission_group' => 'Leads',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            178 => 
            array (
                'id' => 204,
                'name' => 'edit_lead',
                'display_name' => 'Edit Lead',
                'description' => 'Edit Lead',
                'permission_group' => 'Leads',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            179 => 
            array (
                'id' => 205,
                'name' => 'delete_lead',
                'display_name' => 'Delete Lead',
                'description' => 'Delete Lead',
                'permission_group' => 'Leads',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            180 => 
            array (
                'id' => 211,
                'name' => 'manage_account_type',
                'display_name' => 'Manage Account Type',
                'description' => 'Manage Account Type',
                'permission_group' => 'Bank Account Types',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            181 => 
            array (
                'id' => 212,
                'name' => 'add_account_type',
                'display_name' => 'Add Account Type',
                'description' => 'Add Account Type ',
                'permission_group' => 'Bank Account Types',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            182 => 
            array (
                'id' => 213,
                'name' => 'edit_account_type',
                'display_name' => 'Edit Account Type',
                'description' => 'Edit Account Type',
                'permission_group' => 'Bank Account Types',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            183 => 
            array (
                'id' => 214,
                'name' => 'delete_account_type',
                'display_name' => 'Delete Account Type',
                'description' => 'Delete Account Type',
                'permission_group' => 'Bank Account Types',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            184 => 
            array (
                'id' => 215,
                'name' => 'manage_pos',
                'display_name' => 'Manage POs',
                'description' => 'Manage Point of Sale',
                'permission_group' => 'POS',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            185 => 
            array (
                'id' => 216,
                'name' => 'manage_url_shortner',
                'display_name' => 'URL Shortner',
                'description' => 'Manage url shortner',
                'permission_group' => 'URL Shortner',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            186 => 
            array (
                'id' => 217,
                'name' => 'manage_sms_template',
                'display_name' => 'Manage SMS Template',
                'description' => 'manage sms template',
                'permission_group' => 'SMS Templates',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            187 => 
            array (
                'id' => 218,
                'name' => 'edit_ticket_reply',
                'display_name' => 'Edit Ticket Reply',
                'description' => 'Edit Ticket Reply',
                'permission_group' => 'Ticket Reply',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            188 => 
            array (
                'id' => 219,
                'name' => 'manage_calendar',
                'display_name' => 'Manage Calendar',
                'description' => 'Manage Calendar',
                'permission_group' => 'Calendar',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            189 => 
            array (
                'id' => 220,
                'name' => 'edit_url_shortner',
                'display_name' => 'Edit URL Shortner',
                'description' => 'Edit URL Shortner',
                'permission_group' => 'URL Shortner',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            190 => 
            array (
                'id' => 221,
                'name' => 'manage_stock_transfer',
                'display_name' => 'Manage Stock Transfer',
                'description' => 'Manage Stock Transfer',
                'permission_group' => 'Stock Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            191 => 
            array (
                'id' => 222,
                'name' => 'add_stock_transfer',
                'display_name' => 'Add Stock Transfer',
                'description' => 'Add Stock Transfer',
                'permission_group' => 'Stock Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            192 => 
            array (
                'id' => 223,
                'name' => 'edit_stock_transfer',
                'display_name' => 'Edit Stock Transfer',
                'description' => 'Edit Stock Transfer',
                'permission_group' => 'Stock Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            193 => 
            array (
                'id' => 224,
                'name' => 'manage_stock_adjustment',
                'display_name' => 'Manage Stock Adjustment',
                'description' => 'Manage Stock Adjustment',
                'permission_group' => 'Stock Adjustments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            194 => 
            array (
                'id' => 225,
                'name' => 'add_stock_adjustment',
                'display_name' => 'Add Stock Adjustment',
                'description' => 'Add Stock Adjustment',
                'permission_group' => 'Stock Adjustments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            195 => 
            array (
                'id' => 228,
                'name' => 'manage_ticket_reply',
                'display_name' => 'Manage Ticket Reply',
                'description' => 'Manage Ticket Reply',
                'permission_group' => 'Ticket Reply',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            196 => 
            array (
                'id' => 229,
                'name' => 'own_stock_transfer',
                'display_name' => 'View Own Stock Transfer',
                'description' => 'View Own Stock Transfer',
                'permission_group' => 'Stock Transfers',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            197 => 
            array (
                'id' => 230,
                'name' => 'own_stock_adjustment',
                'display_name' => 'View Own Stock Adjustment',
                'description' => 'View Own Stock Adjustment',
                'permission_group' => 'Stock Adjustments',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            198 => 
            array (
                'id' => 231,
                'name' => 'delete_project_note',
                'display_name' => 'Delete Project Note',
                'description' => 'Delete Project Note',
                'permission_group' => 'Project Notes',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            199 => 
            array (
                'id' => 232,
                'name' => 'manage_theme_preference',
                'display_name' => 'Manage Theme Preference',
                'description' => 'Manage Theme Preference',
                'permission_group' => 'Theme Preference',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            200 => 
            array (
                'id' => 233,
                'name' => 'manage_timesheet',
                'display_name' => 'Manage Timesheet',
                'description' => 'Manage Timesheet',
                'permission_group' => 'Timesheets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            201 => 
            array (
                'id' => 234,
                'name' => 'add_timesheet',
                'display_name' => 'Add Timesheet',
                'description' => 'Add Timesheet',
                'permission_group' => 'Timesheets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            202 => 
            array (
                'id' => 235,
                'name' => 'edit_timesheet',
                'display_name' => 'Edit Timesheet',
                'description' => 'Edit Timesheet',
                'permission_group' => 'Timesheets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            203 => 
            array (
                'id' => 236,
                'name' => 'delete_timesheet',
                'display_name' => 'Delete Timesheet',
                'description' => 'Delete Timesheet',
                'permission_group' => 'Timesheets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            204 => 
            array (
                'id' => 237,
                'name' => 'own_timesheet',
                'display_name' => 'View Own Timesheet',
                'description' => 'View Own Timesheet',
                'permission_group' => 'Timesheets',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            205 => 
            array (
                'id' => 238,
                'name' => 'manage_captcha_setup',
                'display_name' => 'Manage Captcha Setup',
                'description' => 'Manage Captcha Setup',
                'permission_group' => 'Captcha Setup',
                'created_at' => '2021-06-01 15:16:42',
                'updated_at' => '2021-06-09 15:16:42',
            ),
            206 => 
            array (
                'id' => 239,
                'name' => 'manage_currency_converter_setup',
                'display_name' => 'Manage Currency Converter Setup',
                'description' => 'Manage Currency Converter Setup',
                'permission_group' => 'Currency Converter Setup',
                'created_at' => '2021-06-01 15:16:42',
                'updated_at' => '2021-06-09 15:16:42',
            ),
            207 => 
            array (
                'id' => 240,
                'name' => 'manage_canned_message',
                'display_name' => 'Manage Canned Message',
                'description' => 'Manage Canned Message',
                'permission_group' => 'Canned Messages',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 241,
                'name' => 'add_canned_message',
                'display_name' => 'Add Canned Message',
                'description' => 'Add Canned Message',
                'permission_group' => 'Canned Messages',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id' => 242,
                'name' => 'edit_canned_message',
                'display_name' => 'Edit Canned Message',
                'description' => 'Edit Canned Message',
                'permission_group' => 'Canned Messages',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id' => 243,
                'name' => 'delete_canned_message',
                'display_name' => 'Delete Canned Message',
                'description' => 'Delete Canned Message',
                'permission_group' => 'Canned Messages',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id' => 244,
                'name' => 'manage_canned_link',
                'display_name' => 'Manage Canned Link',
                'description' => 'Manage Canned Link',
                'permission_group' => 'Canned Links',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id' => 245,
                'name' => 'add_canned_link',
                'display_name' => 'Add Canned Link',
                'description' => 'Add Canned Link',
                'permission_group' => 'Canned Links',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id' => 246,
                'name' => 'edit_canned_link',
                'display_name' => 'Edit Canned Link',
                'description' => 'Edit Canned Link',
                'permission_group' => 'Canned Links',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id' => 247,
                'name' => 'delete_canned_link',
                'display_name' => 'Delete Canned Link',
                'description' => 'Delete Canned Link',
                'permission_group' => 'Canned Links',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),

            215 => 
            array (
                'id' => 248,
                'name' => 'manage_group',
                'display_name' => 'Manage Group',
                'description' => 'Manage Group',
                'permission_group' => 'Groups',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id' => 249,
                'name' => 'add_group',
                'display_name' => 'Add Group',
                'description' => 'Add Group',
                'permission_group' => 'Groups',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id' => 250,
                'name' => 'edit_group',
                'display_name' => 'Edit Group',
                'description' => 'Edit Group',
                'permission_group' => 'Groups',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id' => 251,
                'name' => 'delete_group',
                'display_name' => 'Delete Group',
                'description' => 'Delete Group',
                'permission_group' => 'Groups',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id' => 252,
                'name' => 'manage_knowledge_base',
                'display_name' => 'Manage Knowledge Base',
                'description' => 'Manage Knowledge Base',
                'permission_group' => 'Knowledge Base',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id' => 253,
                'name' => 'add_knowledge_base',
                'display_name' => 'Add Knowledge Base',
                'description' => 'Add Knowledge Base',
                'permission_group' => 'Knowledge Base',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id' => 254,
                'name' => 'edit_knowledge_base',
                'display_name' => 'Edit Knowledge Base',
                'description' => 'Edit Knowledge Base',
                'permission_group' => 'Knowledge Base',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id' => 255,
                'name' => 'delete_knowledge_base',
                'display_name' => 'Delete Knowledge Base',
                'description' => 'Delete Knowledge Base',
                'permission_group' => 'Knowledge Base',
                'created_at' => '2021-06-05 16:10:21',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}