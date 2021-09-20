<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMilestonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('milestones', function(Blueprint $table)
		{
			$table->foreign('project_id', 'milestones_project_id_foreign')->references('id')->on('projects')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('milestones', function(Blueprint $table)
		{
			$table->dropForeign('milestones_project_id_foreign');
		});
	}

}
