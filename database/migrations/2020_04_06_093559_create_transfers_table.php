<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransfersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transfers', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('from_account_id')->unsigned()->index('transfers_from_account_id_foreign_idx');
			$table->integer('to_account_id')->unsigned()->index('transfers_to_account_id_foreign_idx');
			$table->integer('user_id')->unsigned()->nullable()->index('transfers_user_id_foreign_idx');
			$table->integer('from_currency_id')->unsigned()->index('transfers_from_currency_id_foreign_idx');
			$table->integer('to_currency_id')->unsigned()->index('transfers_to_currency_id_foreign_idx');
			$table->date('transaction_date')->index();
			$table->integer('transaction_reference_id')->unsigned()->index('transfers_transaction_reference_id_foreign_idx');
			$table->string('description', 200)->nullable();
			$table->decimal('amount', 16, 8)->index();
			$table->decimal('fee', 16, 8);
			$table->decimal('exchange_rate', 16, 8)->nullable();
			$table->decimal('incoming_amount', 16, 8)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transfers');
	}

}
