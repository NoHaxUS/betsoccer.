<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Foreing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('aposta_jogo_user', function (Blueprint $table) {
            $table->foreign('apostas_id')->references('id')->on('apostas');
            $table->foreign('jogos_id')->references('id')->on('jogos');
            $table->foreign('users_id')->references('id')->on('users');
        });
         Schema::table('jogos', function (Blueprint $table) {
            $table->foreign('campeonatos_id')->references('id')->on('campeonatos');
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
