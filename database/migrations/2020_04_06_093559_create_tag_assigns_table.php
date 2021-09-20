<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagAssignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag_assigns', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('tag_type', 20)->index('tags_assigns_tag_type_idx');
			$table->integer('tag_id')->unsigned()->index('tags_in_tag_id_foreign_idx');
			$table->integer('reference_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tag_assigns');
	}

}
