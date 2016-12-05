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
        $jogos = \App\Jogo::with('time', 'campeonato')
        ->where('ativo','<>', true)
        ->whereBetween('data', [Carbon::now()->addMinute(5), Carbon::now()->addDay(1)->setTime(23, 59, 59)])
        ->get();
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
        ->where('pago', '<>', true)->get();

        $temp = $this->calcRetorno($apostas);        

        foreach ($apostas as $key => $aposta) 
        {
            if ($this->apostasWins($aposta)) 
            {
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

    /**Método para realização de aposta via Web Service
     * @param Request $request dados da aposta
     * @return \Illuminate\Http\JsonResponse resultado da operação
     */
    public function apostar(Request $request)
    {
        $user = \App\User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                         //Verifica restrição usuário
        if (!is_null($resposta)):                                           //Se retornou restrição
            return response()->json($resposta, 400);                        //Retorna json com restrição encontrada
            endif;
        $jogos_invalidos = $this->verificarJogos($request->jogo);       //Valida jogos
        if (count($jogos_invalidos) > 0):                               //Verifica se quantidade de jogos inválidos é maior que zero
        return response()->json(
                ['jogos_invalidos' => $jogos_invalidos], 400);          //retorna json com array com todos os jogos inválidos
        endif;
        /*$palpites_invalidos = $this->verificarPalpites($palpites);    //Verifica se há palpites inválidos
        if (count($palpites_invalidos) > 0):                            //Verifica se quantidade de palpites inválidos é maior que zero
            return response()->json(
                ['palpites_invalidos' => $palpites_invalidos]);         //retorna json com array com todos os palpites inválidos
                endif;*/
        $aposta = $this->registrarAposta($request, $user);              //Registra aposta
        return response()->json(['aposta' => $aposta]);                 //Retorna json com a aposta feita e código
    }

    /** Método que verifica se usuário possui alguma restrição
     * @param $user \App\User usuário a ser verificado
     * @return array|null informação de problema do usuário ou null caso usuário esteja apto
     */
    private function verificarUsuario($user)
    {
        if (is_null($user)):                        //Verificar se usuário existe
            return ['status' => 'Inexistente'];     //Retorna status de usuário inexistente
            endif;
        if (!$user->ativo):                         //Verificar se usuário não está ativo
            return ['status' => 'Inativo'];         //Retorna status de usuário inativo
            endif;
        return null;                                //Retorn null
    }

    /** Método que verifica se jogos estão válidos para realização de aposta
     * @param $jogos mixed de jogas para validar
     * @return array|string lista de jogos inválidos ou informação de número mínimo de jogos
     */
    private function verificarJogos($jogos)
    {
        if (count($jogos) < 2):                     //Verifica se quantidade de jogos é menor que 2
            return "Minimo 2 jogos";                //Retorna mensagem
            endif;
        $jogos_invalidos = Array();                 //Cria array para armazenar jogos que não podem receber aposta
        foreach ($jogos as $valor):                 //Realiza interação em todos os jogos
            $jogo = Jogo::find($valor);             //Busca jogo pelo id (valor)
            //Verificar  jogo é nulo ou se data e hora do jogo é menor horário que a data atual menos 5 minutos
            if ($jogo == null || $jogo->data < Carbon::now()->addMinute(5)):
               $jogos_invalidos[] = $jogo;         //Se passou do horário para apostar coloca o joga no array
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
    private function registrarAposta(Request $request, \App\User $user)
    {
        //Instancia uma aposta com dados vindos do request e passando id do usuário
        $aposta = \App\Aposta::create($request->all());
        $aposta->users_id=$user->id;
        $hashids = new Hashids('betsoccer2', 5);
        $aposta->codigo = $hashids->encode($aposta->id);
        $aposta->save();                                            //Salva aposta
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
        //Busca o usuário pelo código de segurança
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                 //Verifica restrição usuário
        if (!is_null($resposta)):                                   //Se retornou restrição
            return response()->json($resposta, 400);                //Retorna json com restrição encontrada
            endif;
        $apostas = Aposta::recentes($user);                         //Busca as apostas recentes feitas pelo usuário
        $ganho_simples = 0;                                         //Cria variável para acumular comissão com apostas simples (2 jogos)
        $ganho_mediano = 0;                                         //Cria variável para acumular comissão com apostas medianas (3 jogos)
        $ganho_maximo = 0;                                          //Cria variável para acumular comissão com apostas máximas (mais de 2 jogos)
        $premiacao = 0;
        $qtd_jogos = 0;                                             //Cria variável para acumular quantidade de jogos
        foreach ($apostas as $aposta):                              //Itera pela lista de apostas
            switch ($aposta->jogo()->count()) :                     //Seleciona quantidade de jogos como parâmetro
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
            if ($this->apostasWins($aposta)):                       //Verifica se aposta foi vencedora
                $premiacao += $this->calcularPremio($aposta);       //soma a premiação
                endif;
            $qtd_jogos += $aposta->jogo()->count();                 //Acrescenta quantidade de jogos a variável
            //dd($premiacao);
            endforeach;
        //Obtém o valor total da comissão somando as comissões de aposta simples, mediana e máxima
            $ganho_total = $ganho_simples + $ganho_mediano + $ganho_maximo;
        /*Retorna o id e nome do cambista, quantidade de apostas, quantidade de jogos, comissão de apostas simples,
        mediana e máxima, comissão total do cambista*/
        $liquido = $apostas->sum('valor_aposta') - $premiacao - $ganho_total;
        //dd($liquido);
        return response()->json([
            'codigo' => $user->codigo,
            'cambista' => $user->name,
            'qtd_apostas' => $apostas->count(),
            'qtd_jogos' => $qtd_jogos,
            'comissao_simples' => $ganho_simples,
            'comissao_mediana' => $ganho_mediano,
            'comissao_maxima' => $ganho_maximo,
            'comissao_total' => $ganho_total,
            'total_premiação' => $premiacao,
            'liquido' => $liquido,
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
        $resposta = $this->verificarUsuario($user);                 //Verifica restrição usuário
        if (!is_null($resposta)):                                   //Se retornou restrição
            return response()->json($resposta, 400);                //Retorna json com restrição encontrada
        endif;
        $apostas = Aposta::recentes($user);                         //Busca as apostas recentes feitas pelo usuário
        $premiacao_total = 0;
        $ganho_total = 0;                                             //Cria variável para acumular quantidade de jogos
        $premiacao_paga = 0;
        $premiacao_nao_paga = 0;
        $lista_apostas = Array();
        $apostas_vencedoras = Array();
        foreach ($apostas as $aposta):                              //Itera pela lista de apostas
            switch ($aposta->jogo()->count()) :                     //Seleciona a quantidade de jogos como parâmetro
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
            $ganho_aposta = $aposta->valor_aposta * $porcentagem;   //Calcula o ganho por cada aposta
            //Cria array para armazenar id e ganho da aposta
            $lista_apostas [] = [
            'aposta' => $aposta->codigo,
            'ganho' => $ganho_aposta,
            'jogos' => $this->dadosJogos($aposta->jogo)];

            if ($this->apostasWins($aposta)):                       //Se aposta for vencedora
                $premiacao_aposta = $this->calcularPremio($aposta); //Calcula prêmio
                //Gera array de dados da aposta
                $apostas_vencedoras[] = $this->dadosAposta($aposta, $premiacao_aposta);
                if ($aposta->pago):                                 //Verifica se aposta foi paga (o prêmio)
                    $premiacao_paga += $premiacao_aposta;           //Soma valor a de premiações pagas
                    else:
                    $premiacao_nao_paga += $premiacao_aposta;       //Soma valor a premiações não pagas
                endif;
                $premiacao_total += $premiacao_aposta;              //Acrescenta a premiação da aposta a premiação total
                endif;
            $ganho_total += $ganho_aposta;                          //Soma o ganho de cada aposta para formar o montante
            endforeach;

        //dd($premiacao_total);
            return response()->json([
                'codigo' => $user->codigo,
                'cambista' => $user->name,
                'apostas_vencedoras' => $apostas_vencedoras,
                'total_premiacao' => $premiacao_total,
                'apostas' => $lista_apostas,
                ]);
        }

    /** Método que formata dados de aposta em array
     * @param $aposta Aposta aposta cujos dados serão passados para array
     * @param int $premiacao_aposta valor da premiação da aposta
     * @return array array com dados da aposta
     */
        private function dadosAposta($aposta, $premiacao_aposta = 0)
        {
            return [
            'codigo' => $aposta->codigo,
            'data' => $aposta->created_at,
            'apostador' => $aposta->nome_apostador,
            'valor_apostado' => $aposta->valor_aposta,
            'premio' => $premiacao_aposta,
            'paga' => $aposta->pago,
            'jogos' => $this->dadosJogos($aposta->jogo)
            ];
        }

    /** Método que formata dados de jogos em array
     * @param $jogos array lista de jogos
     * @return array array com dados dos jogos
     */
        private function dadosJogos($jogos)
        {
            $lista = Array();
            foreach ($jogos as $jogo):
                $lista[] = ['times' => $jogo->time()->get()->toArray(),
            'resultado' => ['r_casa' => $jogo->r_casa, 'r_fora' => $jogo->r_fora],
            'data' => $jogo->data];
            endforeach;
            return $lista;
        }

    /** Método que calcula valor do prêmio de uma aposta vencedora
     * @param $aposta Aposta vencedora
     * @return mixed valor do prêmio
     */
        private function calcularPremio($aposta)
        {
            $premio = $aposta->valor_aposta;
            foreach ($aposta->jogo as $jogo):
                $premio *= $jogo->pivot->palpite;
            endforeach;
            return $premio;
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
        $apostas = Aposta::with(['jogo'])
        ->where('pago', '<>', true)
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
        //Lista de apostas é passada para a view
        return view('aposta.allapostas', compact('apostas', 'premios', 'total', 'apostasPagas', 'premiosPago', 'totalPago'));
    }
}