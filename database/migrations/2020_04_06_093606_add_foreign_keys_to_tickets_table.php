<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tickets', function(Blueprint $table)
		{
            $table->foreign('customer_id', 'tickets_customer_id_foreign')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('department_id', 'tickets_department_id_foreign')->references('id')->on('departments')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('priority_id', 'tickets_priority_id_foreign')->references('id')->on('priorities')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('ticket_status_id', 'tickets_ticket_status_id_foreign')->references('id')->on('ticket_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('assigned_member_id', 'tickets_assigned_member_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('project_id', 'tickets_project_id_foreign')->references('id')->on('projects')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('user_id', 'tickets_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tickets', function(Blueprint $table)
		{
			$table->dropForeign('tickets_assigned_member_id_foreign');
			$table->dropForeign('tickets_customer_id_foreign');
			$table->dropForeign('tickets_department_id_foreign');
			$table->dropForeign('tickets_priority_id_foreign');
			$table->dropForeign('tickets_project_id_foreign');
			$table->dropForeign('tickets_ticket_status_id_foreign');
			$table->dropForeign('tickets_user_id_foreign');
		});
	}

}
