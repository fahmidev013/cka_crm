<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralLedgerTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_ledger_transactions', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('reference_id')->unsigned();
			$table->integer('reference_type')->unsigned();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('gl_account_id')->unsigned();
			$table->integer('currency_id')->unsigned();
			$table->decimal('exchange_rate', 16, 8);
			$table->decimal('amount', 16, 8);
			$table->string('comment', 200)->nullable();
			$table->boolean('is_reversed')->default(0)->comment('1 for yes, 0 for no');
			$table->date('transaction_date');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_ledger_transactions');
	}

}
