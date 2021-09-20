<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('name')->index();
			$table->string('first_name', 100)->nullable()->index();
			$table->string('last_name', 100)->nullable()->index();
			$table->string('email')->nullable()->index();
			$table->string('password');
			$table->string('phone', 20)->nullable()->index();
			$table->string('tax_id', 50)->nullable();
			$table->integer('currency_id')->unsigned()->index('customers_currency_id_foreign_idx')->comment('currency_id refers to currencies tbale id.');
			$table->string('customer_type', 16)->nullable();
			$table->string('timezone', 191)->nullable();
			$table->string('remember_token');
			$table->boolean('is_active')->default(1)->index('customers_is_inactive_index')->comment('1 for active;\n0 for inactive.');
			$table->boolean('is_verified')->default(0)->index('customers_is_not_verified_index')->comment('1 for vairified;0 for not varified.');
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
		Schema::drop('customers');
	}

}
