<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('account_type_id')->unsigned()->index('accounts_account_type_id_foreign_idx');
			$table->string('name', 30)->index('accounts_name_index');
			$table->string('account_number', 30)->index('accounts_account_number_index');
			$table->integer('income_expense_category_id')->unsigned()->nullable()->index('accounts_income_expense_category_id_foreign_idx');
			$table->integer('currency_id')->unsigned()->index('accounts_currency_id_foreign_idx');
			$table->string('bank_name', 100)->index('accounts_bank_name_index');
			$table->string('branch_name', 50)->nullable();
			$table->string('branch_city', 50)->nullable();
			$table->string('swift_code', 100)->nullable();
			$table->string('bank_address')->nullable();
			$table->boolean('is_default')->default(0)->index('accounts_is_default_index');
			$table->boolean('is_deleted')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}

}
