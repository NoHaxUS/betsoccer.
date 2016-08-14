<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApostaJogoUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('aposta_jogo_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apostas_id')->unsigned();
            $table->integer('jogos_id')->unsigned();
            $table->integer('users_id')->unsigned();
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
        //
    }
}
