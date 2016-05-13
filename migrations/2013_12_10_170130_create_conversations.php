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
        Schema::create('conversations', function($table)
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
        Schema::drop('conversations');
	}

}