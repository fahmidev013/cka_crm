<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchasePricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_prices', function(Blueprint $table)
		{
			$table->foreign('currency_id', 'purchase_prices_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('item_id', 'purchase_prices_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_prices', function(Blueprint $table)
		{
			$table->dropForeign('purchase_prices_currency_id_foreign');
			$table->dropForeign('purchase_prices_item_id_foreign');
		});
	}

}
