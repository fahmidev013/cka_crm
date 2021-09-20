<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSaleTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sale_taxes', function(Blueprint $table)
		{
			$table->foreign('sale_order_detail_id', 'sale_taxes_sale_order_detail_id_foreign')->references('id')->on('sale_order_details')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tax_type_id', 'sale_taxes_tax_type_id_foreign')->references('id')->on('tax_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sale_taxes', function(Blueprint $table)
		{
			$table->dropForeign('sale_taxes_sale_order_detail_id_foreign');
			$table->dropForeign('sale_taxes_tax_type_id_foreign');
		});
	}

}
