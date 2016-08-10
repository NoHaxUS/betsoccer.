<?php

use Illuminate\Database\Seeder;

class TimesTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Campeonato',10)->create()->each(function($u){
        	$u->time()->save(factory('App\Time')->make());
        });
    }
}
