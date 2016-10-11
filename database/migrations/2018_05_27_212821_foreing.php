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
       Schema::table('aposta_jogo', function (Blueprint $table) {
        $table->foreign('apostas_id')->references('id')->on('apostas')->onDelete('cascade');
        $table->foreign('jogos_id')->references('id')->on('jogos')->onDelete('cascade');            
    });
       Schema::table('jogos', function (Blueprint $table) {
        $table->foreign('campeonatos_id')->references('id')->on('campeonatos');
        $table->foreign('horarios_id')->references('id')->on('horarios')->onUpdate('cascade');
    });
       Schema::table('apostas', function (Blueprint $table) {
        $table->foreign('users_id')->references('id')->on('users');
    });
       Schema::table('jogo_time', function (Blueprint $table) {
        $table->foreign('jogos_id')->references('id')->on('jogos');
        $table->foreign('times_id')->references('id')->on('times');
    });
       
   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     
    }
}
