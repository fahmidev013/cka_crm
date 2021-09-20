<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSalePricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sale_prices', function(Blueprint $table)
		{
			$table->foreign('item_id', 'sale_prices_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('sale_type_id', 'sale_prices_sale_type_id_foreign')->references('id')->on('sale_types')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('currency_id', 'sales_prices_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('item_id', 'sales_prices_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('sale_type_id', 'sales_prices_sale_type_id_foreign')->references('id')->on('sale_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sale_prices', function(Blueprint $table)
		{
			$table->dropForeign('sale_prices_item_id_foreign');
			$table->dropForeign('sale_prices_sale_type_id_foreign');
			$table->dropForeign('sales_prices_currency_id_foreign');
			$table->dropForeign('sales_prices_item_id_foreign');
			$table->dropForeign('sales_prices_sale_type_id_foreign');
		});
	}

}
