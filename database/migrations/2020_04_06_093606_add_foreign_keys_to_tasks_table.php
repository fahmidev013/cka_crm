<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tasks', function(Blueprint $table)
		{
			$table->foreign('milestone_id','tasks_milestone_id_foreign')->references('id')->on('milestones')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('priority_id', 'tasks_priority_id_foreign')->references('id')->on('priorities')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('task_status_id', 'tasks_task_status_id_foreign')->references('id')->on('task_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'tasks_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tasks', function(Blueprint $table)
		{
			$table->dropForeign('tasks_milestone_id_foreign');
			$table->dropForeign('tasks_priority_id_foreign');
			$table->dropForeign('tasks_task_status_id_foreign');
			$table->dropForeign('tasks_user_id_foreign');
		});
	}

}
