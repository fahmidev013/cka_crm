<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskAssignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_assigns', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable()->index('task_assigns_user_id_foreign_idx');
			$table->integer('task_id')->unsigned()->index('task_assigns_task_id_foreign_idx');
			$table->integer('assigned_by')->unsigned()->index('task_assigns_assigned_by_foreign_idx');
			$table->boolean('is_assigned_by_customer')->default(0)->comment('0 for not;
1 for yes;');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_assigns');
	}

}
