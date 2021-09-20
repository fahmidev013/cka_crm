<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStockCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_categories', function(Blueprint $table)
		{
			$table->foreign('item_unit_id', 'stock_categories_item_unit_id_foreign')->references('id')->on('item_units')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_categories', function(Blueprint $table)
		{
			$table->dropForeign('stock_categories_item_unit_id_foreign');
		});
	}

}
