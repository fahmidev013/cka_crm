<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSaleOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sale_orders', function(Blueprint $table)
		{
			$table->foreign('currency_id', 'sale_orders_currency_id_foreign')->references('id')->on('currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('customer_branch_id', 'sale_orders_customer_branch_id_foreign')->references('id')->on('customer_branches')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('customer_id', 'sale_orders_customer_id_foreign')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('location_id', 'sale_orders_location_id_foreign')->references('id')->on('locations')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('payment_term_id', 'sale_orders_payment_term_id_foreign')->references('id')->on('payment_terms')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('project_id', 'sale_orders_project_id_foreign')->references('id')->on('projects')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('user_id', 'sale_orders_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sale_orders', function(Blueprint $table)
		{
			$table->dropForeign('sale_orders_currency_id_foreign');
			$table->dropForeign('sale_orders_customer_branch_id_foreign');
			$table->dropForeign('sale_orders_customer_id_foreign');
			$table->dropForeign('sale_orders_location_id_foreign');
			$table->dropForeign('sale_orders_payment_term_id_foreign');
			$table->dropForeign('sale_orders_project_id_foreign');
			$table->dropForeign('sale_orders_user_id_foreign');
		});
	}

}
