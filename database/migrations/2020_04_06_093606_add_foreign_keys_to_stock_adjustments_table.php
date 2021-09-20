<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStockAdjustmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_adjustments', function(Blueprint $table)
		{
			$table->foreign('location_id','stock_adjustments_location_id_foreign')->references('id')->on('locations')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'stock_adjustments_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_adjustments', function(Blueprint $table)
		{
			$table->dropForeign('stock_adjustments_location_id_foreign');
			$table->dropForeign('stock_adjustments_user_id_foreign');
		});
	}

}
