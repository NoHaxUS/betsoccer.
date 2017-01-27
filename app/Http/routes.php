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

Route::group(['namespace' => 'Services'], function () {
    Route::get('/aposta', ['uses' => 'ApostaService@getJsonJogos', 'as' => 'aposta.getJsonJogos']);
    Route::get('/aposta/ganhosApostas/{codigo_seguranca}', ['uses' => 'ApostaService@ganhosApostas', 'as' => 'aposta.ganhosApostas']);
    Route::get('/aposta/premiosApostas/{codigo_seguranca}', ['uses' => 'ApostaService@premiosApostas', 'as' => 'aposta.premiosApostas']);
    Route::get('/aposta/ultima/{codigo_seguranca}', ['uses' => 'ApostaService@ultima', 'as' => 'aposta.ultima']);
    Route::get('/aposta/consultar/{codigo}', ['uses' => 'ApostaService@consultar', 'as' => 'aposta.consultar']);

    Route::get('/cambista/acerto/{codigo_c}/{codigo_a}', ['uses' => 'ApostaService@acerto', 'as' => 'aposta.acerto']);
    Route::post('/aposta/cambista', ['uses' => 'ApostaService@apostaCambista', 'as' => 'aposta.cambista']);
    Route::post('/aposta/apostar', ['uses' => 'ApostaService@apostar', 'as' => 'aposta.apostar']);
    Route::post('/aposta/apostarSemCodigo', ['uses' => 'ApostaService@apostarSemCodigo', 'as' => 'aposta.apostarSemCodigo']);
    Route::put('/aposta/validar', ['uses' => 'ApostaService@validar', 'as' => 'aposta.validar']);
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

Route::get('/password/reset', ['uses' => 'Auth\PasswordController@getEmail', 'as' => 'senha.recu']);

Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin', 'middleware' => 'check.user.role:admin',], function () {

    Route::get('/editarUser', ['middleware' =>'permission:ativar-user|desativar-user','uses' => 'Auth\AuthController@getAll', 'as' => 'user.edit']);
    Route::get('/editarUser/{id}/ativ', ['middleware' =>'permission:ativar-user','uses' => 'Auth\AuthController@ativar', 'as' => 'user.ativ']);
    Route::get('/editarUser/{id}/desat', ['middleware' =>'permission:desativar-user','uses' => 'Auth\AuthController@desativar', 'as' => 'user.desat']);
    Route::get('/register', ['uses' => 'Auth\AuthController@getRegister', 'as' => 'reg.get']);
    Route::post('/register', ['uses' => 'Auth\AuthController@postRegister', 'as' => 'reg.post']);
    // Registration routes...

//rotas para tabelas times
    Route::get('/time', ['middleware' =>'permission:consultar-time','uses' => 'TimeController@index', 'as' => 'time.index']);
    Route::get('/time/cadastrar', ['middleware' =>'permission:criar-time','uses' => 'TimeController@cadastrar', 'as' => 'time.cadastrar']);
    Route::post('/time/salvar', ['middleware' =>'permission:criar-time','uses' => 'TimeController@salvar', 'as' => 'time.salvar']);
    Route::get('/time/editar/{id}', ['middleware' =>'permission:editar-time','uses' => 'TimeController@editar', 'as' => 'time.editar']);
    Route::put('/time/atualizar/{id}', ['middleware' =>'permission:editar-time','uses' => 'TimeController@atualizar', 'as' => 'time.atualizar']);
    Route::get('/time/deletar/{id}', ['middleware' =>'permission:excluir-time','uses' => 'TimeController@deletar', 'as' => 'time.deletar']);
    Route::get('/time/detalhe/{id}', ['uses' => 'TimeController@detalhe', 'as' => 'time.detalhe']);

    //rotas para tabela campeonatos

    Route::get('/campeonato', ['middleware' =>'permission:consultar-campeonato','uses' => 'CampeonatoController@index', 'as' => 'campeonato.index']);
    Route::get('/campeonato/cadastrar', ['middleware' =>'permission:criar-campeonato','uses' => 'CampeonatoController@cadastrar', 'as' => 'campeonato.cadastrar']);
    Route::post('/campeonato/salvar', ['middleware' =>'permission:criar-campeonato','uses' => 'CampeonatoController@salvar', 'as' => 'campeonato.salvar']);
    Route::get('/campeonato/editar/{id}', ['middleware' =>'permission:editar-campeonato','uses' => 'CampeonatoController@editar', 'as' => 'campeonato.editar']);
    Route::put('/campeonato/atualizar/{id}', ['middleware' =>'permission:editar-campeonato','uses' => 'CampeonatoController@atualizar', 'as' => 'campeonato.atualizar']);
    Route::get('/campeonato/deletar/{id}', ['middleware' =>'permission:excluir-campeonato','uses' => 'CampeonatoController@deletar', 'as' => 'campeonato.deletar']);

    //rotas para tabela horario
    Route::get('/horario', ['uses' => 'HorarioController@index', 'as' => 'horario.index']);
    Route::get('/horario/cadastrar', ['uses' => 'HorarioController@cadastrar', 'as' => 'horario.cadastrar']);
    Route::post('/horario/salvar', ['uses' => 'HorarioController@salvar', 'as' => 'horario.salvar']);
    Route::get('/horario/editar/{id}', ['uses' => 'HorarioController@editar', 'as' => 'horario.editar']);
    Route::put('/horario/atualizar/{id}', ['uses' => 'HorarioController@atualizar', 'as' => 'horario.atualizar']);
    Route::get('/horario/deletar/{id}', ['uses' => 'HorarioController@deletar', 'as' => 'horario.deletar']);

    //rotas para tabela de jogos

    Route::get('/jogo', ['middleware' =>'permission:consultar-jogo','uses' => 'JogoController@index', 'as' => 'jogo.index']);
    Route::get('/jogo/cadastrar', ['middleware' =>'permission:criar-jogo','uses' => 'JogoController@cadastrar', 'as' => 'jogo.cadastrar']);
    Route::post('/jogo/salvar', ['middleware' =>'permission:criar-jogo','uses' => 'JogoController@salvar', 'as' => 'jogo.salvar']);
    Route::get('/jogo/editar/{id}', ['middleware' =>'permission:editar-jogo','uses' => 'JogoController@editar', 'as' => 'jogo.editar']);
    Route::post('/jogo/atualizar/{id}', ['middleware' =>'permission:editar-jogo','uses' => 'JogoController@atualizar', 'as' => 'jogo.atualizar']);
    Route::get('/jogo/ativar-desativar/{id}', ['middleware' =>'permission:ativar-jogo|desativar-jogo','uses' => 'JogoController@atiDes', 'as' => 'jogo.atides']);
    Route::get('/jogo/cadastrar-placar', ['middleware' =>'permission:editar-placar','uses' => 'JogoController@allJogosPlacar', 'as' => 'jogo.allJogosPlacar']);
    Route::post('/jogo/cadastrar-placar/post', ['middleware' =>'permission:editar-placar','uses' => 'JogoController@addPlacar', 'as' => 'jogo.addPlacar']);
    Route::get('/jogo/deletar/{id}', ['middleware' =>'permission:excluir-jogo','uses' => 'JogoController@deletar', 'as' => 'jogo.deletar']);
    Route::get('/jogo/total-palpites/{id}', ['middleware' =>'permission:consultar-palpite','uses' => 'JogoController@totalPalpites', 'as' => 'jogo.totalPalpites']);
    Route::get('/jogo/mais-apostados', ['middleware' =>'permission:consultar-jogo','uses' => 'JogoController@maisApostados', 'as' => 'jogo.maisApostados']);

    Route::group(['prefix' => 'telefone'], function () {
        Route::get('', ['middleware' =>'permission:consultar-telefone','uses' => 'TelefoneController@index', 'as' => 'telefone.index']);
        Route::get('/cadastrar', ['middleware' =>'permission:criar-telefone','uses' => 'TelefoneController@cadastrar', 'as' => 'telefone.cadastrar']);
        Route::post('/salvar', ['middleware' =>'permission:criar-telefone','uses' => 'TelefoneController@salvar', 'as' => 'telefone.salvar']);
        Route::get('/editar/{id}', ['middleware' =>'permission:editar-telefone','uses' => 'TelefoneController@editar', 'as' => 'telefone.editar']);
        Route::put('/atualizar/{id}', ['middleware' =>'permission:editar-telefone','uses' => 'TelefoneController@atualizar', 'as' => 'telefone.atualizar']);
        Route::get('/deletar/{id}', ['middleware' =>'permission:excluir-telefone','uses' => 'TelefoneController@deletar', 'as' => 'telefone.deletar']);
    });
//Rotas de dispositivo
    Route::group(['prefix' => 'dispositivo'], function () {
        Route::get('', ['middleware' =>'permission:consultar-dispositivo','uses' => 'DispositivoController@index', 'as' => 'dispositivo.index']);
        Route::get('/cadastrar', ['middleware' =>'permission:criar-dispositivo','uses' => 'DispositivoController@cadastrar', 'as' => 'dispositivo.cadastrar']);
        Route::post('/salvar', ['middleware' =>'permission:criar-dispositivo','uses' => 'DispositivoController@salvar', 'as' => 'dispositivo.salvar']);
        Route::get('/editar/{id}', ['middleware' =>'permission:editar-dispositivo','uses' => 'DispositivoController@editar', 'as' => 'dispositivo.editar']);
        Route::put('/atualizar/{id}', ['middleware' =>'permission:editar-dispositivo','uses' => 'DispositivoController@atualizar', 'as' => 'dispositivo.atualizar']);
        Route::get('/deletar/{id}', ['middleware' =>'permission:excluir-dispositivo','uses' => 'DispositivoController@deletar', 'as' => 'dispositivo.deletar']);
    });
});

