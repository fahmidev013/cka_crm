<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects', function(Blueprint $table)
		{
			$table->foreign('customer_id', 'projects_customer_id_foreign')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('project_status_id', 'projects_project_status_id_foreign')->references('id')->on('project_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'projects_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projects', function(Blueprint $table)
		{
			$table->dropForeign('projects_customer_id_foreign');
			$table->dropForeign('projects_project_status_id_foreign');
			$table->dropForeign('projects_user_id_foreign');
		});
	}

}
