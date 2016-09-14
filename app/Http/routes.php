<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {

	
    return view('welcome');
});
Route::post('/teste', function() {
    //
    $meuPost = file_get_contents("php://input");
    $json = json_decode( $meuPost );
    
   dd($json->titulo);
});

Route::auth();

//rotas para tabelas times

Route::get('/time', ['uses'=>'TimeController@index', 'as'=>'time.index']);
Route::get('/time/cadastrar', ['uses'=>'TimeController@cadastrar', 'as'=>'time.cadastrar']);
Route::post('/time/salvar', ['uses'=>'TimeController@salvar', 'as'=>'time.salvar']);
Route::get('/time/editar/{id}', ['uses'=>'TimeController@editar', 'as'=>'time.editar']);
Route::put('/time/atualizar/{id}', ['uses'=>'TimeController@atualizar', 'as'=>'time.atualizar']);
Route::get('/time/deletar/{id}', ['uses'=>'TimeController@deletar', 'as'=>'time.deletar']);
Route::get('/time/detalhe/{id}', ['uses'=>'TimeController@detalhe', 'as'=>'time.detalhe']);

//rotas para tabela campeonatos

Route::get('/campeonato', ['uses'=>'CampeonatoController@index', 'as'=>'campeonato.index']);
Route::get('/campeonato/cadastrar', ['uses'=>'CampeonatoController@cadastrar', 'as'=>'campeonato.cadastrar']);
Route::post('/campeonato/salvar', ['uses'=>'CampeonatoController@salvar', 'as'=>'campeonato.salvar']);
Route::get('/campeonato/editar/{id}', ['uses'=>'CampeonatoController@editar', 'as'=>'campeonato.editar']);
Route::put('/campeonato/atualizar/{id}', ['uses'=>'CampeonatoController@atualizar', 'as'=>'campeonato.atualizar']);
Route::get('/campeonato/deletar/{id}', ['uses'=>'CampeonatoController@deletar', 'as'=>'campeonato.deletar']);

//rotas para tabela horario
Route::get('/horario', ['uses'=>'HorarioController@index', 'as'=>'horario.index']);
Route::get('/horario/cadastrar', ['uses'=>'HorarioController@cadastrar', 'as'=>'horario.cadastrar']);
Route::post('/horario/salvar', ['uses'=>'HorarioController@salvar', 'as'=>'horario.salvar']);
Route::get('/horario/editar/{id}', ['uses'=>'HorarioController@editar', 'as'=>'horario.editar']);
Route::put('/horario/atualizar/{id}', ['uses'=>'HorarioController@atualizar', 'as'=>'horario.atualizar']);
Route::get('/horario/deletar/{id}', ['uses'=>'HorarioController@deletar', 'as'=>'horario.deletar']);

//rotas para tabela de jogos

Route::get('/jogo', ['uses'=>'JogoController@index', 'as'=>'jogo.index']);
Route::get('/jogo/cadastrar', ['uses'=>'JogoController@cadastrar', 'as'=>'jogo.cadastrar']);
Route::post('/jogo/salvar', ['uses'=>'JogoController@salvar', 'as'=>'jogo.salvar']);
Route::get('/jogo/editar/{id}', ['uses'=>'JogoController@editar', 'as'=>'jogo.editar']);
Route::put('/jogo/atualizar/{id}', ['uses'=>'JogoController@atualizar', 'as'=>'jogo.atualizar']);
Route::get('/jogo/deletar/{id}', ['uses'=>'JogoController@deletar', 'as'=>'jogo.deletar']);

//rotas para a tabela de aposta
Route::get('/aposta', ['uses'=>'ApostaController@index', 'as'=>'aposta.index']);
Route::get('/aposta/cadastrar', ['uses'=>'ApostaController@cadastrar', 'as'=>'aposta.cadastrar']);
Route::post('/aposta/salvar', ['uses'=>'ApostaController@salvar', 'as'=>'aposta.salvar']);
Route::get('/aposta/editar/{id}', ['uses'=>'ApostaController@editar', 'as'=>'aposta.editar']);
Route::put('/aposta/atualizar/{id}', ['uses'=>'ApostaController@atualizar', 'as'=>'aposta.atualizar']);
Route::get('/aposta/deletar/{id}', ['uses'=>'ApostaController@deletar', 'as'=>'aposta.deletar']);
