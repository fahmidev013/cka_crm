<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSupplierTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('supplier_transactions', function(Blueprint $table)
		{
			$table->foreign('currency_id','supplier_transactions_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('payment_method_id', 'supplier_transactions_payment_method_id_foreign')->references('id')->on('payment_methods')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('purchase_order_id', 'supplier_transactions_purchase_order_id_foreign')->references('id')->on('purchase_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('supplier_id', 'supplier_transactions_supplier_id_foreign')->references('id')->on('suppliers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'supplier_transactions_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('supplier_transactions', function(Blueprint $table)
		{
			$table->dropForeign('supplier_transactions_currency_id_foreign');
			$table->dropForeign('supplier_transactions_payment_method_id_foreign');
			$table->dropForeign('supplier_transactions_purchase_order_id_foreign');
			$table->dropForeign('supplier_transactions_supplier_id_foreign');
			$table->dropForeign('supplier_transactions_user_id_foreign');
		});
	}

}
