<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTaskCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_comments', function(Blueprint $table)
		{
			$table->foreign('customer_id', 'task_comments_customer_id_foreign')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('task_id', 'task_comments_task_id_foreign')->references('id')->on('tasks')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'task_comments_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_comments', function(Blueprint $table)
		{
			$table->dropForeign('task_comments_customer_id_foreign');
			$table->dropForeign('task_comments_task_id_foreign');
			$table->dropForeign('task_comments_user_id_foreign');
		});
	}

}
