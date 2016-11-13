<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlterJogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->foreign('valor_casa')->references('id')->on('valores');
            $table->foreign('valor_fora')->references('id')->on('valores');
            $table->foreign('valor_empate')->references('id')->on('valores');
            $table->foreign('valor_dupla')->references('id')->on('valores');
            $table->foreign('valor_1_2')->references('id')->on('valores');
            $table->foreign('max_gol_2')->references('id')->on('valores');
            $table->foreign('min_gol_3')->references('id')->on('valores');
            $table->foreign('ambas_gol')->references('id')->on('valores');
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
