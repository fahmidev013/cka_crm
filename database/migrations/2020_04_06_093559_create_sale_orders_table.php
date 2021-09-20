<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_orders', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->bigIncrements('id');
			$table->string('transaction_type', 15);
			$table->string('invoice_type', 10)->index()->comment('quantity, hourse or amount');
			$table->string('order_type', 15);
			$table->integer('project_id')->unsigned()->nullable()->index('sale_orders_project_id_foreign_idx');
			$table->integer('customer_id')->unsigned()->nullable()->index('sale_orders_customer_id_foreign_idx');
			$table->integer('customer_branch_id')->unsigned()->nullable()->index('sale_orders_customer_branch_id_foreign_idx');
			$table->integer('user_id')->unsigned()->nullable()->index('sale_orders_user_id_foreign_idx');
			$table->string('tax_type', 10)->comment('exclusive or inclusive');
			$table->string('reference', 32);
			$table->integer('order_reference_id');
			$table->text('comment', 65535)->nullable();
			$table->boolean('has_comment');
			$table->date('order_date');
			$table->date('due_date')->nullable();
			$table->integer('location_id')->unsigned()->nullable()->index('sale_orders_location_id_foreign_idx');
			$table->string('payment_method_id', 15)->nullable();
			$table->string('discount_on', 15);
			$table->integer('currency_id')->unsigned()->index('sale_orders_currency_id_foreign_idx');
			$table->decimal('exchange_rate', 16, 8);
			$table->boolean('has_tax');
			$table->boolean('has_description');
			$table->boolean('has_item_discount');
			$table->boolean('has_hsn');
			$table->boolean('has_other_discount');
			$table->boolean('has_shipping_charge');
			$table->boolean('has_custom_charge');
			$table->decimal('other_discount_amount', 11, 8)->nullable();
			$table->string('other_discount_type', 1)->comment('% or $');
			$table->decimal('shipping_charge', 16, 8)->nullable();
			$table->string('custom_charge_title', 199)->nullable();
			$table->decimal('custom_charge_amount', 16, 8);
			$table->decimal('total', 16, 8)->default(0);
			$table->decimal('paid', 16, 8)->default(0);
			$table->decimal('amount_received', 16, 8)->nullable();
			$table->integer('payment_term_id')->unsigned()->nullable()->index('sale_orders_payment_term_id_foreign_idx');
			$table->string('pos_invoice_status', 10)->default('clear')->comment('clear or hold');
			$table->decimal('pos_discount_amount', 11, 8)->default(0);
			$table->string('pos_order_title', 50)->nullable();
			$table->decimal('pos_tax_on_order', 16, 8);
			$table->text('pos_shipping', 65535)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale_orders');
	}

}
