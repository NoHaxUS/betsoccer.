<?php

use Illuminate\Database\Migrations\Migration;

class AlterarUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('codigo_seguranca')->unique()->after('role');
            $table->boolean('ativo')->after('codigo_seguranca')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn(['codigo_seguranca', 'ativo']);
        });
    }
}
