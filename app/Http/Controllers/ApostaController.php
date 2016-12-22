<?php

namespace App\Http\Controllers;

use App\Aposta;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Jogo;
use Carbon\Carbon;
use DB;
use Hashids\Hashids;

class ApostaController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getJsonJogos()
    {
        $jogos = Jogo::disponiveis();
        return response()->json(array("jogos" => $jogos));
    }

    public function index()
    {
        $results = DB::select('select DISTINCT  CAST(data AS date) AS dataS , campeonatos_id from jogos order by data');
        $jogos = \App\Jogo::with('time', 'campeonato')->get();
        $campeonatos = \App\Campeonato::all();
        return view('aposta.index', compact('jogos', 'campeonatos', 'results'));
    }

    public function listaAposta()
    {

        $apostaWins = [];
        $total = [];
        $apostas = \App\Aposta::with('user')
            ->where('pago', false)
            ->where('ativo', true)
            ->where('users_id','<>',0)
            ->get();

        $temp = $this->calcRetorno($apostas);

        foreach ($apostas as $key => $aposta) {
            if ($this->apostasWins($aposta)) {
                array_push($apostaWins, $aposta);
                array_push($total, $temp[$key]);
            }
        }
        // dd($apostaWins);
        return view('aposta.wins', compact('apostaWins', 'total'));
    }

    /**
     * Método que verifica se uma aposta foi vencedora ou não pega a lista de jogos de uma aposta
     * e verificar o resultados do jogo em relaçao aos palpites do apostador
     * Caso ele acerte todo os palpites retorna um true.
     * @param $apostas Aposta
     * @return Boolean true or false
     */
    public function apostasWins($aposta)
    {
        $i = 0;
        foreach ($aposta->jogo as $key => $jogo) {
            if ((is_null($jogo->r_casa)) && (is_null($jogo->r_fora))) {
                return false;
            }
            if (($jogo->pivot->tpalpite == "valor_casa") && ($jogo->r_casa > $jogo->r_fora)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "valor_fora") && ($jogo->r_casa < $jogo->r_fora)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "valor_empate") && ($jogo->r_casa == $jogo->r_fora)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "ambas_gol") && ($jogo->r_casa > 0 && $jogo->r_fora > 0)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "min_gol_3") && ($jogo->r_casa + $jogo->r_fora >= 3)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "max_gol_2") && ($jogo->r_casa + $jogo->r_fora < 3)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "valor_1_2") && ($jogo->valor_casa < $jogo->valor_fora && $jogo->r_casa - $jogo->r_fora >= 2)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "valor_1_2") && ($jogo->valor_casa > $jogo->valor_fora && $jogo->r_fora - $jogo->r_casa >= 2)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "valor_dupla") && ($jogo->valor_casa > $jogo->valor_fora && $jogo->r_casa >= $jogo->r_fora)) {
                $i++;
            }
            if (($jogo->pivot->tpalpite == "valor_dupla") && ($jogo->valor_casa < $jogo->valor_fora && $jogo->r_casa <= $jogo->r_fora)) {
                $i++;
            }
        }
        if ($i != count($aposta->jogo)) {
            return false;
        } else {
            return true;
        }
    }

    public function cadastrar()
    {
        $time = \App\Jogo::all();
        return view('aposta.cadastrar', compact('time'));
    }

    public function salvar(\App\Http\Requests\ApostaRequest $request)
    {
        $jogo = [];
        $palpite = [];
        $jogo = $request->get('jogo');
        //dd($request->all());
        $aposta = \App\Aposta::create($request->all());
        foreach ($jogo as $jogos => $value) {
            $jo = Jogo::find($value);
            $text = "palpite";
            $text .= $value;
            $consu = $request->get($text);
            $palpite ['palpite'] = $jo->$consu;
            $palpite ['tpalpite'] = $consu;
            $aposta->jogo()->attach($value, $palpite);
        }
        $aposta->save();
        \Session::flash('flash_message', [
            'msg' => "Aposta realizada com Sucesso",
            'class' => "alert-success"
        ]);
        return redirect()->route('aposta.index');
    }

    public function apostar(Request $request)
    {
       if(is_null($request->codigo_seguranca)):                     //Se código de segurança é nulo
            return response()->json(
                ['status'=>'codigo_seguranca_nao_informado'], 409);//retorna json informando erro
       endif;
       return $this->realizarAposta($request);                      //Tentar realizar aposta
    }

    public function apostarSemCodigo(Request $request)
    {
       return $this->realizarAposta($request);                      //Tenta realizar aposta
    }
    /**Método para realização de aposta via Web Service
     * @param Request $request dados da aposta
     * @
     return \Illuminate\Http\JsonResponse resultado da operação
     */
    private function realizarAposta(Request $request)
    {   $user = null;                                                       //Cria variável para guardar usuário
        if($request->codigo_seguranca):                                     //Verifica se foi passado codigo de segurança
            //Busca o usuário pelo código de segurança
            $user = \App\User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
            $resposta = $this->verificarUsuario($user);                      //Verifica restrição usuário
            if (!is_null($resposta)):                                        //Se retornou restrição
                return response()->json($resposta, $resposta['erro']);       //Retorna json com restrição encontrada
            endif;
        endif;
        $jogos_invalidos = $this->verificarJogos($request->jogo);       //Valida jogos
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
        $aposta = $this->registrarAposta($request, $user);              //Registra aposta
        $retorno = ['aposta' => $aposta];                               //Passa dados de aposta para retorno
        if(!is_null($user)):                                            //Verifica se usuário não é nulo
                $retorno+=['cambista' => $user->name];                  //Acrescenta nome do usuário (cambista) no resultado
        endif;
        return response()->json($retorno);                              //Retorna json com resultado
    }

    /** Método que verifica se usuário possui alguma restrição
     * @param $user \App\User usuário a ser verificado
     * @return array|null informação de problema do usuário ou null caso usuário esteja apto
     */
    private function verificarUsuario($user)
    {
        if (is_null($user)):                                    //Verificar se usuário existe
            return ['status' => 'Inexistente', 'erro' => 400];  //Retorna status de usuário inexistente
        endif;
        if (!$user->ativo):                                     //Verificar se usuário não está ativo
            return ['status' => 'Inativo', 'erro' => 401];      //Retorna status de usuário inativo
        endif;
        return null;                                            //Retorna null
    }

    /** Método que verifica se jogos estão válidos para realização de aposta
     * @param $jogos mixed de jogas para validar
     * @return array|string lista de jogos inválidos ou informação de número mínimo de jogos
     */
    private function verificarJogos($jogos)
    {
        if (count($jogos) < 2):                     //Verifica se quantidade de jogos é menor que 2
            return ['status' => "Minimo 2 jogos",
                    'erro' => 402];                 //Retorna mensagem e erro
        endif;
        $jogos_invalidos = Array();                 //Cria array para armazenar jogos que não podem receber aposta
        foreach ($jogos as $valor):                 //Realiza interação em todos os jogos
            $jogo = Jogo::find($valor);             //Busca jogo pelo id (valor)
            //Verificar  jogo é nulo ou se data e hora do jogo é menor horário que a data atual menos 5 minutos
            if ($jogo == null || $jogo->data < Carbon::now()->addMinute(5)):
                $jogos_invalidos[] = $jogo;         //Passou jogo para array
                $jogos_invalidos['erro'] = 403;     //Passa código de erro para array
            endif;
        endforeach;
        return $jogos_invalidos;                    //retorna o array com jogos que não podem ser feita aposta
    }

    /** Método que verifica validade de palpites
     * @param $palpites \Illuminate\Support\Collection com palpites a serem verificados
     * @return array palpites inválidos
     */
    private function verificarPalpites($palpites)
    {
        $palpites_invalidos = Array();                  //Cria array para armazenar palpites inválidos
        foreach ($palpites as $palpite):                //Interra sobre palpites
            $jogo = Jogo::find($palpite['jogo_id']);    //Busca jogo pelo id
            if ($jogo->$palpite['tpalpite'] == 0):       //Verifica se valor do palpite no jogo é zero
                $palpites_invalidos[] = $palpite;       //Passa palpite inválido para array
            endif;
        endforeach;
        return $palpites_invalidos;                     //Retorna array com palpites inválidos
    }

    /** Método que registra aposta no sistema
     * @param Request $request dados para registro
     * @param \App\User $user usuário que responsável pela aposta
     * @param \Illuminate\Support\Collection $palpites array de palpites
     * @return Aposta aposta feita
     */
    private function registrarAposta(Request $request, $user)
    {
        $aposta = \App\Aposta::create($request->all());             //Cria uma aposta com dados vindos do request
        if(is_null($user)):                                         //Verifica se usuário é nulo
           $aposta->ativo = false;                                  //Passa false para atributo ativo
        else:                                                       //Se usuário não for nulo
            $aposta->users_id = $user->id;                          //Passa id do usuário
        endif;
        $hashids = new Hashids('betsoccer2', 5);
        $aposta->codigo = $hashids->encode($aposta->id);
        $aposta->save();                                            //Salva aposta
        /*$aposta =  new \App\Aposta($request->all());
        $aposta->users_id = $user->id;
        $hashids = new Hashids('betsoccer2', 5);
        $aposta->codigo = $hashids->encode(DB::table('apostas')->max('id')+1);
        $aposta->save();*/

        for ($i = 0; $i < count($request->jogo); $i++):             //Criar iteração com base no número de jogos
            $palpite ['palpite'] = $request->valorPalpite[$i];      //Passa valor do palpite para array
            $palpite['tpalpite'] = $request->tpalpite[$i];          //Passa texto do palpite para array
            $aposta->jogo()->attach($request->jogo[$i], $palpite);  //Relaciona aposta com jogo incluindo os dados de palpite
        endfor;
        return $aposta;                                             //Retorna a aposta
    }


    /** Método que cálcula o valor a ser recebido pelas apostas feitas
     * @param $codigo_seguranca string código que identifica o usuário
     * @return \Illuminate\Http\JsonResponse json com resultado da operação
     */
    public function ganhosApostas($codigo_seguranca)
    {
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();//Busca o usuário pelo código de segurança
        $resposta = $this->verificarUsuario($user);                             //Verifica restrição usuário
        if (!is_null($resposta)):                                               //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);              //Retorna json com restrição encontrada
        endif;
        $apostas = Aposta::recentes($user);                                     //Busca as apostas recentes feitas pelo usuário
        $ganho_simples = 0;                                                     //Cria variável para acumular comissão com apostas simples (2 jogos)
        $ganho_mediano = 0;                                                     //Cria variável para acumular comissão com apostas medianas (3 jogos)
        $ganho_maximo = 0;                                                      //Cria variável para acumular comissão com apostas máximas (mais de 2 jogos)
        $premiacao = 0;
        $qtd_jogos = 0;                                                         //Cria variável para acumular quantidade de jogos
        foreach ($apostas as $aposta):                                          //Itera pela lista de apostas
            switch ($aposta->jogo()->count()) :                                 //Seleciona quantidade de jogos como parâmetro
                case 2:
                    //Cálcula ganho para aposta com dois jogos
                    $ganho_simples += $aposta->valor_aposta * (config('constantes.porcentagem_simples') / 100);
                    break;
                case 3:
                    //Cálcula ganho para aposta com três jogos
                    $ganho_mediano += $aposta->valor_aposta * (config('constantes.porcentagem_mediana') / 100);
                    break;
                default:
                    //Cálcula ganho para aposta com mais três jogos
                    $ganho_maximo += $aposta->valor_aposta * (config('constantes.porcentagem_maxima') / 100);
                    break;
            endswitch;
            if ($this->apostasWins($aposta)):                                   //Verifica se aposta foi vencedora
                $premiacao += $this->calcularPremio($aposta);                   //soma a premiação
            endif;
            $qtd_jogos += $aposta->jogo()->count();                             //Acrescenta quantidade de jogos a variável
        endforeach;
        $ganho_total = $ganho_simples + $ganho_mediano + $ganho_maximo;         //Obtém o valor total da comissão somando as comissões de aposta simples, mediana e máxima
        $total_apostado = $apostas->sum('valor_aposta');                        //Soma total apostado
        $liquido = $total_apostado - $premiacao - $ganho_total;                 //Obtém o valor liquido
        return response()->json([
            'codigo' => $user->codigo,                                          //Código do cambista
            'cambista' => $user->name,                                          //Nome do cambista
            'qtd_apostas' => $apostas->count(),                                 //Quantidade de apostas
            'qtd_jogos' => $qtd_jogos,                                          //Quantidade de jogos
            'comissao_simples' => number_format($ganho_simples, 2, ',', '.'),   //Comissão de apostas simples
            'comissao_mediana' => number_format($ganho_mediano, 2, ',', '.'),   //Comissão de apostas medianas
            'comissao_maxima' => number_format($ganho_maximo, 2, ',', '.'),     //Comissão de apostas máximas
            'comissao_total' => number_format($ganho_total, 2, ',', '.'),       //Total de comissão
            'total_apostado' => number_format($total_apostado, 2, ',', '.'),    //Total apostado
            'total_premiacao' => number_format($premiacao, 2, ',', '.'),        //Total de premiação
            'liquido' => number_format($liquido, 2, ',', '.'),                  //Valor líquido
        ]);
    }

    /** Método que verifica a relação de premiações das apostas
     * @param $codigo_seguranca string codigo de segurança do cambista
     * @return \Illuminate\Http\JsonResponse informações relacionadas a apostas e premiação
     */
    public function premiosApostas($codigo_seguranca)
    {
        //Busca o usuário pelo código de segurança
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                     //Verifica restrição usuário
        if (!is_null($resposta)):                                       //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);      //Retorna json com restrição encontrada
        endif;
        $apostas = Aposta::recentes($user);                             //Busca as apostas recentes feitas pelo usuário
        $premiacao_total = 0;                                           //Cria variável para acumular premiação total
        $ganho_total = 0;                                               //Cria variável para acumular quantidade de jogos
        $premiacao_paga = 0;                                            //Cria variável para acumular premiação paga
        $premiacao_nao_paga = 0;                                        //Cria variável para acumular premiação não paga
        $lista_apostas = Array();                                       //Cria array para armazenar lista de apostas
        $apostas_vencedoras = Array();                                  //Cria array para armazenar lista de apostas vencedoras
        foreach ($apostas as $aposta):                                  //Itera pela lista de apostas
            switch ($aposta->jogo()->count()) :                         //Seleciona a quantidade de jogos como parâmetro
                case 2:
                    //Gera valor para cálculo de ganho para aposta com dois jogos
                    $porcentagem = config('constantes.porcentagem_simples') / 100;
                    break;
                case 3:
                    //Gera valor para cálculo de ganho para aposta com três jogos
                    $porcentagem = config('constantes.porcentagem_mediana') / 100;
                    break;
                default:
                    //Gera valor para cálculo de ganho para aposta com mais três jogos
                    $porcentagem = config('constantes.porcentagem_maxima') / 100;
                    break;
            endswitch;
            $ganho_aposta = $aposta->valor_aposta * $porcentagem;               //Calcula o ganho por cada aposta
            $premiacao_aposta = $this->calcularPremio($aposta);                 //Calcula o possível prêmio
            $dados_aposta = $this->dadosAposta($aposta, $ganho_aposta);         //Gera dados de aposta
            //Passa para array dados da aposta mais retorno possível
            $lista_apostas [] = $dados_aposta + ['retorno_possivel' => number_format($premiacao_aposta, 2, ',', '.')];
            if ($this->apostasWins($aposta)):                                   //Se aposta for vencedora
                $apostas_vencedoras[] = $dados_aposta
                    + ['premio' => number_format($premiacao_aposta, 2, ',', '.'),
                        'paga' => $aposta->pago];                               //Passa dados de aposta mais premiação e informação se já pago
                if ($aposta->pago):                                             //Verifica se aposta foi paga (o prêmio)
                    $premiacao_paga += $premiacao_aposta;                       //Soma valor a de premiações pagas
                else:                                                           //Se não foi paga
                    $premiacao_nao_paga += $premiacao_aposta;                   //Soma valor a premiações não pagas
                endif;
                $premiacao_total += $premiacao_aposta;                          //Acrescenta a premiação da aposta a premiação total
            endif;
            $ganho_total += $ganho_aposta;                                      //Soma o ganho de cada aposta para formar o montante
        endforeach;
        return response()->json([
            'codigo' => $user->codigo,                                          //Código do cambista
            'cambista' => $user->name,                                          //Nome do cambista
            'apostas_vencedoras' => $apostas_vencedoras,                        //Relação de apostas vencedoras
            'total_premiacao' => number_format($premiacao_total, 2, ',', '.'),  //Total de premiação
            'apostas' => $lista_apostas,                                        //Lista de apostas
        ]);
    }

    /** Método que formata dados de aposta em array
     * @param $aposta Aposta aposta cujos dados serão passados para array
     * @param float $ganho valor do ganho do cambista
     * @return array array com dados da aposta
     */
    private function dadosAposta($aposta, $ganho = 0)
    {
        return [
            'codigo' => $aposta->codigo,                                            //Código
            'data' => $aposta->created_at,                                          //Data
            'apostador' => $aposta->nome_apostador,                                 //Nome do apostador
            'valor_apostado' => number_format($aposta->valor_aposta, 2, ',', '.'),  //Valor apostado
            'ativa'=>(boolean)$aposta->ativo,                                                //Se ativa
            'ganho' => number_format($ganho, 2, ',', '.'),                          //Ganho do cambista
            'jogos' => $this->dadosJogos($aposta->jogo)                             //Relação de jogos
        ];
    }

    /** Método que formata dados de jogos em array
     * @param $jogos array lista de jogos
     * @return array array com dados dos jogos
     */
    private function dadosJogos($jogos)
    {
        $lista = Array();                           //Cria array para armazenar dados de jogos
        foreach ($jogos as $jogo):                  //Precorre lista de jogos
            $lista[] = ['id' => $jogo->id,          //Passa id
                'times' => $jogo->time->toArray(),  //Passa times
                'resultado' => [                    //Passa resultado
                    'r_casa' => $jogo->r_casa,      //Resultado de casa
                    'r_fora' => $jogo->r_fora],     //Resultado de fora
                'data' => $jogo->data];             //Passa data
        endforeach;
        return $lista;                              //Retorna lista com dados dos jogos
    }

    /** Método que calcula valor do prêmio de uma aposta vencedora
     * @param $aposta Aposta vencedora
     * @return mixed valor do prêmio
     */
    private function calcularPremio($aposta)
    {
        $premio = $aposta->valor_aposta;        //Passa valor da aposta para variável prêmio
        foreach ($aposta->jogo as $jogo):       //Percorre relação de jogos da aposta
            $premio *= $jogo->pivot->palpite;   //Multiplica o valor do prêmio pelo do palpite
        endforeach;
        return $premio;                         //Retorna valor do prêmio
    }

    /**
     * Método que cálcula o valor a ser pago com premios por cada aposta
     * Passada por paramentro e reotnar um array com a lista dos premios
     * @param $apostas Aposta Listas de aposta
     * @return Array com coleção dos premios das apotas passadas.
     */
    public function calcRetorno($apostas)
    {

        $premios = [];
        foreach ($apostas as $key => $aposta) {
            $premios[$key] = $aposta->valor_aposta;
            foreach ($aposta->jogo as $jogo) {
                if ($jogo->pivot->palpite != 0) {
                    $premios[$key] *= $jogo->pivot->palpite;
                }
            }
        }
        return $premios;
    }

    /**
     * Método que cálcula o valor a ser pago com premios por cada aposta
     * Passada por paramentro e reotnar um array com a lista dos premios
     * @param $apostas Aposta Listas de aposta
     * @return Array com coleção dos premios das apotas passadas.
     */
    public function calcApostasPagas($apostas)
    {
        $premios = [];
        foreach ($apostas as $key => $aposta) {
            $premios[$key] = $aposta->valor_aposta;
            foreach ($aposta->jogo as $jogo) {
                if ($jogo->pivot->palpite != 0) {
                    $premios[$key] *= $jogo->pivot->palpite;
                }
            }
        }
        return $premios;
    }

    public function resumoAposta()
    {
        $apostas = Aposta::with(['jogo','user'])
            ->where('pago', false)
            ->where('ativo', true)
            ->where('users_id','<>',0)
            ->get();
        $apostasPagas = Aposta::with(['jogo','user'])
            ->where('pago', true)
            ->where('ativo', true)
            ->where('users_id','<>',0)
            ->get();
        $users = DB::table('users')->select('id', 'name')->get();
        $total = 0;
        $totalPago = 0;
        $premios = $this->calcRetorno($apostas);
        $premiosPago = $this->calcRetorno($apostasPagas);
        $totalPago += array_sum($premiosPago);
        $total += array_sum($premios);
        //Lista de apostas é passada para a view
        return view('aposta.allapostas', compact('users', 'apostas', 'premios', 'total', 'apostasPagas', 'premiosPago', 'totalPago'));
    }

    /** Método que busca e retorna última aposta do cambista
     * @param $codigo_seguranca string com código de segurança do cambista
     * @return \Illuminate\Http\JsonResponse dados da última aposta feita pelo cambista
     */
    public function ultima($codigo_seguranca)
    {
        //Busca o usuário pelo código de segurança
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                     //Verifica restrição usuário
        if (!is_null($resposta)):                                       //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);      //Retorna json com restrição encontrada
        endif;
        $aposta = $user->apostas->last();                               //Busca última aposta feita pelo usuário
        if (is_null($aposta)):                                          //Se aposta for nula
            return response()->json(['aposta' => 'inexistente'], 403);  //Retorna json informando
        endif;
        $dados_aposta = $this->dadosAposta($aposta);                    //Busca dados de aposta
        $this->removerDadosDeJogos($dados_aposta['jogos']);             //Remove dados desnecessário de jogos
        unset($dados_aposta['ganho']);                                  //Remove o indice ganho
        //Retorna json com dados da última aposta feita pelo usuário
        return response()->json([
            'cambista' => $user->name,                                  //Nome do cambista
            'aposta' => $dados_aposta,                                  //Dados da aposta
            'palpites' => $this->dadosPalpites($aposta->jogo),          //Dados dos palpites
            //Valor do possível do prêmio
            'possivel_premio' => number_format($this->calcularPremio($aposta), 2, ',', '.')]);
    }

    /** Método que formata dados de palpites de jogos em array
     * @param $jogos \Illuminate\Support\Collection jogos com respectivos palpites
     * @return array relação de palpites de cada jogo
     */
    private function dadosPalpites($jogos)
    {
        $palpites = array();                                //Cria array para armazenar dados de palpites
        foreach ($jogos as $jogo):                          //Percorre coleção de jogos
            $palpites[] = [
                'jogos_id' => $jogo->pivot->jogos_id,       //Passa id do jogo
                'palpite' => $jogo->pivot->palpite,         //Passa valor para o palpite
                'tpalpite' => $jogo->pivot->tpalpite        //Passa texto do palpite
            ];
        endforeach;
        return $palpites;                                   //Retorna array com dados de palpites
    }

    /**Método que remove dados desnecessários de jogos
     * @param $jogos array de jogos para remover dados
     */
    private function removerDadosDeJogos(&$jogos)
    {
        foreach ($jogos as &$jogo):                 //Percorre array de jogos
            foreach ($jogo['times'] as &$time):     //Percorre array de times
                unset($time['created_at']);         //Remove campo created_at do time
                unset($time['updated_at']);         //Remove campo updated_at do time
                unset($time['pivot']);              //Remove dados de pivot do time
            endforeach;
        endforeach;
    }

    /** Método que muda o atributo ultimo_pagamento do usuario para o momento atual
     * @param $codigo_c codigo do Cambista , $codigo_a codigo de um Admin
     * @return mixed array confirmação da alteração
     */

    public function acerto($codigo_c, $codigo_a)
    {

        $cambista = \App\User::buscarPorCodigoSeguranca($codigo_c)->first();
        $resposta = $this->verificarUsuario($cambista);                                       //Verifica restrição usuário
        if (!is_null($resposta)) {                                                             //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                            //Retorna json com restrição encontrada
        }
        $adm = \App\User::buscarPorCodigoSeguranca($codigo_a)->first();
        $resposta = $this->verificarUsuario($adm);                                            //Verifica restrição usuário
        if (!is_null($resposta)) {                                                             //Se retornou restrição
            return response()->json($resposta, $resposta['erro']);                            //Retorna json com restrição encontrada
        }
        if ($adm->role != "admin") {                                                           //Se retornou restrição
            return response()->json(['status' => 'Credenciais Insuficientes','erro'=>501], 501);          //Retorna json com restrição encontrada
        }
        $ultimo_p = $cambista->ultimo_pagamento;
        \App\Aposta::where('users_id', $cambista->id)
            ->where('created_at', '>', $ultimo_p)
            ->update(['pago' => true]);
        $cambista->ultimo_pagamento = Carbon::now();
        $cambista->save();
    }

    public function receberDoCambista($apostas)
    {
        $valor_aposta = 0;
        foreach ($apostas as $key => $aposta) {
            $valor_aposta += $aposta->valor_aposta;
        }
        return $valor_aposta;
    }

    public function apostaCambista(Request $request)
    {
        $id = $request->get('cambista');
        $users = \App\User::find($id);
        $apostas = Aposta::with(['jogo'])
            ->where('users_id', '=', $id)
            ->where('created_at', '>', $users->ultimo_pagamento)
            ->get();
        $apostasPagas = Aposta::with(['jogo'])
            ->where('pago', '=', true)
            ->get();
        $total = 0;
        $totalPago = 0;
        $premios = $this->calcRetorno($apostas);
        $premiosPago = $this->calcRetorno($apostasPagas);
        $totalPago += array_sum($premiosPago);
        $total += array_sum($premios);
        $receber = $this->receberDoCambista($apostas);

        return view('aposta.apostaCambista', compact('users', 'apostas', 'receber', 'premios', 'total', 'apostasPagas', 'premiosPago', 'totalPago'));
    }

    public function consultar($codigo)
    {
        $aposta = Aposta::buscarPorAtributo('codigo', $codigo)->first();
        if(count($aposta)==0):
            return response()->json(['status'=>'codigo_nao_encontrado'], 403);
        endif;
        $dados_aposta = $this->dadosAposta($aposta);
        $this->removerDadosDeJogos($dados_aposta['jogos']);
        unset($dados_aposta['ganho']);
        $cambista = is_null($aposta->user)?null:$aposta->user->name;
        return response()->json([
            'cambista' => $cambista,                                  
            'aposta' => $dados_aposta,                                  
            'palpites' => $this->dadosPalpites($aposta->jogo),          
            'possivel_premio' => number_format($this->calcularPremio($aposta), 2, ',', '.'),
            'vencedora'=>$this->apostasWins($aposta)]);
    }
}