<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suppliers', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('name', 150)->index();
			$table->string('email', 100)->index()->nullable();
			$table->string('street', 50)->nullable();
			$table->string('contact', 30)->nullable()->index();
			$table->string('tax_id', 50)->nullable();
			$table->integer('currency_id')->unsigned()->index('suppliers_currency_id_foreign_idx')->comment('currency_id refers currencies table id');
			$table->string('city', 50)->nullable();
			$table->string('state', 50)->nullable()->index();
			$table->string('zipcode', 20)->nullable();
			$table->integer('country_id')->unsigned()->nullable()->index('suppliers_country_id_foreign_idx');
			$table->boolean('is_active')->default(1)->index()->comment('1 for active; \n 0 for inactive.');
			$table->timestamp('created_at')->nullable()->useCurrent();
			$table->timestamp('updated_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('suppliers');
	}

}
