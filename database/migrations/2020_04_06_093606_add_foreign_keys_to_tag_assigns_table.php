<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTagAssignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tag_assigns', function(Blueprint $table)
		{
			$table->foreign('tag_id', 'tags_in_tag_id_foreign')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tag_assigns', function(Blueprint $table)
		{
			$table->dropForeign('tags_in_tag_id_foreign');
		});
	}

}
