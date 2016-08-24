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
            $table->double('valor_casa', 3 , 2);
            $table->double('valor_fora', 3 , 2);
            $table->double('valor_empate', 3 , 2);
            $table->double('valor_dupla', 3 , 2);
            $table->double('valor_1_2', 3 , 2);
            $table->double('max_gol_2', 3 , 2);
            $table->double('min_gol_3', 3 , 2);
            $table->double('ambas_gol', 3 , 2);

            $table->integer('campeonatos_id')->unsigned();           
            $table->integer('horarios_id')->unsigned();
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
        Schema::drop('jogos');
    }
}
