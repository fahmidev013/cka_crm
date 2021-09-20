<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStockAdjustmentDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_adjustment_details', function(Blueprint $table)
		{
			$table->foreign('item_id','stock_adjustment_details_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('stock_adjustment_id', 'stock_adjustment_details_stock_adjustment_id_foreign')->references('id')->on('stock_adjustments')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_adjustment_details', function(Blueprint $table)
		{
			$table->dropForeign('stock_adjustment_details_item_id_foreign');
			$table->dropForeign('stock_adjustment_details_stock_adjustment_id_foreign');
		});
	}

}
