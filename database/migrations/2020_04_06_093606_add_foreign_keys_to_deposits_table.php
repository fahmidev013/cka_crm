<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDepositsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('deposits', function(Blueprint $table)
		{
			$table->foreign('account_id', 'deposits_account_id_foreign')->references('id')->on('accounts')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('income_expense_category_id', 'deposits_income_expense_category_id_foreign')->references('id')->on('income_expense_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('payment_method_id', 'deposits_payment_method_id_foreign')->references('id')->on('payment_methods')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('transaction_reference_id', 'deposits_transaction_reference_id_foreign')->references('id')->on('transaction_references')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'deposits_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('deposits', function(Blueprint $table)
		{
			$table->dropForeign('deposits_account_id_foreign');
			$table->dropForeign('deposits_income_expense_category_id_foreign');
			$table->dropForeign('deposits_payment_method_id_foreign');
			$table->dropForeign('deposits_transaction_reference_id_foreign');
			$table->dropForeign('deposits_user_id_foreign');
		});
	}

}
