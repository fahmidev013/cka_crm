<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('name', 100)->nullable()->index();
			$table->text('description')->nullable();
			$table->integer('priority_id')->unsigned()->index('tasks_priority_id_foreign_idx');
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->date('start_date')->nullable();
			$table->date('due_date')->nullable();
			$table->date('finished_date')->nullable();
			$table->integer('user_id')->unsigned()->nullable()->index('tasks_user_id_foreign_idx');
			$table->integer('task_status_id')->unsigned()->index('tasks_task_status_id_foreign_idx');
			$table->string('recurring_type', 20)->nullable();
			$table->integer('repeat_every')->unsigned()->nullable();
			$table->integer('recurring')->unsigned();
			$table->date('recurring_ends_on')->nullable();
			$table->integer('related_to_id')->unsigned()->nullable();
			$table->integer('related_to_type')->nullable()->comment('1=Project, 2= Customer, 3=Ticket');
			$table->integer('custom_recurring')->unsigned()->default(0);
			$table->date('last_recurring_date')->nullable();
			$table->boolean('is_public')->nullable()->default(0);
			$table->boolean('is_billable')->nullable()->default(0)->index();
			$table->boolean('is_billed')->index('tasks_is_billed_foreign');
			$table->integer('invoice_id')->unsigned();
			$table->decimal('hourly_rate', 12, 8)->nullable();
			$table->integer('milestone_id')->unsigned()->nullable()->index('tasks_milestone_id_foreign_idx');
			$table->boolean('is_visible_to_customer')->nullable()->default(0);
			$table->integer('deadline_notified')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