//rotas para a tabela de aposta

Route::group(['middleware' => 'auth'], function () {
    Route::get('/apostaJogo', ['middleware' =>'permission:consultar-aposta','uses' => 'ApostaController@resumoAposta', 'as' => 'apostaJogo.resumoAposta']);
    Route::get('/aposta/serv', ['middleware' =>'permission:consultar-aposta', 'uses' => 'ApostaController@index', 'as' => 'aposta.index']);
    Route::get('/aposta/cadastrar', ['middleware' =>'permission:criar-aposta','uses' => 'ApostaController@cadastrar', 'as' => 'aposta.cadastrar']);
    Route::post('/aposta/salvar', ['middleware' =>'permission:criar-aposta','uses' => 'ApostaController@salvar', 'as' => 'aposta.salvar']);
    Route::post('/aposta/cambista', ['middleware' =>'permission:consultar-aposta','uses' => 'ApostaController@apostaCambista', 'as' => 'aposta.cambista']);
    Route::get('/aposta/editar/{id}', ['middleware' =>'permission:editar-aposta','uses' => 'ApostaController@editar', 'as' => 'aposta.editar']);
    Route::put('/aposta/atualizar/{id}', ['middleware' =>'permission:editar-aposta','uses' => 'ApostaController@atualizar', 'as' => 'aposta.atualizar']);
    Route::get('/aposta/deletar/{id}', ['middleware' =>'permission:excluir-aposta','uses' => 'ApostaController@deletar', 'as' => 'aposta.deletar']);
    Route::get('/aposta/listar', ['middleware' =>'permission:consultar-aposta','uses' => 'ApostaController@listaAposta', 'as' => 'aposta.listaAposta']);

});

