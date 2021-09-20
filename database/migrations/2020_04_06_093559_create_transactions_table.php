<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->bigIncrements('id');
			$table->integer('currency_id')->unsigned()->index('transactions_currency_id_foreign_idx');
			$table->decimal('amount', 16, 8)->index();
			$table->string('transaction_type', 100)->index();
			$table->integer('account_id')->unsigned()->nullable()->index('transactions_account_id_foreign_idx')->comment('account_id refers accounts table id');
			$table->date('transaction_date')->index();
			$table->integer('user_id')->unsigned()->nullable()->index('transactions_user_id_foreign_idx')->comment('user_id refers users table id');
			$table->integer('transaction_reference_id')->unsigned()->index('transactions_transaction_reference_id_foreign_idx')->comment('transaction_reference_id refers transaction_references table id');
			$table->string('transaction_method', 50)->index();
			$table->text('description')->nullable();
			$table->integer('payment_method_id')->unsigned()->nullable()->index('transactions_payment_method_id_foreign_idx')->comment('payment_method_id references payment_methods table id');
			$table->text('params', 65535)->nullable();
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
		Schema::drop('transactions');
	}

}
