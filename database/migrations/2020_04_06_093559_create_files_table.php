<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->string('object_type', 32)->nullable()->index()->comment('The type of file.
e.g.: task, ticket, customer, user etc. ');
			$table->integer('object_id')->nullable()->index()->comment('object id refers
e.g: user_id, customer_id, ticket_id etc.');
			$table->integer('uploaded_by')->nullable();
			$table->string('file_name', 255)->nullable();
			$table->string('original_file_name')->nullable();
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
		Schema::drop('files');
	}

}
