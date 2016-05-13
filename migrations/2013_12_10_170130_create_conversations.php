<?php

use Illuminate\Database\Migrations\Migration;

class CreateConversations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('tbm_conversations', function($table)
        {
            $table->increments('id');

            $table->softDeletes();
            $table->timestamps();


        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('tbm_conversations');
	}

}