<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApostaJogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('aposta_jogo', function (Blueprint $table) {           
            $table->integer('apostas_id')->unsigned();
            $table->integer('jogos_id')->unsigned();
            $table->double('palpite', 4 , 2);
            $table->string('tpalpite');
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
        Schema::drop('aposta_jogo');
    }
}
