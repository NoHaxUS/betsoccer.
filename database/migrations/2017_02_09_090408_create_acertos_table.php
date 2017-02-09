<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcertosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acertos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cambista_id')->unsigned();
            $table->integer('gerente_id')->unsigned();
            $table->integer('qtd_apostas')->unsigned();
            $table->integer('qtd_jogos')->unsigned();
            $table->double('comissao_simples',15,2);
            $table->double('comissao_mediana',15,2);
            $table->double('comissao_maxima',15,2);
            $table->double('total_apostado',15,2);
            $table->double('total_premiacao',15,2);
            $table->double('liquido',15,2);
            $table->timestamps();
            $table->foreign('cambista_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gerente_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acertos');
    }
}
