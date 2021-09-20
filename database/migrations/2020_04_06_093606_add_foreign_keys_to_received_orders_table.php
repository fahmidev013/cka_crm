<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReceivedOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('received_orders', function(Blueprint $table)
		{
			$table->foreign('purchase_order_id', 'receive_orders_purchase_order_id_foreign')->references('id')->on('purchase_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('supplier_id', 'receive_orders_supplier_id_foreign')->references('id')->on('suppliers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'receive_orders_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('location_id', 'received_orders_location_id_foreign')->references('id')->on('locations')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('received_orders', function(Blueprint $table)
		{
			$table->dropForeign('receive_orders_purchase_order_id_foreign');
			$table->dropForeign('receive_orders_supplier_id_foreign');
			$table->dropForeign('receive_orders_user_id_foreign');
			$table->dropForeign('received_orders_location_id_foreign');
		});
	}

}
