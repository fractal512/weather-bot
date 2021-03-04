<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViberBotUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viber_bot_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('viber_user_id');
            $table->string('name');
            $table->string('language');
            $table->string('country');
            $table->string('city');
            $table->integer('interval');
            $table->timestamps();
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
        Schema::dropIfExists('viber_bot_users');
    }
}
