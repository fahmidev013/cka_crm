<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEmailTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('email_templates', function(Blueprint $table)
		{
			$table->foreign('language_id', 'email_templates_language_id_foreign')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('email_templates', function(Blueprint $table)
		{
			$table->dropForeign('email_templates_language_id_foreign');
		});
	}

}
