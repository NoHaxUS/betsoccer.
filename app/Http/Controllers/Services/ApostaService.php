<?php
namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Hespers\ApostaHelper;
use App\Aposta;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Jenssegers\Optimus\Optimus;

/**
 * Created by PhpStorm.
 * User: Wilder
 * Date: 11/01/2017
 * Time: 11:38
 */
class ApostaService extends Controller
{

    /** M�todo para realiza��o de apostas com c�digo de seguran�a
     * @param Request $request conjunto de dados da aposta
     * @return \Illuminate\Http\JsonResponse dados da aposta ou informa��o de erro
     */
    public function apostar(Request $request)
    {
        if (is_null($request->codigo_seguranca)):                       //Se c�digo de seguran�a � nulo
            return response()->json(
                ['status' => 'codigo_seguranca_nao_informado'], 409);   //retorna json informando erro
        endif;
        //Busca usu�rio pelo c�digo de seguran�a
        $user = User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
        $resposta = ApostaHelper::verificarUsuario($user);               //Verifica restri��o usu�rio
        if (!is_null($resposta)):                                        //Se retornou restri��o
            return response()->json($resposta, $resposta['erro']);       //Retorna json com restri��o encontrada
        endif;
        $jogos_invalidos = ApostaHelper::verificarJogos($request->jogo);//Valida jogos
        if (count($jogos_invalidos) > 0):                               //Verifica se quantidade de jogos inv�lidos � maior que zero
            return response()->json(
                ['jogos_invalidos' => $jogos_invalidos],
                $jogos_invalidos['erro']);                              //retorna json com array com todos os jogos inv�lidos
        endif;
        $aposta = $this->registrarAposta($request->all(), $user);       //Registra aposta

        //$aposta =  $this->realizarAposta($request);                   //Tentar realizar aposta
        $dados_aposta = ApostaHelper::dadosAposta($aposta);             //Busca dados de aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);      //Remove dados desnecess�rio de jogos
        unset($dados_aposta['ganho']);                                  //Remove o indice ganho
        return response()->json([
            'cambista' => $aposta->user->name,                           //Nome do cambista
            'aposta' => $dados_aposta,                                  //Dados da aposta
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),   //Dados dos palpites
            //Valor do poss�vel do pr�mio
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.')]);
    }

    /**M�todo para realiza��o de apostas sem c�digo de seguran�a
     * @param Request $request conjunto de dados da aposta
     * @return \Illuminate\Http\JsonResponse dados da aposta ou informa��o de erro
     */
    public function apostarSemCodigo(Request $request)
    {
        //return $this->realizarAposta($request);                       //Tenta realizar aposta
        $jogos_invalidos = ApostaHelper::verificarJogos($request->jogo);//Valida jogos
        if (count($jogos_invalidos) > 0):                               //Verifica se quantidade de jogos inv�lidos � maior que zero
            return response()->json(
                ['jogos_invalidos' => $jogos_invalidos],
                $jogos_invalidos['erro']);                              //retorna json com array com todos os jogos inv�lidos
        endif;
        /*$palpites_invalidos = $this->verificarPalpites($palpites);    //Verifica se h� palpites inv�lidos
        if (count($palpites_invalidos) > 0):                            //Verifica se quantidade de palpites inv�lidos � maior que zero
            return response()->json(
                ['palpites_invalidos' => $palpites_invalidos]);         //retorna json com array com todos os palpites inv�lidos
                endif;*/
        $aposta = $this->registrarAposta($request->all());              //Registra aposta
        return response()->json(['aposta' => $aposta]);                 //Retorna json com resultado
    }

    /** M�todo que registra aposta no sistema
     * @param array $dados dados para registro
     * @param User $user usu�rio que respons�vel pela aposta
     * @param \Illuminate\Support\Collection $palpites array de palpites
     * @return Aposta aposta feita
     */
    private function registrarAposta($dados, $user = null)
    {
        $aposta = Aposta::create($dados);                            //Cria uma aposta com dados vindos do request
        if (is_null($user)):                                         //Verifica se usu�rio � nulo
            $aposta->ativo = false;                                  //Passa false para atributo ativo
            $optimus = new Optimus(config('constantes.optimus.prime'),
                config('constantes.optimus.inverse'),
                config('constantes.optimus.xor'));
            $optimus->setMode('native');
            $aposta->codigo = $optimus->encode($aposta->id);        //Cria c�digo com base no id
        else:                                                       //Se usu�rio n�o for nulo
            $aposta->ativo = true;                                  //Passa true para atributo ativo
            $aposta->users_id = $user->id;                          //Passa id do usu�rio
            $hashids = new Hashids('betsoccer2', 5);
            $aposta->codigo = $hashids->encode($aposta->id);

        endif;
        $aposta->save();                                            //Salva aposta
        for ($i = 0; $i < count($dados['jogo']); $i++):             //Criar itera��o com base no n�mero de jogos
            $palpite ['palpite'] = $dados['valorPalpite'][$i];      //Passa valor do palpite para array
            $palpite['tpalpite'] = $dados['tpalpite'][$i];          //Passa texto do palpite para array
            $aposta->jogo()->attach($dados['jogo'][$i], $palpite);  //Relaciona aposta com jogo incluindo os dados de palpite
        endfor;
        return $aposta;                                             //Retorna a aposta
    }

    /** M�todo que retorna o valor a ser recebido pelas apostas feitas,
     * exceto as em aberto (com jogos n�o conclu�dos)
     * @param $codigo_seguranca string c�digo que identifica o usu�rio
     * @return \Illuminate\Http\JsonResponse json com resultado da opera��o
     */
    public function ganhosApostas($codigo_seguranca)
    {
        $user = User::buscarPorCodigoSeguranca($codigo_seguranca)->first();   //Busca o usu�rio pelo c�digo de seguran�a
        $resposta = ApostaHelper::verificarUsuario($user);                    //Verifica restri��o usu�rio
        if (!is_null($resposta)):                                             //Se retornou restri��o
            return response()->json($resposta, $resposta['erro']);            //Retorna json com restri��o encontrada
        endif;
        $apostas = Aposta::recentes($user);                                   //Busca apostas recentes do usu�rio
        $dados['com_abertas'] = ApostaHelper::dadosGanhos($user, $apostas);    //Formata dados de todas as apostas
        //Formata dados de apostas sem as abertas
        $dados['sem_abertas'] = ApostaHelper::dadosGanhos($user, ApostaHelper::removerAbertas($apostas));
        return response()->json($dados);                                       //Retorna json com dados
    }

    /** M�todo que verifica a rela��o de premia��es das apostas
     * @param $codigo_seguranca string codigo de seguran�a do cambista
     * @return \Illuminate\Http\JsonResponse informa��es relacionadas a apostas e premia��o
     */
    public function premiosApostas($codigo_seguranca)
    {
        //Busca o usu�rio pelo c�digo de seguran�a
        $user = User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        $resposta = ApostaHelper::verificarUsuario($user);              //Verifica restri��o usu�rio
        if (!is_null($resposta)):                                       //Se retornou restri��o
            return response()->json($resposta, $resposta['erro']);      //Retorna json com restri��o encontrada
        endif;
        /*Busca as apostas recentes do usu�rio feitas pelo usu�rio, remove as que est�o em aberto
        (jogos n�o conclu�dos e formata os dados de pr�mios*/
        $resposta = ApostaHelper::dadosPremios($user, Aposta::recentes($user));
        return response()->json($resposta);                             //Retorn json com respostas
    }

    /** M�todo que busca e retorna �ltima aposta do cambista
     * @param $codigo_seguranca string com c�digo de seguran�a do cambista
     * @return \Illuminate\Http\JsonResponse dados da �ltima aposta feita pelo cambista
     */
    public function ultima($codigo_seguranca)
    {
        $user = User::buscarPorCodigoSeguranca($codigo_seguranca)->first(); //Busca o usu�rio pelo c�digo de seguran�a
        $resposta = ApostaHelper::verificarUsuario($user);                  //Verifica restri��o usu�rio
        if (!is_null($resposta)):                                           //Se retornou restri��o
            return response()->json($resposta, $resposta['erro']);          //Retorna json com restri��o encontrada
        endif;
        $aposta = Aposta::recentes($user)->last();                         //Busca �ltima aposta feita pelo usu�rio
        if (is_null($aposta)):                                              //Se aposta for nula
            return response()->json(['aposta' => 'inexistente'], 403);      //Retorna json informando
        endif;
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                 //Busca dados de aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);          //Remove dados desnecess�rio de jogos
        unset($dados_aposta['ganho']);                                      //Remove o indice ganho
        //Retorna json com dados da �ltima aposta feita pelo usu�rio
        return response()->json([
            'cambista' => $user->name,                                  //Nome do cambista
            'aposta' => $dados_aposta,                                  //Dados da aposta
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),          //Dados dos palpites
            //Valor do poss�vel do pr�mio
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.')]);
    }

    /**M�todo que realiza consulta de aposta
     * @param $codigo string codigo da aposta
     * @return \Illuminate\Http\JsonResponse dados da aposta
     */
    public function consultar($codigo)
    {
        $aposta = Aposta::buscarPorAtributo('codigo', $codigo)->first();        //Busca aposta pelo c�digo
        if (count($aposta) == 0):                                               //Se n�o tiver retornado nenhuma aposta
            return response()->json(['status' => 'codigo_nao_encontrado'], 403);//Retorna json com informa��o de que n�o foi encontrado
        endif;
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                            //Cria array com dados da aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);                     //Remove dados n�o utilizados de jogos
        unset($dados_aposta['ganho']);                                          //Apaga indece do array
        $cambista = is_null($aposta->user) ? null : $aposta->user->name;        //Pega nome do cambista se n�o for nulo
        return response()->json([                                               //Retorna json
            'cambista' => $cambista,                                            //Cambista
            'aposta' => $dados_aposta,                                          //Dados da aposta
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),                  //Dados dos palpites
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.'),    //Pr�mio poss�vel
            'vencedora' => ApostaHelper::apostasWins($aposta)]);                         //Informa��o se aposta vencedora
    }

    /**M�todo que realiza valida��o de aposta
     * @param Request $request dados para valida��o
     * @return \Illuminate\Http\JsonResponse dados da aposta
     */
    public function validar(Request $request)
    {
        //Busca usu�rio pelo c�digo de seguran�a
        $user = User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
        $resposta = ApostaHelper::verificarUsuario($user);          //Verifica usu�rio
        if (!is_null($resposta)):                                   //Se houve erro
            return response()->json($resposta, $resposta['erro']);  //Retorna erro
        endif;
        //Busca aposta pelo c�digo
        $aposta = Aposta::buscarPorAtributo('codigo', $request->codigo_aposta)->first();
        if (is_null($aposta)):                                      //Se aposta for nula (n�o existe)
            //Retorna json informando erro
            return response()->json(['status' => 'codigo_nao_encontrado'], 403);
        endif;
        if ($aposta->ativo):                                        //Se aposta j� estiver ativa
            //Retorna json informando erro
            return response()->json(['status' => 'aposta_ja_ativa'], 406);
        endif;
        $aposta->ativo = true;                                      //Altera atributo ativo para verdadeiro
        $aposta->users_id = $user->id;                              //Passa id do cambista
        $aposta->save();                                            //Salva altera��o
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                //Formata dados da aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);         //Remove dados dos jogos
        unset($dados_aposta['ganho']);                              //Remove dados referentes a ganhos
        return response()->json([
            'cambista' => $user->name,
            'aposta' => $dados_aposta,
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.')]);
    }


    /** M�todo que muda o atributo ultimo_pagamento do usuario para o momento atual
     * @param $codigo_c codigo do Cambista , $codigo_a codigo de um Admin
     * @return mixed array confirma��o da altera��o
     */

    public function acerto($codigo_c, $codigo_a)
    {
        $cambista = \App\User::buscarPorCodigoSeguranca($codigo_c)->first();
        $resposta = ApostaHelper::verificarUsuario($cambista);                                       //Verifica restri��o usu�rio
        if (!is_null($resposta)) {                                                             //Se retornou restri��o
            return response()->json($resposta, $resposta['erro']);                            //Retorna json com restri��o encontrada
        }
        $adm = \App\User::buscarPorCodigoSeguranca($codigo_a)->first();
        $resposta = ApostaHelper::verificarUsuario($adm);                                            //Verifica restri��o usu�rio
        if (!is_null($resposta)) {                                                             //Se retornou restri��o
            return response()->json($resposta, $resposta['erro']);                            //Retorna json com restri��o encontrada
        }
        if ($adm->role != "admin") {                                                           //Se retornou restri��o
            return response()->json(['status' => 'Credenciais Insuficientes', 'erro' => 501], 501);          //Retorna json com restri��o encontrada
        }
        $apostas = Aposta::recentes($cambista);
        $apostas = ApostaHelper::removerAbertas($apostas);
        foreach ($apostas as $aposta) {
            $aposta->pago = true;
            $aposta->save();
        }
        //
        if (!$apostas->isEmpty()):                          //Se rela��o de apostas n�o estiver vazio
            $acerto = new \App\Acerto();                    //Instancia acerto
            $dados=ApostaHelper::calcularGanho($apostas);   //Busca dados de ganhos de apostas
            $acerto->cambista_id = $cambista->id;           //Passa id do cambista
            $acerto->gerente_id = $adm->id;                 //Passa id do gerente
            $acerto->qtd_apostas=$dados['qtd_apostas'];     //Passa quantidade de apostas
            $acerto->qtd_jogos=$dados['qtd_jogos'];         //Passa quantidade de jogos
            //Passa valor da comiss�o simples
            $acerto->comissao_simples = str_replace(',','.',str_replace('.','',$dados['comissao_simples']));
            //Passa valor da comiss�o mediana
            $acerto->comissao_mediana = str_replace(',','.',str_replace('.','',$dados['comissao_mediana']));
            //Passa valor da comiss�o m�xima
            $acerto->comissao_maxima = str_replace(',','.',str_replace('.','',$dados['comissao_maxima']));
            //Passa valor total apostado
            $acerto->total_apostado = str_replace(',','.',str_replace('.','',$dados['total_apostado']));
            //Passa valor total de premia��o
            $acerto->total_premiacao = str_replace(',','.',str_replace('.','',$dados['total_premiacao']));
            //Passa valor liquido
            $acerto->liquido = str_replace(',','.',str_replace('.','',$dados['liquido']));
            $acerto->save();                                //Salva acerto
        endif;
        //
        $cambista->ultimo_pagamento = Carbon::now();
        $cambista->save();
    }

    public function getJsonJogos()
    {
        $jogos = \App\Jogo::disponiveis();
        return response()->json(array("jogos" => $jogos));
    }
}