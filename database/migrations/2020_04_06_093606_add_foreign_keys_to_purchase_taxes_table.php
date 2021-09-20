<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_taxes', function(Blueprint $table)
		{
			$table->foreign('purchase_order_detail_id', 'purchase_taxes_purchase_order_detail_id_foreign')->references('id')->on('purchase_order_details')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tax_type_id', 'purchase_taxes_tax_type_id_foreign')->references('id')->on('tax_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_taxes', function(Blueprint $table)
		{
			$table->dropForeign('purchase_taxes_purchase_order_detail_id_foreign');
			$table->dropForeign('purchase_taxes_tax_type_id_foreign');
		});
	}

}
