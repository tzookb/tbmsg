<?php

use Illuminate\Database\Migrations\Migration;

class CreateConvUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conv_users', function($table)
        {
            $table->integer('conv_id')->nullable();
            $table->integer('user_id')->nullable();


            $table->primary(array('conv_id', 'user_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('conv_users');
    }

}