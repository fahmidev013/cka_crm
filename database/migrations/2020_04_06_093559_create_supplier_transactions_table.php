<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupplierTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_transactions', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable()->index('supplier_transactions_user_id_foreign)idx');
			$table->integer('transaction_reference_id')->unsigned();
			$table->integer('currency_id')->unsigned()->index('supplier_transactions_currency_id_foreign_idx');
			$table->integer('supplier_id')->unsigned()->index('supplier_transactions_supplier_id_foreign_idx');
			$table->unsignedBigInteger('purchase_order_id')->index('supplier_transactions_purchase_order_id_foreign_idx');
			$table->integer('payment_method_id')->unsigned()->nullable()->index('supplier_transactions_payment_method_id_foreign_idx');
			$table->date('transaction_date')->nullable()->index();
			$table->decimal('amount', 16, 8)->nullable()->index();
			$table->decimal('exchange_rate', 16, 8)->unsigned()->index();
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
		Schema::drop('supplier_transactions');
	}

}
