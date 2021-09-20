<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIpAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ip_addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 15)->comment('Type can be Debug, Whitelisted, Banned');
			$table->string('value', 15);
			$table->text('description');
			$table->boolean('is_active')->default(1)->comment('	1 for active; 0 for inactive.');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ip_addresses');
	}

}
