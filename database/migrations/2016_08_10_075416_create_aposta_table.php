<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApostaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apostas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->nullable();
            $table->double('valor_aposta');
            $table->string('nome_apostador');
            $table->boolean('pago')->default(false);            
            $table->boolean('ativo')->default(true);
            $table->integer('users_id')->unsigned();            
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
        Schema::drop('apostas');
    }
}
