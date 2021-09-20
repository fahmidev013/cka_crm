<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUrlShortnerConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('url_shortner_config', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 50)->nullable();
			$table->string('status', 50)->nullable();
			$table->string('key')->nullable();
			$table->string('secretkey');
			$table->string('default', 50)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('url_shortner_config');
	}

}
