<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('items', function(Blueprint $table)
		{
			$table->foreign('item_unit_id', 'items_item_unit_id_foreign')->references('id')->on('item_units')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('stock_category_id', 'items_stock_category_id_foreign')->references('id')->on('stock_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tax_type_id', 'items_tax_type_id_foreign')->references('id')->on('tax_types')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('weight_unit_id', 'items_weight_unit_id_foreign')->references('id')->on('item_units')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function(Blueprint $table)
		{
			$table->dropForeign('items_item_unit_id_foreign');
			$table->dropForeign('items_stock_category_id_foreign');
			$table->dropForeign('items_tax_type_id_foreign');
			$table->dropForeign('items_weight_unit_id_foreign');
		});
	}

}
