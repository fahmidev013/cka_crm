<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLeadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('leads', function(Blueprint $table)
		{
			$table->foreign('assignee_id', 'leads_assignee_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('country_id', 'leads_country_id_foreign')->references('id')->on('countries')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('lead_source_id', 'leads_lead_source_id_foreign')->references('id')->on('lead_sources')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('lead_status_id', 'leads_lead_status_id_foreign')->references('id')->on('lead_statuses')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'leads_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('leads', function(Blueprint $table)
		{
			$table->dropForeign('leads_assignee_id_foreign');
			$table->dropForeign('leads_country_id_foreign');
			$table->dropForeign('leads_lead_source_id_foreign');
			$table->dropForeign('leads_lead_status_id_foreign');
			$table->dropForeign('leads_user_id_foreign');
		});
	}

}
