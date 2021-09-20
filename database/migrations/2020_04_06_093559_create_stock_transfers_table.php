<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockTransfersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_transfers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable()->index('stock_transfers_user_id_foreign_idx');
			$table->integer('source_location_id')->unsigned()->index('stock_transfers_source_location_id_foreign_idx');
			$table->integer('destination_location_id')->unsigned()->index('stock_transfers_destination_location_id_foreign_idx');
			$table->text('note', 65535)->nullable();
			$table->decimal('quantity', 16, 8);
			$table->date('transfer_date')->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_transfers');
	}

}
