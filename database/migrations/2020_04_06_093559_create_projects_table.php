<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('name', 50)->nullable()->index();
			$table->text('detail')->nullable();
			$table->string('project_type', 20)->nullable()->index();
			$table->integer('customer_id')->unsigned()->nullable()->index('projects_customer_id_foreign_idx');
			$table->integer('user_id')->unsigned()->nullable()->index('projects_user_id_foreign_idx');
			$table->integer('project_status_id')->unsigned()->index('projects_status_index');
			$table->integer('charge_type')->unsigned()->comment('1 = Fixed Rate;
2 = Project Hour;
3 = Rate Per Hour;');
			$table->date('begin_date')->nullable()->index();
			$table->date('due_date')->nullable()->index();
			$table->integer('improvement');
			$table->boolean('improvement_from_task')->nullable()->comment('1=yes, 0=no');
			$table->date('completed_date')->nullable();
			$table->decimal('cost', 16, 8)->nullable();
			$table->decimal('per_hour_project_scale', 12, 8)->nullable();
			$table->decimal('estimated_hours', 8, 2)->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
