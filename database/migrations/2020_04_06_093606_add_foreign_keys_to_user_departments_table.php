<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_departments', function(Blueprint $table)
		{
			$table->foreign('department_id', 'user_departments_department_id_foreign')->references('id')->on('departments')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'user_departments_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_departments', function(Blueprint $table)
		{
			$table->dropForeign('user_departments_department_id_foreign');
			$table->dropForeign('user_departments_user_id_foreign');
		});
	}

}
