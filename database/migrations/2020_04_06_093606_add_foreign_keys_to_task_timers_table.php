<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTaskTimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_timers', function(Blueprint $table)
		{
			$table->foreign('task_id', 'task_timers_task_id_foreign')->references('id')->on('tasks')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id','task_timers_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_timers', function(Blueprint $table)
		{
			$table->dropForeign('task_timers_task_id_foreign');
			$table->dropForeign('task_timers_user_id_foreign');
		});
	}

}
