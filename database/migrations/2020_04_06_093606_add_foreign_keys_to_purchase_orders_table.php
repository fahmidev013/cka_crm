<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_orders', function(Blueprint $table)
		{
			$table->foreign('currency_id','purchase_orders_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('location_id' , 'purchase_orders_location_id_foreign')->references('id')->on('locations')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('payment_term_id', 'purchase_orders_payment_term_id_foreign')->references('id')->on('payment_terms')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('purchase_receive_type_id', 'purchase_orders_purchase_receive_type_id_foreign')->references('id')->on('purchase_receive_types')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('supplier_id', 'purchase_orders_supplier_id_foreign')->references('id')->on('suppliers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'purchase_orders_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_orders', function(Blueprint $table)
		{
			$table->dropForeign('purchase_orders_currency_id_foreign');
			$table->dropForeign('purchase_orders_location_id_foreign');
			$table->dropForeign('purchase_orders_payment_term_id_foreign');
			$table->dropForeign('purchase_orders_purchase_receive_type_id_foreign');
			$table->dropForeign('purchase_orders_supplier_id_foreign');
			$table->dropForeign('purchase_orders_user_id_foreign');
		});
	}

}
