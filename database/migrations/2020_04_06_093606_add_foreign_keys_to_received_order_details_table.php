<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReceivedOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('received_order_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'receive_order_details_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('purchase_order_detail_id', 'received_order_details_purchase_order_detail_id_foreign')->references('id')->on('purchase_order_details')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('purchase_order_id', 'received_order_details_purchase_order_id_foreign')->references('id')->on('purchase_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('received_order_id', 'received_order_details_received_order_id_foreign')->references('id')->on('received_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('received_order_details', function(Blueprint $table)
		{
			$table->dropForeign('receive_order_details_item_id_foreign');
			$table->dropForeign('received_order_details_purchase_order_detail_id_foreign');
			$table->dropForeign('received_order_details_purchase_order_id_foreign');
			$table->dropForeign('received_order_details_received_order_id_foreign');
		});
	}

}
