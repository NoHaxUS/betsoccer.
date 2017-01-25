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

Route::group(['namespace'=>'Services'], function(){
	Route::get('/aposta', ['uses'=>'ApostaService@getJsonJogos', 'as'=>'aposta.getJsonJogos']);
	Route::get('/aposta/ganhosApostas/{codigo_seguranca}', ['uses'=>'ApostaService@ganhosApostas', 'as'=>'aposta.ganhosApostas']);
	Route::get('/aposta/premiosApostas/{codigo_seguranca}', ['uses'=>'ApostaService@premiosApostas', 'as'=>'aposta.premiosApostas']);
	Route::get('/aposta/ultima/{codigo_seguranca}', ['uses'=>'ApostaService@ultima', 'as'=>'aposta.ultima']);
	Route::get('/aposta/consultar/{codigo}', ['uses'=>'ApostaService@consultar', 'as'=>'aposta.consultar']);

	Route::get('/cambista/acerto/{codigo_c}/{codigo_a}', ['uses'=>'ApostaService@acerto', 'as'=>'aposta.acerto']);
	Route::post('/aposta/cambista', ['uses'=>'ApostaService@apostaCambista', 'as'=>'aposta.cambista']);
	Route::post('/aposta/apostar', ['uses'=>'ApostaService@apostar', 'as'=>'aposta.apostar']);
	Route::post('/aposta/apostarSemCodigo', ['uses'=>'ApostaService@apostarSemCodigo', 'as'=>'aposta.apostarSemCodigo']);
	Route::put('/aposta/validar', ['uses'=>'ApostaService@validar', 'as'=>'aposta.validar']);
});
//rotas não auteticadas (TEMPORARIAMENTE)
//Route::get('/aposta', ['uses'=>'ApostaController@getJsonJogos', 'as'=>'aposta.getJsonJogos']);
//Route::post('/aposta/apostar', ['uses'=>'ApostaController@apostar', 'as'=>'aposta.apostar']);
//Route::get('/aposta/ganhosApostas/{codigo_seguranca}', ['uses'=>'Services\ApostaService@ganhosApostas', 'as'=>'aposta.ganhosApostas']);
//Route::get('/aposta/premiosApostas/{codigo_seguranca}', ['uses'=>'ApostaController@premiosApostas', 'as'=>'aposta.premiosApostas']);
//Route::get('/aposta/ultima/{codigo_seguranca}', ['uses'=>'ApostaController@ultima', 'as'=>'aposta.ultima']);
//Route::get('/cambista/acerto/{codigo_c}/{codigo_a}', ['uses'=>'ApostaController@acerto', 'as'=>'aposta.acerto']);
//Route::post('/aposta/apostarSemCodigo', ['uses'=>'ApostaController@apostarSemCodigo', 'as'=>'aposta.apostarSemCodigo']);
//Route::get('/aposta/consultar/{codigo}', ['uses'=>'ApostaController@consultar', 'as'=>'aposta.consultar']);
//Route::put('/aposta/validar', ['uses'=>'ApostaController@validar', 'as'=>'aposta.validar']);

//rotas não auteticadas (TEMPORARIAMENTE)

Route::get('/', function () {
	return view('welcome');
});

