<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJogoXCapeonatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jogo_x_capeonatos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('capeonato_id')->unsigned();
            $table->integer('jogo_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('JogoXCapeonatos', function($table) {
            //
            $table->foreing('jogo_id')->reference('id')->on('jogo');
            $table->foreing('campeonato_id')->reference('id')->on('campeonatos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jogo_x_capeonatos');
    }
}
