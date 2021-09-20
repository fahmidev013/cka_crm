<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_order_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'purchase_order_details_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('purchase_order_id', 'purchase_order_details_purchase_order_id_foreign')->references('id')->on('purchase_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_order_details', function(Blueprint $table)
		{
			$table->dropForeign('purchase_order_details_item_id_foreign');
			$table->dropForeign('purchase_order_details_purchase_order_id_foreign');
		});
	}

}
