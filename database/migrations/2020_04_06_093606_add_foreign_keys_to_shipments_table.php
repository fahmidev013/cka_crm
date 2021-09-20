<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToShipmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shipments', function(Blueprint $table)
		{
			$table->foreign('sale_order_id', 'shipments_sale_order_id_foreign')->references('id')->on('sale_orders')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shipments', function(Blueprint $table)
		{
			$table->dropForeign('shipments_sale_order_id_foreign');
		});
	}

}
