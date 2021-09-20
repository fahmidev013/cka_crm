<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('customer_id')->unsigned()->nullable()->index('tickets_customer_id_foreign)idx')->comment('customer_id refers customers table id');
			$table->string('email')->nullable()->index();
			$table->string('name')->nullable()->index();
			$table->integer('department_id')->unsigned()->index('tickets_department_id_foreign_idx')->comment('department_id refers departments table id');
			$table->integer('priority_id')->unsigned()->index('tickets_priority_id_foreign_idx')->comment('priority_id refers priorities table id');
			$table->unsignedInteger('ticket_status_id')->nullable()->index('tickets_ticket_status_id_foreign_idx')->comment('status_id refers ticket_statuses  table id');
			$table->string('ticket_key', 45);
			$table->string('subject')->index();
			$table->integer('user_id')->unsigned()->nullable()->index('tickets_user_id_foreign_idx')->comment('user_id refers users table id');
			$table->dateTime('date')->nullable()->index();
			$table->integer('project_id')->unsigned()->nullable()->index('tickets_project_id_foreign_idx')->comment('project_id refers projects table id');
			$table->dateTime('last_reply')->nullable()->index();
			$table->integer('assigned_member_id')->unsigned()->nullable()->index('tickets_assigned_member_id_foreign_idx')->comment('assigned_member_id refers users table id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tickets');
	}

}
