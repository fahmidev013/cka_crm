<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_transactions', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->bigIncrements('id');
			$table->integer('user_id')->unsigned()->nullable()->index('customer_transactions_user_id_foreign_idx');
			$table->integer('account_id')->unsigned()->nullable()->index('customer_transactions_account_id_foreign_idx');
			$table->integer('payment_method_id')->unsigned()->nullable()->index('customer_transactions_payment_method_id_foreign_idx');
			$table->integer('customer_id')->unsigned()->index('customer_transactions_customer_id_foreign_idx');
			$table->unsignedBigInteger('sale_order_id')->index('customer_transactions_invoice_id_foreign_idx');
			$table->integer('transaction_reference_id')->unsigned()->index('customer_transactions_transaction_reference_id_foreign_idx');
			$table->integer('currency_id')->unsigned()->index('customer_transactions_currency_id_foreign_idx');
			$table->date('transaction_date')->index();
			$table->decimal('amount', 16, 8)->index();
			$table->decimal('exchange_rate', 16, 8);
			$table->string('status', 16)->default('Approved')->index()->comment('\'Pending\', \'Approved\', \'Declined\'');
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customer_transactions');
	}

}
