<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
		    $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('full_name', 64)->index();
			$table->string('first_name', 32)->index();
			$table->string('last_name', 32)->index();
			$table->integer('added_by')->unsigned()->nullable()->index('users_added_by_foreign_idx');
			$table->string('password', 100);
			$table->integer('role_id')->unsigned()->nullable()->index('users_role_id_foreign_idx');
			$table->string('phone', 30)->nullable();
			$table->string('email', 100)->nullable()->index();
			$table->string('remember_token', 100);
			$table->boolean('is_active')->default(1)->index()->comment('1 for active;
0 otherwise');
			$table->timestamp('created_at')->nullable()->useCurrent();
			$table->timestamp('updated_at')->nullable()->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'));
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
