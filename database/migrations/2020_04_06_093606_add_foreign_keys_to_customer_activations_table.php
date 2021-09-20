<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCustomerActivationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_activations', function(Blueprint $table)
		{
			$table->foreign('customer_id', 'customer_activations_customer_id_foreign')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_activations', function(Blueprint $table)
		{
			$table->dropForeign('customer_activations_customer_id_foreign');
		});
	}

}
