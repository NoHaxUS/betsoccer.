<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJogoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
       Schema::create('jogos', function (Blueprint $table) {
        $table->increments('id');
        $table->datetime('data');
        $table->boolean('ativo')->default(true);
        $table->integer('valor_casa')->unsigned();
        $table->integer('valor_fora')->unsigned();
        $table->integer('valor_empate')->unsigned();
        $table->integer('valor_dupla')->unsigned();
        $table->integer('valor_1_2')->unsigned();
        $table->integer('max_gol_2')->unsigned();
        $table->integer('min_gol_3')->unsigned();
        $table->integer('ambas_gol')->unsigned();


    });
   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jogos');
    }
}
