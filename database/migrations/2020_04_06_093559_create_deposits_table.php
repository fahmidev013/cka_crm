<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepositsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deposits', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('deposits_account_id_foreign_idx');
			$table->integer('user_id')->unsigned()->nullable()->index('deposits_user_id_foreign_idx');
			$table->integer('income_expense_category_id')->unsigned()->index('deposits_income_expense_category_id_foreign_idx');
			$table->integer('transaction_reference_id')->unsigned()->index('deposits_transaction_reference_id_foreign_idx');
			$table->integer('payment_method_id')->unsigned()->index('deposits_payment_method_id_foreign_idx');
			$table->date('transaction_date')->index();
			$table->text('description')->nullable();
			$table->decimal('amount', 16, 8)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('deposits');
	}

}
