<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockMovesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_moves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedBigInteger('item_id')->index('stock_moves_item_id_foreign_idx')->comment('item_id refers items table id');
			$table->integer('transaction_type_id')->unsigned()->nullable()->index()->comment('Id of sale_orders, purchase_orders, stock_adjustments, stock_transfers, items (initial stock)');
			$table->string('transaction_type', 25)->index();
			$table->integer('location_id')->unsigned()->index('stock_moves_location_id_foreign_idx')->comment('location_id refers locations table id');
			$table->date('transaction_date')->index();
			$table->integer('user_id')->unsigned()->nullable()->index('stock_moves_user_id_foreign_idx')->comment('user_id refers users table id');
			$table->integer('transaction_type_detail_id')->unsigned()->nullable()->index()->comment('Id of sale_order_details, purchase_order_details, stock_adjustment_details, stock_transfers, items (initial stock)');
			$table->string('reference', 30);
			$table->string('note')->nullable();
			$table->decimal('quantity', 16, 8)->default(0);
			$table->decimal('price', 16, 8)->default(0)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_moves');
	}

}
