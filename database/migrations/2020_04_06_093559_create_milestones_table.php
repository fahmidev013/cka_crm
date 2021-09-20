<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMilestonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('milestones', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('project_id')->unsigned()->index('milestones_project_id_foreign_idx');
			$table->string('name')->index();
			$table->text('description')->nullable();
			$table->boolean('is_visible_to_customer')->nullable()->default(0)->comment('1=yes, 0=no');
			$table->date('due_date')->nullable()->index();
			$table->integer('order')->nullable();
			$table->string('color', 45)->nullable();
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
		Schema::drop('milestones');
	}

}
