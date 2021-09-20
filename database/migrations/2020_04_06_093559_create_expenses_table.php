<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->unsignedBigInteger('transaction_id')->nullable()->index('expenses_transaction_id_foreign_idx')->comment('transaction_id refers transactions tables\' id;');
			$table->integer('user_id')->unsigned()->nullable()->index('expenses_user_id_foreign_idx')->comment('user_id refers users tables\' id;');
			$table->integer('transaction_reference_id')->unsigned()->index('expenses_transaction_reference_id_foreign_idx')->comment('transaction_reference_id refers transaction_references tables\'  id;');
			$table->integer('income_expense_category_id')->unsigned()->index('expenses_income_expense_category_id_foreign_idx')->comment('income_expense_category_id refers income_expense_categories tables\' id;');
			$table->integer('currency_id')->unsigned()->index('expenses_currency_id_foreign_idx')->comment('currency_id refers currencies tables\' id;');
			$table->integer('payment_method_id')->unsigned()->nullable()->index('expenses_payment_method_id_foreign_idx')->comment('payment_method_id refers payment_methods tables\' id;');
			$table->decimal('amount', 16, 8)->index();
			$table->string('note', 200)->nullable();
			$table->date('transaction_date')->index();
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
		Schema::drop('expenses');
	}

}
