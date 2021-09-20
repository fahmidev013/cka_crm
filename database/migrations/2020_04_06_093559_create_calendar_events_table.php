<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendar_events', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('title', 150)->index('calendar_events_title_index');
			$table->text('description', 65535)->nullable();
			$table->dateTime('start_date')->index();
			$table->dateTime('end_date')->nullable()->index();
			$table->integer('notification_start')->nullable()->comment('notification_start, it means how many days/hour/minutes/weeks before it will remind user about the event.');
			$table->boolean('notification_repeat_every')->nullable()->comment('notification_repeat_every, it means what is the unit of \'notification_start\' field.
i.e. : minutes, hours, days, weeks');
			$table->string('event_color', 20)->nullable();
			$table->boolean('is_public')->nullable()->index();
			$table->integer('created_by')->unsigned()->nullable()->index('calendar_events_created_by_foreign_idx')->comment('created by refers the users table id;
it is the id who was logged in at the creation time of the event.');
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
		Schema::drop('calendar_events');
	}

}
