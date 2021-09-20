<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_methods', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('name', 150)->index();
			$table->string('mode', 45)->nullable();
			$table->string('client_id')->nullable();
			$table->string('consumer_key')->nullable();
			$table->string('consumer_secret')->nullable();
			$table->string('approve', 45)->nullable();
			$table->boolean('is_default')->default(0)->index()->comment('0 for not default;
1 for default');
			$table->boolean('is_active')->comment('0 for inactive;\n1 for active');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_methods');
	}

}
