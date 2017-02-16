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

    /** Método para realização de apostas com código de segurança
     * @param Request $request conjunto de dados da aposta
     * @return \Illuminate\Http\JsonResponse dados da aposta ou informação de erro
     */
    public function apostar(Request $request)
    {
        if (is_null($request->codigo_seguranca)):                                   //Se código de segurança é nulo
            return response()->json(
                ['status' => 'codigo_seguranca_nao_informado'], 409);               //retorna json informando erro
        endif;
        $user = User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();//Busca usuário pelo código de segurança
        $resposta = ApostaHelper::verificarUsuario($user);                          //Verifica restrição usuário
        if (!is_null($resposta)):                                                   //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                  //Retorna json com restrição encontrada
        endif;
        if(!$user->can('criar-aposta')):                                            //Se usuário não tem permissão de realizar aposta
            return response()->json(['status' => 'Credenciais Insuficientes'], 501);//Retorna json informando
        endif;
        $jogos_invalidos = ApostaHelper::verificarJogos($request->jogo);            //Verifica jogos
        if (count($jogos_invalidos) > 0):                                           //Verifica se quantidade de jogos inválidos é maior que zero
            return response()->json(
                ['jogos_invalidos' => $jogos_invalidos],
                $jogos_invalidos['erro']);                                          //retorna json com array com todos os jogos inválidos
        endif;
        $aposta = $this->registrarAposta($request->all(), $user);                   //Registra aposta
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                         //Busca dados de aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);                  //Remove dados desnecessário de jogos
        unset($dados_aposta['ganho']);                                              //Remove o indice ganho
        return response()->json([
            'cambista' => $aposta->user->name,                                      //Nome do cambista
            'aposta' => $dados_aposta,                                              //Dados da aposta
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),               //Dados dos palpites
            //Valor do possível do prêmio
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.')]);
    }

    /**Método para realização de apostas sem código de segurança
     * @param Request $request conjunto de dados da aposta
     * @return \Illuminate\Http\JsonResponse dados da aposta ou informação de erro
     */
    public function apostarSemCodigo(Request $request)
    {
        //return $this->realizarAposta($request);                       //Tenta realizar aposta
        $jogos_invalidos = ApostaHelper::verificarJogos($request->jogo);//Valida jogos
        if (count($jogos_invalidos) > 0):                               //Verifica se quantidade de jogos inválidos é maior que zero
            return response()->json(
                ['jogos_invalidos' => $jogos_invalidos],
                $jogos_invalidos['erro']);                              //retorna json com array com todos os jogos inválidos
        endif;
        /*$palpites_invalidos = $this->verificarPalpites($palpites);    //Verifica se há palpites inválidos
        if (count($palpites_invalidos) > 0):                            //Verifica se quantidade de palpites inválidos é maior que zero
            return response()->json(
                ['palpites_invalidos' => $palpites_invalidos]);         //retorna json com array com todos os palpites inválidos
                endif;*/
        $aposta = $this->registrarAposta($request->all());              //Registra aposta
        return response()->json(['aposta' => $aposta]);                 //Retorna json com resultado
    }

    /** Método que registra aposta no sistema
     * @param array $dados dados para registro
     * @param User $user usuário que responsável pela aposta
     * @param \Illuminate\Support\Collection $palpites array de palpites
     * @return Aposta aposta feita
     */
    private function registrarAposta($dados, $user = null)
    {
        $aposta = Aposta::create($dados);                            //Cria uma aposta com dados vindos do request
        if (is_null($user)):                                         //Verifica se usuário é nulo
            $aposta->ativo = false;                                  //Passa false para atributo ativo
            $optimus = new Optimus(config('constantes.optimus.prime'),
                config('constantes.optimus.inverse'),
                config('constantes.optimus.xor'));
            $optimus->setMode('native');
            $aposta->codigo = $optimus->encode($aposta->id);        //Cria código com base no id
        else:                                                       //Se usuário não for nulo
            $aposta->ativo = true;                                  //Passa true para atributo ativo
            $aposta->users_id = $user->id;                          //Passa id do usuário
            $hashids = new Hashids('betsoccer2', 5);
            $aposta->codigo = $hashids->encode($aposta->id);

        endif;
        $aposta->save();                                            //Salva aposta
        for ($i = 0; $i < count($dados['jogo']); $i++):             //Criar iteração com base no número de jogos
            $palpite ['palpite'] = $dados['valorPalpite'][$i];      //Passa valor do palpite para array
            $palpite['tpalpite'] = $dados['tpalpite'][$i];          //Passa texto do palpite para array
            $aposta->jogo()->attach($dados['jogo'][$i], $palpite);  //Relaciona aposta com jogo incluindo os dados de palpite
        endfor;
        return $aposta;                                             //Retorna a aposta
    }

    /** Método que retorna o valor a ser recebido pelas apostas feitas,
     * exceto as em aberto (com jogos não concluídos)
     * @param $codigo_seguranca string código que identifica o usuário
     * @return \Illuminate\Http\JsonResponse json com resultado da operação
     */
    public function ganhosApostas($codigo_seguranca)
    {
        $user = User::buscarPorCodigoSeguranca($codigo_seguranca)->first();   //Busca o usuário pelo código de segurança
        $resposta = ApostaHelper::verificarUsuario($user);                    //Verifica restrição usuário
        if (!is_null($resposta)):                                             //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);            //Retorna json com restrição encontrada
        endif;
        if(!$user->can('consultar-aposta')):                                        //Se usuário não tem permissão para consultar aposta
            return response()->json(['status' => 'Credenciais Insuficientes'], 501);//Retorna json informando
        endif;
        $apostas = Aposta::recentes($user);                                   //Busca apostas recentes do usuário
        $dados['com_abertas'] = ApostaHelper::dadosGanhos($user, $apostas);    //Formata dados de todas as apostas
        //Formata dados de apostas sem as abertas
        $dados['sem_abertas'] = ApostaHelper::dadosGanhos($user, ApostaHelper::removerAbertas($apostas));
        return response()->json($dados);                                       //Retorna json com dados
    }

    /** Método que verifica a relação de premiações das apostas
     * @param $codigo_seguranca string codigo de segurança do cambista
     * @return \Illuminate\Http\JsonResponse informações relacionadas a apostas e premiação
     */
    public function premiosApostas($codigo_seguranca)
    {
        $user = User::buscarPorCodigoSeguranca($codigo_seguranca)->first();         //Busca o usuário pelo código de segurança
        $resposta = ApostaHelper::verificarUsuario($user);                          //Verifica restrição usuário
        if (!is_null($resposta)):                                                   //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                  //Retorna json com restrição encontrada
        endif;
        if(!$user->can('consultar-aposta')):                                        //Se usuário não tem permissão para consultar aposta
            return response()->json(['status' => 'Credenciais Insuficientes'], 501);//Retorna json informando
        endif;
        /*Busca as apostas recentes do usuário feitas pelo usuário, remove as que estão em aberto
        (jogos não concluídos e formata os dados de prêmios*/
        $resposta = ApostaHelper::dadosPremios($user, Aposta::recentes($user));
        return response()->json($resposta);                                         //Retorn json com respostas
    }

    /** Método que busca e retorna última aposta do cambista
     * @param $codigo_seguranca string com código de segurança do cambista
     * @return \Illuminate\Http\JsonResponse dados da última aposta feita pelo cambista
     */
    public function ultima($codigo_seguranca)
    {
        $user = User::buscarPorCodigoSeguranca($codigo_seguranca)->first();         //Busca o usuário pelo código de segurança
        $resposta = ApostaHelper::verificarUsuario($user);                          //Verifica restrição usuário
        if (!is_null($resposta)):                                                   //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                  //Retorna json com restrição encontrada
        endif;
        if(!$user->can('consultar-aposta')):                                        //Se usuário não tem permissão para consultar aposta
            return response()->json(['status' => 'Credenciais Insuficientes'], 501);//Retorna json informando
        endif;
        $aposta = Aposta::recentes($user)->last();                                  //Busca última aposta feita pelo usuário
        if (is_null($aposta)):                                                      //Se aposta for nula
            return response()->json(['aposta' => 'inexistente'], 403);              //Retorna json informando
        endif;
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                         //Busca dados de aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);                  //Remove dados desnecessário de jogos
        unset($dados_aposta['ganho']);                                              //Remove o indice ganho
        //Retorna json com dados da última aposta feita pelo usuário
        return response()->json([
            'cambista' => $user->name,                                              //Nome do cambista
            'aposta' => $dados_aposta,                                              //Dados da aposta
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),               //Dados dos palpites
            //Valor do possível do prêmio
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.')]);
    }

    /**Método que realiza consulta de aposta
     * @param $codigo string codigo da aposta
     * @return \Illuminate\Http\JsonResponse dados da aposta
     */
    public function consultar($codigo)
    {
        $aposta = Aposta::buscarPorAtributo('codigo', $codigo)->first();        //Busca aposta pelo código
        if (count($aposta) == 0):                                               //Se não tiver retornado nenhuma aposta
            return response()->json(['status' => 'codigo_nao_encontrado'], 403);//Retorna json com informação de que não foi encontrado
        endif;
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                     //Cria array com dados da aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);              //Remove dados não utilizados de jogos
        unset($dados_aposta['ganho']);                                          //Apaga indece do array
        $cambista = is_null($aposta->user) ? null : $aposta->user->name;        //Pega nome do cambista se não for nulo
        return response()->json([                                               //Retorna json
            'cambista' => $cambista,                                            //Cambista
            'aposta' => $dados_aposta,                                          //Dados da aposta
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),           //Dados dos palpites
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.'),    //Prêmio possível
            'vencedora' => ApostaHelper::apostasWins($aposta)]);                //Informação se aposta vencedora
    }

    /**Método que realiza validação de aposta
     * @param Request $request dados para validação
     * @return \Illuminate\Http\JsonResponse dados da aposta
     */
    public function validar(Request $request)
    {
        $user = User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();    //Busca usuário pelo código de segurança
        $resposta = ApostaHelper::verificarUsuario($user);                              //Verifica usuário
        if (!is_null($resposta)):                                                       //Se houve erro
            return response()->json($resposta, $resposta['erro']);                      //Retorna erro
        endif;
        if(!$user->can('validar-aposta')):                                              //Se usuário não tem permissão para validar aposta
            return response()->json(['status' => 'Credenciais Insuficientes'], 501);    //Retorna json informando
        endif;
        $aposta = Aposta::buscarPorAtributo('codigo', $request->codigo_aposta)->first();//Busca aposta pelo código
        if (is_null($aposta)):                                                          //Se aposta for nula (não existe)
            return response()->json(['status' => 'codigo_nao_encontrado'], 403);        //Retorna json informando erro
        endif;
        if ($aposta->ativo):                                                            //Se aposta já estiver ativa
            return response()->json(['status' => 'aposta_ja_ativa'], 406);              //Retorna json informando erro
        endif;
        $aposta->ativo = true;                                                          //Altera atributo ativo para verdadeiro
        $aposta->users_id = $user->id;                                                  //Passa id do cambista
        $aposta->save();                                                                //Salva alteração
        $dados_aposta = ApostaHelper::dadosAposta($aposta);                             //Formata dados da aposta
        ApostaHelper::removerDadosDeJogos($dados_aposta['jogos']);                      //Remove dados dos jogos
        unset($dados_aposta['ganho']);                                                  //Remove dados referentes a ganhos
        //Retorna json com dados da aposta
        return response()->json([
            'cambista' => $user->name,
            'aposta' => $dados_aposta,
            'palpites' => ApostaHelper::dadosPalpites($aposta->jogo),
            'possivel_premio' => number_format(ApostaHelper::calcularPremio($aposta), 2, ',', '.')]);
    }

    /** Método que retorna relatório de ganhos de cambista associado a gerente
     * @param $codigo_gerente string codigo de segurança do gerente
     * @return \Illuminate\Http\JsonResponse relatório com dados de aposta de cambistas associados a gerente
     */
    public function relatorioGerente($codigo_gerente)
    {
        $gerente = User::buscarPorCodigoSeguranca($codigo_gerente)->first();                            //Busca usuário pelo codigo
        $resposta = ApostaHelper::verificarUsuario($gerente);                                           //Verifica usuário
        if (!is_null($resposta)):                                                                       //Se houve erro
            return response()->json($resposta, $resposta['erro']);                                      //Retorna erro
        endif;
        if (!$gerente->hasRole('gerente')) :                                                            //Se usuário não tiver role de gerente
            return response()->json(['status' => 'Credenciais Insuficientes'], 501);                    //Retorna json informando
        endif;
        $relatorio = Array();                                                                           //Cria array para armazenar dados de relatório
        foreach ($gerente->users as $cambista):                                                         //Percorre relação de cambistas ligados ao gerente
            $apostas = Aposta::recentes($cambista);                                                     //Busca apostas recentes do cambista
            $relatorio[] = ['nome_cambista' => $cambista->name] +                                       //Nome do cambista
                ['com_abertas' => ApostaHelper::calcularGanho($apostas)] +                              //Dados de ganhos referentes a todas as apostas
                ['sem_abertas' => ApostaHelper::calcularGanho(ApostaHelper::removerAbertas($apostas))]; //Dados referentes a ganhos de apostas fechadas
        endforeach;
        return response()->json(['relatorio'=>$relatorio]);                                             //Retorna json com dados de relatório
    }

    /** Método que muda o atributo ultimo_pagamento do usuario para o momento atual
     * @param $codigo_c codigo do Cambista , $codigo_a codigo de um Admin
     * @return mixed array confirmação da alteração
     */

    public function acerto($codigo_c, $codigo_gerente)
    {
        $cambista = \App\User::buscarPorCodigoSeguranca($codigo_c)->first();
        $resposta = ApostaHelper::verificarUsuario($cambista);                                       //Verifica restrição usuário
        if (!is_null($resposta)) {                                                                  //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                                  //Retorna json com restrição encontrada
        }/*
        if (!$cambista->hasRole('agente')) {                                                        //Se retornou restrição
            return response()->json(['status' => 'Credenciais Insuficientes', 'erro' => 501], 501); //Retorna json com restrição encontrada
        }*/
        $gerente = \App\User::buscarPorCodigoSeguranca($codigo_gerente)->first();
        $resposta = ApostaHelper::verificarUsuario($gerente);                                       //Verifica restrição usuário
        if (!is_null($resposta)) {                                                                  //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                                  //Retorna json com restrição encontrada
        }
        /*if (!$gerente->hasRole('gerente') || $cambista->users_id != $gerente->id) {                 //Se retornou restrição
            return response()->json(['status' => 'Credenciais Insuficientes', 'erro' => 501], 501); //Retorna json com restrição encontrada
        }*/
        $apostas = Aposta::recentes($cambista);
        $apostas = ApostaHelper::removerAbertas($apostas);
        foreach ($apostas as $aposta) {
            $aposta->pago = true;
            $aposta->save();
        }
        //
        if (!$apostas->isEmpty()):                          //Se relação de apostas não estiver vazio
            $acerto = new \App\Acerto();                    //Instancia acerto
            $dados = ApostaHelper::calcularGanho($apostas); //Busca dados de ganhos de apostas
            $acerto->cambista_id = $cambista->id;           //Passa id do cambista
            $acerto->gerente_id = $gerente->id;             //Passa id do gerente
            $acerto->qtd_apostas = $dados['qtd_apostas'];   //Passa quantidade de apostas
            $acerto->qtd_jogos = $dados['qtd_jogos'];       //Passa quantidade de jogos
            //Passa valor da comissão simples
            $acerto->comissao_simples = str_replace(',', '.', str_replace('.', '', $dados['comissao_simples']));
            //Passa valor da comissão mediana
            $acerto->comissao_mediana = str_replace(',', '.', str_replace('.', '', $dados['comissao_mediana']));
            //Passa valor da comissão máxima
            $acerto->comissao_maxima = str_replace(',', '.', str_replace('.', '', $dados['comissao_maxima']));
            //Passa valor total apostado
            $acerto->total_apostado = str_replace(',', '.', str_replace('.', '', $dados['total_apostado']));
            //Passa valor total de premiação
            $acerto->total_premiacao = str_replace(',', '.', str_replace('.', '', $dados['total_premiacao']));
            //Passa valor liquido
            $acerto->liquido = str_replace(',', '.', str_replace('.', '', $dados['liquido']));
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
