<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProjectMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_members', function(Blueprint $table)
		{
			$table->foreign('project_id', 'project_members_project_id_foreign')->references('id')->on('projects')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('user_id', 'project_members_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_members', function(Blueprint $table)
		{
			$table->dropForeign('project_members_project_id_foreign');
			$table->dropForeign('project_members_user_id_foreign');
		});
	}

}
