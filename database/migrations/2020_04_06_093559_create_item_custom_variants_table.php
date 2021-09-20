<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemCustomVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_custom_variants', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->integer('id', true);
			$table->unsignedBigInteger('item_id')->index('item_custom_variants_item_id_foreign_idx')->comment('item_id refers the items table id column');
			$table->string('variant_title', 240)->index();
			$table->string('variant_value', 240)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('item_custom_variants');
	}

}
