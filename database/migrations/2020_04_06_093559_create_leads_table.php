<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->integer('id', true);
			$table->string('first_name', 50)->index();
			$table->string('last_name', 50)->index();
			$table->string('email', 50)->nullable()->index();
			$table->string('street', 50)->nullable();
			$table->string('city', 50)->nullable();
			$table->string('state', 50)->nullable()->index();
			$table->string('zip_code', 50)->nullable();
			$table->integer('country_id')->unsigned()->nullable()->index('leads_country_id_foreign_idx');
			$table->string('phone', 30)->nullable()->index();
			$table->string('website', 50)->nullable();
			$table->string('company', 50)->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('lead_status_id')->index('leads_lead_status_id_foreign_idx');
			$table->integer('lead_source_id')->index('leads_lead_source_id_foreign_idx');
			$table->integer('assignee_id')->unsigned()->nullable()->index('leads_assignee_id_foreign_idx');
			$table->integer('user_id')->unsigned()->nullable()->index('leads_user_id_foreign_idx');
			$table->dateTime('last_contact')->nullable()->index();
			$table->dateTime('last_status_change')->nullable();
			$table->integer('last_lead_status')->nullable();
			$table->date('date_converted')->nullable();
			$table->date('date_assigned')->nullable();
			$table->boolean('is_lost')->default(0)->index();
			$table->boolean('is_public')->default(0)->index();
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
		Schema::drop('leads');
	}

}
