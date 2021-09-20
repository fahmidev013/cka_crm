<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRoleUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('role_users', function(Blueprint $table)
		{
			$table->foreign('user_id', 'role_users_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('role_id', 'role_users_role_id_foreign')->references('id')->on('roles')->onUpdate('CASCADE')->onDelete('CASCADE');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('role_users', function(Blueprint $table)
		{
			$table->dropForeign('role_users_user_id_foreign');
			$table->dropForeign('role_users_role_id_foreign');
		});
	}

}
