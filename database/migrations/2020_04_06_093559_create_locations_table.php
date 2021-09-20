<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('code', 10)->unique();
			$table->string('name', 60)->index();
			$table->string('delivery_address', 60);
			$table->string('email', 60)->nullable()->index();
			$table->string('phone', 60)->nullable()->index();
			$table->string('fax', 60)->nullable();
			$table->string('contact', 60)->nullable();
			$table->boolean('is_active')->default(1)->index()->comment('1 = \'Active\'\n 0 = \'Inactive\'');
			$table->boolean('is_default')->default(0)->comment('1 = \'Yes\'\n 0 = \'No\'');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('locations');
	}

}
