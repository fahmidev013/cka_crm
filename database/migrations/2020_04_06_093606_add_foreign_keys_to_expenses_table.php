<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('expenses', function(Blueprint $table)
		{
			$table->foreign('currency_id', 'expenses_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('income_expense_category_id', 'expenses_income_expense_category_id_foreign')->references('id')->on('income_expense_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('payment_method_id', 'expenses_payment_method_id_foreign')->references('id')->on('payment_methods')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('transaction_id', 'expenses_transaction_id_foreign')->references('id')->on('transactions')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('transaction_reference_id', 'expenses_transaction_reference_id_foreign')->references('id')->on('transaction_references')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'expenses_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('expenses', function(Blueprint $table)
		{
			$table->dropForeign('expenses_currency_id_foreign');
			$table->dropForeign('expenses_income_expense_category_id_foreign');
			$table->dropForeign('expenses_payment_method_id_foreign');
			$table->dropForeign('expenses_transaction_id_foreign');
			$table->dropForeign('expenses_transaction_reference_id_foreign');
			$table->dropForeign('expenses_user_id_foreign');
		});
	}

}
