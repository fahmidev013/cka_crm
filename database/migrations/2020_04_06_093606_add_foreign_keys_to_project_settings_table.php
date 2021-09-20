<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProjectSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_settings', function(Blueprint $table)
		{
			$table->foreign('project_id', 'project_settings_project_id_foreign')->references('id')->on('projects')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_settings', function(Blueprint $table)
		{
			$table->dropForeign('project_settings_project_id_foreign');
		});
	}

}
