<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('suppliers', function(Blueprint $table)
		{
			$table->foreign('country_id', 'suppliers_country_id_foreign')->references('id')->on('countries')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('currency_id','suppliers_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('suppliers', function(Blueprint $table)
		{
			$table->dropForeign('suppliers_country_id_foreign');
			$table->dropForeign('suppliers_currency_id_foreign');
		});
	}

}
