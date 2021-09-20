<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockAdjustmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_adjustments', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable()->index('stock_adjustments_user_id_foreign_idx');
			$table->integer('location_id')->unsigned()->index('stock_adjustments_location_id_foreign_idx');
			$table->string('transaction_type', 20);
			$table->decimal('total_quantity', 16, 8);
			$table->text('note', 65535)->nullable();
			$table->date('transaction_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_adjustments');
	}

}
