<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSaleOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sale_order_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'sale_order_details_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('sale_order_id', 'sale_order_details_sale_order_id_foreign')->references('id')->on('sale_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sale_order_details', function(Blueprint $table)
		{
			$table->dropForeign('sale_order_details_item_id_foreign');
			$table->dropForeign('sale_order_details_sale_order_id_foreign');
		});
	}

}
