<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transactions', function(Blueprint $table)
		{
            $table->foreign('currency_id', 'transactions_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('account_id', 'transactions_ account_id_foreign')->references('id')->on('accounts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'transactions_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('transaction_reference_id', 'transactions_transaction_reference_id_foreign')->references('id')->on('transaction_references')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('payment_method_id', 'transactions_payment_method_id_foreign')->references('id')->on('payment_methods')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transactions', function(Blueprint $table)
		{
			$table->dropForeign('transactions_account_id_foreign');
			$table->dropForeign('transactions_currency_id_foreign');
			$table->dropForeign('transactions_user_id_foreign');
			$table->dropForeign('transactions_payment_method_id_foreign');
			$table->dropForeign('transactions_transaction_reference_id_foreign');
		});
	}

}
