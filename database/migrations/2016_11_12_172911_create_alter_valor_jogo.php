<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlterValorJogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jogo_valor', function (Blueprint $table) {
           $table->foreign('fk_jogo_id')->references('id')->on('jogos');
           $table->foreign('fk_valor_id')->references('id')->on('valores');

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jogo_valor');
    }
}