Route::get('/password/reset', ['uses'=>'Auth\PasswordController@getEmail', 'as'=>'senha.recu']);

Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin','middleware' => 'check.user.role:admin',], function() {

	Route::get('/editarUser', ['uses' => 'Auth\AuthController@getAll', 'as'=>'user.editar']);
	Route::get('/editarUser/{id}/ativ',['uses' => 'Auth\AuthController@ativar', 'as'=>'user.ativ']);
	Route::get('/editarUser/{id}/desat',['uses' => 'Auth\AuthController@desativar', 'as'=>'user.desat']);
	Route::get('/register', ['uses'=>'Auth\AuthController@getRegister', 'as'=>'reg.get']);
	Route::post('/register', ['uses'=>'Auth\AuthController@postRegister', 'as'=>'reg.post']);
	// Registration routes...

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
	Route::post('/jogo/atualizar/{id}', ['uses'=>'JogoController@atualizar', 'as'=>'jogo.atualizar']);
	Route::get('/jogo/ativar-desativar/{id}', ['uses'=>'JogoController@atiDes', 'as'=>'jogo.atides']);
	Route::get('/jogo/cadastrar-placar', ['uses'=>'JogoController@allJogosPlacar', 'as'=>'jogo.allJogosPlacar']);
	Route::post('/jogo/cadastrar-placar/post', ['uses'=>'JogoController@addPlacar', 'as'=>'jogo.addPlacar']);
	Route::get('/jogo/deletar/{id}', ['uses'=>'JogoController@deletar', 'as'=>'jogo.deletar']);
	Route::get('/jogo/total-palpites/{id}', ['uses'=>'JogoController@totalPalpites', 'as'=>'jogo.totalPalpites']);
	Route::get('/jogo/mais-apostados', ['uses'=>'JogoController@maisApostados', 'as'=>'jogo.maisApostados']);
});

	//rotas para a tabela de aposta

Route::group(['middleware' => 'auth'], function() {
	Route::get('/apostaJogo', ['uses'=>'ApostaController@resumoAposta', 'as'=>'apostaJogo.resumoAposta']);
	Route::get('/aposta/serv', ['middleware' => 'auth','uses'=>'ApostaController@index', 'as'=>'aposta.index']);
	Route::get('/aposta/cadastrar', ['uses'=>'ApostaController@cadastrar', 'as'=>'aposta.cadastrar']);
	Route::post('/aposta/salvar', ['uses'=>'ApostaController@salvar', 'as'=>'aposta.salvar']);
	Route::post('/aposta/cambista', ['uses'=>'ApostaController@apostaCambista', 'as'=>'aposta.cambista']);
	Route::get('/aposta/editar/{id}', ['uses'=>'ApostaController@editar', 'as'=>'aposta.editar']);
	Route::put('/aposta/atualizar/{id}', ['uses'=>'ApostaController@atualizar', 'as'=>'aposta.atualizar']);
	Route::get('/aposta/deletar/{id}', ['uses'=>'ApostaController@deletar', 'as'=>'aposta.deletar']);

});
Route::get('/aposta/listar', ['uses'=>'ApostaController@listaAposta', 'as'=>'aposta.listaAposta']);
//Route::get('/aposta/listar/all', ['uses'=>'ApostaController@listaAposta', 'as'=>'aposta.listar']);
//Rotas de telefone
Route::group(['prefix' => 'telefone'], function() {
	Route::get('', ['uses'=>'TelefoneController@index', 'as'=>'telefone.index']);
	Route::get('/cadastrar', ['uses'=>'TelefoneController@cadastrar', 'as'=>'telefone.cadastrar']);
	Route::post('/salvar', ['uses'=>'TelefoneController@salvar', 'as'=>'telefone.salvar']);
	Route::get('/editar/{id}', ['uses'=>'TelefoneController@editar', 'as'=>'telefone.editar']);
	Route::put('/atualizar/{id}', ['uses'=>'TelefoneController@atualizar', 'as'=>'telefone.atualizar']);
	Route::get('/deletar/{id}', ['uses'=>'TelefoneController@deletar', 'as'=>'telefone.deletar']);
});
//Rotas de dispositivo
Route::group(['prefix' => 'dispositivo'], function() {
	Route::get('', ['uses'=>'DispositivoController@index', 'as'=>'dispositivo.index']);
	Route::get('/cadastrar', ['uses'=>'DispositivoController@cadastrar', 'as'=>'dispositivo.cadastrar']);
	Route::post('/salvar', ['uses'=>'DispositivoController@salvar', 'as'=>'dispositivo.salvar']);
	Route::get('/editar/{id}', ['uses'=>'DispositivoController@editar', 'as'=>'dispositivo.editar']);
	Route::put('/atualizar/{id}', ['uses'=>'DispositivoController@atualizar', 'as'=>'dispositivo.atualizar']);
	Route::get('/deletar/{id}', ['uses'=>'DispositivoController@deletar', 'as'=>'dispositivo.deletar']);
});
