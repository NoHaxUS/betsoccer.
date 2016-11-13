<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJogoValorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jogo_valor', function (Blueprint $table) {
            $table->integer('fk_jogo_id')->unsigned();
            $table->integer('fk_valor_id')->unsigned();
            $table->boolean('ativado')->default(true);
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
