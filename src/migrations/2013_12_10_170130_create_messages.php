<?php

use Illuminate\Database\Migrations\Migration;

class CreateMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('messages', function($table)
        {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('conv_id');
            $table->text('content');
            $table->timestamps();

            $table->index('sender_id');
            $table->index('conv_id');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('messages');
	}

}