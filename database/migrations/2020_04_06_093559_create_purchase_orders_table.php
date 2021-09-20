<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_orders', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->bigIncrements('id');
			$table->integer('supplier_id')->unsigned()->index('purchase_orders_supplier_id_foreign_idx');
			$table->integer('user_id')->unsigned()->nullable()->index('purchase_orders_user_id_foreign_idx');
			$table->string('invoice_type', 15)->comment('\'quantity\', \n\'hours\', \n\'amount\'');
			$table->string('discount_on', 15)->comment('\'before\', \n\'after\'');
			$table->string('tax_type', 16)->comment('\'exclusive\',
\'inclusive\'');
			$table->boolean('has_tax');
			$table->boolean('has_description');
			$table->boolean('has_item_discount');
			$table->boolean('has_hsn');
			$table->boolean('has_other_discount');
			$table->boolean('has_shipping_charge');
			$table->boolean('has_custom_charge');
			$table->decimal('other_discount_amount', 16, 8)->nullable();
			$table->string('other_discount_type', 1)->comment('% or $');
			$table->decimal('shipping_charge', 16, 8)->nullable();
			$table->string('custom_charge_title', 199)->nullable();
			$table->decimal('custom_charge_amount', 16, 8);
			$table->integer('currency_id')->unsigned()->index('purchase_orders_currency_id_foreign_idx');
			$table->decimal('exchange_rate', 16, 8);
			$table->integer('purchase_receive_type_id')->unsigned()->index('purchase_orders_purchase_receive_type_id_foreign_idx');
			$table->text('comments', 65535)->nullable();
			$table->boolean('has_comment')->default(0);
			$table->date('order_date');
			$table->string('reference', 30);
			$table->integer('location_id')->unsigned()->index('purchase_orders_location_id_foreign_idx');
			$table->string('payment_method_id', 50)->nullable()->index();
			$table->decimal('total', 16, 8)->default(0);
			$table->decimal('paid', 16, 8)->default(0);
			$table->integer('payment_term_id')->unsigned()->index('purchase_orders_payment_term_id_foreign_idx');
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
		Schema::drop('purchase_orders');
	}

}
