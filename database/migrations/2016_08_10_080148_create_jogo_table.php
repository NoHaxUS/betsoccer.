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
        $table->string('codigo')->nullable();
        $table->double('valor_casa', 4 , 2);
        $table->double('valor_fora', 4 , 2);
        $table->double('valor_empate', 4 , 2);
        $table->double('valor_dupla', 4 , 2);
        $table->double('valor_1_2', 4 , 2);
        $table->double('max_gol_2', 4 , 2);
        $table->double('min_gol_3', 4 , 2);
        $table->double('ambas_gol', 4 , 2);
        $table->integer('r_casa')->nullable()->default(null);
        $table->integer('r_fora')->nullable()->default(null);
        $table->integer('campeonatos_id')->unsigned();           
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
        Schema::drop('jogos');
    }
}
