<?php

namespace Database\Seeders;

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
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AccountTypesTableSeeder::class);
        $this->call(BackupsTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(EmailConfigurationsTableSeeder::class);
        $this->call(FilesTableSeeder::class);
        $this->call(IncomeExpenseCategoriesTableSeeder::class);
        $this->call(ItemUnitsTableSeeder::class);
        $this->call(JobsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(LeadSourcesTableSeeder::class);
        $this->call(LeadStatusesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(MonthsTableSeeder::class);
        $this->call(PasswordResetsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(PaymentTermsTableSeeder::class);
        $this->call(PreferencesTableSeeder::class);
        $this->call(PrioritiesTableSeeder::class);
        $this->call(ProjectStatusesTableSeeder::class);
        $this->call(PurchaseReceiveTypesTableSeeder::class);
        $this->call(SaleTypesTableSeeder::class);
        $this->call(SecurityRolesTableSeeder::class);
        $this->call(SmsConfigTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(TaskStatusesTableSeeder::class);
        $this->call(TaxTypesTableSeeder::class);
        $this->call(TicketStatusesTableSeeder::class);
        $this->call(TransactionReferencesTableSeeder::class);
        $this->call(UrlShortnerConfigTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(CustomerActivationsTableSeeder::class);
        $this->call(CustomerBranchesTableSeeder::class);
        $this->call(ExchangeRatesTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(TagAssignsTableSeeder::class);
        $this->call(StockCategoriesTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(ItemCustomVariantsTableSeeder::class);
        $this->call(PurchasePricesTableSeeder::class);
        $this->call(UserDepartmentsTableSeeder::class);
        $this->call(CalendarEventsTableSeeder::class);
        $this->call(LeadsTableSeeder::class);
        $this->call(NotesTableSeeder::class);
        $this->call(RoleUsersTableSeeder::class);
        $this->call(StockAdjustmentsTableSeeder::class);
        $this->call(StockAdjustmentDetailsTableSeeder::class);
        $this->call(StockMovesTableSeeder::class);
        $this->call(StockTransfersTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TaskAssignsTableSeeder::class);
        $this->call(TaskCommentsTableSeeder::class);
        $this->call(TaskTimersTableSeeder::class);
        $this->call(ChecklistItemsTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(ActivitiesTableSeeder::class);
        $this->call(MilestonesTableSeeder::class);
        $this->call(ProjectMembersTableSeeder::class);
        $this->call(ProjectSettingsTableSeeder::class);
        $this->call(TicketsTableSeeder::class);
        $this->call(TicketRepliesTableSeeder::class);
        $this->call(PurchaseOrdersTableSeeder::class);
        $this->call(PurchaseOrderDetailsTableSeeder::class);
        $this->call(PurchaseTaxesTableSeeder::class);
        $this->call(ReceivedOrdersTableSeeder::class);
        $this->call(ReceivedOrderDetailsTableSeeder::class);
        $this->call(SaleOrdersTableSeeder::class);
        $this->call(SaleOrderDetailsTableSeeder::class);
        $this->call(SalePricesTableSeeder::class);
        $this->call(SaleTaxesTableSeeder::class);
        $this->call(ShipmentsTableSeeder::class);
        $this->call(ShipmentDetailsTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(TransfersTableSeeder::class);
        $this->call(SupplierTransactionsTableSeeder::class);
        $this->call(ExpensesTableSeeder::class);
        $this->call(DepositsTableSeeder::class);
        $this->call(CustomerTransactionsTableSeeder::class);
        $this->call(CustomItemOrdersTableSeeder::class);
        $this->call(PaymentGatewaysTableSeeder::class);
        $this->call(GeneralLedgerTransactionsTableSeeder::class);
        $this->call(CurrencyConverterConfigurationsTableSeeder::class);
        $this->call(CaptchaConfigurationsTableSeeder::class);
        $this->call(PermissionRolesTableSeeder::class);
    }
}
