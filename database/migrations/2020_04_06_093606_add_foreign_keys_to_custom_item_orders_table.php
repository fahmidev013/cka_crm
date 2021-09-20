<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCustomItemOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('custom_item_orders', function(Blueprint $table)
		{
			$table->foreign('tax_type_id', 'custom_item_orders_tax_type_id_foreign')->references('id')->on('tax_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('custom_item_orders', function(Blueprint $table)
		{
			$table->dropForeign('custom_item_orders_tax_type_id_foreign');
		});
	}

}
