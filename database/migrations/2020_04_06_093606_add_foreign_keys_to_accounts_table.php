<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->foreign('account_type_id', 'accounts_account_type_id_foreign')->references('id')->on('account_types')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('currency_id', 'accounts_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('income_expense_category_id', 'accounts_income_expense_category_id_foreign')->references('id')->on('income_expense_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->dropForeign('accounts_account_type_id_foreign');
			$table->dropForeign('accounts_currency_id_foreign');
			$table->dropForeign('accounts_income_expense_category_id_foreign');
		});
	}

}
