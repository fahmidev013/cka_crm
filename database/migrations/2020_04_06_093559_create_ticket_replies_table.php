<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketRepliesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_replies', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->increments('id');
			$table->integer('ticket_id')->unsigned()->index('ticket_replies_ticket_id_foreign_idx')->comment('ticket_id refers tickets table id');
			$table->integer('user_id')->unsigned()->nullable()->index('ticket_replies_user_id_foreign_idx')->comment('user_id refers users table id in other word (admin id who replied the current one)');
			$table->integer('customer_id')->unsigned()->nullable()->index('ticket_replies_customer_id_foreign_idx')->comment('customer_id refers customers table id');
			$table->dateTime('date')->nullable()->index();
			$table->text('message', 16777215)->nullable();
			$table->boolean('has_attachment')->default(0)->index()->comment('0 for no attachment;
1 means this reply has attachment');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket_replies');
	}

}
