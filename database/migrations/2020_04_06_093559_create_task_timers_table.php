<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskTimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_timers', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable()->index('task_timers_user_id_foreign_idx');
			$table->integer('task_id')->unsigned()->index('task_timers_task_id_foreign_idx');
			$table->string('start_time', 64);
			$table->string('end_time', 64)->nullable();
			$table->decimal('hourly_rate', 12, 8)->default(0);
			$table->text('note', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_timers');
	}

}