//Rotas de permissões
Route::group(['prefix' => 'permission'], function () {
    Route::get('', ['middleware' =>'permission:consultar-permission','uses' => 'PermissionController@index', 'as' => 'permission.index']);
    Route::get('/cadastrar', ['middleware' =>'permission:criar-permission','uses' => 'PermissionController@cadastrar', 'as' => 'permission.cadastrar']);
    Route::post('/salvar', ['middleware' =>'permission:criar-permission','uses' => 'PermissionController@salvar', 'as' => 'permission.salvar']);
    Route::get('/editar/{id}', ['middleware' =>'permission:editar-permission','uses' => 'PermissionController@editar', 'as' => 'permission.editar']);
    Route::put('/atualizar/{id}', ['middleware' =>'permission:editar-permission','uses' => 'PermissionController@atualizar', 'as' => 'permission.atualizar']);
    Route::get('/deletar/{id}', ['middleware' =>'permission:excluir-permission','uses' => 'PermissionController@deletar', 'as' => 'permission.deletar']);
});
//Rotas de Roles
Route::group(['prefix' => 'role'], function () {
    Route::get('', ['middleware' =>'permission:consultar-role','uses' => 'RoleController@index', 'as' => 'role.index']);
    Route::get('/cadastrar', ['middleware' =>'permission:criar-role','uses' => 'RoleController@cadastrar', 'as' => 'role.cadastrar']);
    Route::post('/salvar', ['middleware' =>'permission:criar-role','uses' => 'RoleController@salvar', 'as' => 'role.salvar']);
    Route::get('/editar/{id}', ['middleware' =>'permission:editar-role|relacionar-permission','uses' => 'RoleController@editar', 'as' => 'role.editar']);
    Route::put('/atualizar/{id}', ['middleware' =>'permission:editar-role|relacionar-permission','uses' => 'RoleController@atualizar', 'as' => 'role.atualizar']);
    Route::get('/deletar/{id}', ['middleware' =>'permission:excluir-role','uses' => 'RoleController@deletar', 'as' => 'role.deletar']);
});
//Rotas de usuários
Route::group(['prefix' => 'user'], function () {
Route::get('', ['middleware' =>'permission:consultar-user','uses'=>'UserController@index', 'as'=>'user.index']);
Route::get('/cadastrar', ['middleware' =>'permission:criar-user','uses'=>'UserController@cadastrar', 'as'=>'user.cadastrar']);
Route::post('/salvar', ['middleware' =>'permission:criar-user','uses'=>'UserController@salvar', 'as'=>'user.salvar']);
Route::get('/editar/{id}', ['middleware' =>'permission:editar-user|relacionar-role','uses'=>'UserController@editar', 'as'=>'user.editar']);
Route::put('/atualizar/{id}', ['middleware' =>'permission:editar-user|relacionar-role','uses'=>'UserController@atualizar', 'as'=>'user.atualizar']);
Route::get('/deletar/{id}', ['middleware' =>'permission:excluir-user','uses'=>'UserController@deletar', 'as'=>'user.deletar']);
});
