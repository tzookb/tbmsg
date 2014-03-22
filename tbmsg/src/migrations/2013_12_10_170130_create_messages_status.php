<?php

use Illuminate\Database\Migrations\Migration;

class CreateMessagesStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('messages_status', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('msg_id');
            $table->boolean('self');
            $table->integer('status');

            $table->index('msg_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('messages_status');
	}

}