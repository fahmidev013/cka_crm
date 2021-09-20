<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToItemCustomVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_custom_variants', function(Blueprint $table)
		{
			$table->foreign('item_id', 'item_custom_variants_item_id_foreign')->references('id')->on('items')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_custom_variants', function(Blueprint $table)
		{
			$table->dropForeign('item_custom_variants_item_id_foreign');
		});
	}

}
