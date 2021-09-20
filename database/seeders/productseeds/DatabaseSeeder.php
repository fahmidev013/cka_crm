<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        
        $this->call(AccountsTableSeeder::class);
        $this->call(AccountTypesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(EmailConfigurationsTableSeeder::class);
        $this->call(IncomeExpenseCategoriesTableSeeder::class);
        $this->call(ItemUnitsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(LeadSourcesTableSeeder::class);
        $this->call(LeadStatusesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(MonthsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(PaymentTermsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PreferencesTableSeeder::class);
        $this->call(PrioritiesTableSeeder::class);
        $this->call(ProjectStatusesTableSeeder::class);
        $this->call(PurchaseReceiveTypesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SaleTypesTableSeeder::class);
        $this->call(SecurityRolesTableSeeder::class);
        $this->call(SmsConfigTableSeeder::class);
        $this->call(TaskStatusesTableSeeder::class);
        $this->call(TaxTypesTableSeeder::class);
        $this->call(TicketStatusesTableSeeder::class);
        $this->call(UrlShortnerConfigTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(CustomerBranchesTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(PermissionRolesTableSeeder::class);
        $this->call(StockCategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUsersTableSeeder::class);
        $this->call(CaptchaConfigurationsTableSeeder::class);
        $this->call(PaymentGatewaysTableSeeder::class);
    }
}
