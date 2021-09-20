<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalePricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_prices', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->unsignedBigInteger('item_id')->unsigned()->index('sales_prices_item_id_foreign_idx');
			$table->integer('sale_type_id')->unsigned()->index('sale_prices_sale_type_id_foreign_idx');
			$table->integer('currency_id')->unsigned()->index('sales_prices_currency_id_foreign_idx');
			$table->decimal('price', 16, 8);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale_prices');
	}

}
