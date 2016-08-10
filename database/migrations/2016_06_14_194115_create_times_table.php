<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao_time');
            $table->integer('campeonato_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('times', function (Blueprint $table) {
             $table->foreign('campeonato_id')->references('id')->on('campeonatos');
             });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('times');
    }
}
