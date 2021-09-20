<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_taxes', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->unsignedBigInteger('sale_order_detail_id')->index('sale_taxes_sale_order_detail_id_foreign_idx');
			$table->integer('tax_type_id')->unsigned()->index('sale_taxes_tax_type_id_foreign_idx');
			$table->decimal('tax_amount', 16, 8);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale_taxes');
	}

}
