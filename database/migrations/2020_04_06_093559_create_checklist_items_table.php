<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChecklistItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('checklist_items', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->increments('id');
			$table->string('title')->index();
			$table->integer('task_id')->unsigned()->nullable()->index('checklist_items_task_id_foreign_idx')->comment('task_id refers the task table\'s id column.');
			$table->boolean('is_checked')->default(0)->index()->comment('1, 0 and 0 as default;\n0 for unchecked;\n1 for checked;');
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('checked_at')->nullable()->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('checklist_items');
	}

}
