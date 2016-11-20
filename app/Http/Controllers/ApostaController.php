<?php

namespace App\Http\Controllers;

use App\Aposta;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Jogo;
use Carbon\Carbon;
use DB;

class ApostaController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {


        $jogos = \App\Jogo::with('time', 'campeonato')
        ->where('data', '>', Carbon::now()->addMinutes(5))
        ->get();
        $campeonatos = \App\Campeonato::all();
        return response()->json(array("jogos" => $jogos));
    }

    public function index2()
    {
        $results = DB::select('select DISTINCT  CAST(data AS date) AS dataS , campeonatos_id from jogos order by data');
        $jogos = \App\Jogo::with('time', 'campeonato')->get();
        $campeonatos = \App\Campeonato::all();
        return view('aposta.index', compact('jogos', 'campeonatos', 'results'));
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
        $jogos_invalidos = $this->verificarJogos($request->jogo);           //Valida jogos
        if (count($jogos_invalidos) > 0):                                   //Verifica se quantidade de jogos inválidos é maior que zero
            return response()->json(
                ['jogos_invalidos' => $jogos_invalidos], 400);              //retorna json com array com todos os jogos inválidos
        endif;
        /*$palpites_invalidos = $this->verificarPalpites($palpites);        //Verifica se há palpites inválidos
        if (count($palpites_invalidos) > 0):                                //Verifica se quantidade de palpites inválidos é maior que zero
            return response()->json(
                ['palpites_invalidos' => $palpites_invalidos]);             //retorna json com array com todos os palpites inválidos
        endif;*/
        return response()->json(
            ['aposta' => $this->registrarAposta($request, $user)]);         //Retorna json com a aposta feita
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
     * @return array lista de jogos inválidos
     */
    private function verificarJogos($jogos)
    {
        $jogos_invalidos = Array();                 //Cria array para armazenar jogos que não podem receber aposta
        foreach ($jogos as $valor):                 //Realiza interação em todos os jogos
            $jogo = Jogo::find($valor);             //Busca jogo pelo id (valor)
            //Verificar  jogo é nulo ou se data e hora do jogo é menor horário que a data atual menos 5 minutos
            if ($jogo == null || (new Carbon($jogo->data)) < Carbon::now()->subMinute(5)):
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
        $aposta = new \App\Aposta($request->all());                 //Instancia uma aposta
        $aposta->users_id = $user->id;                              //Passa id do usuário responsável pela aposta
        $aposta->save();                                            //Salva aposta
        for ($i = 0; $i < count($request->jogo); $i++):             //Criar iteração com base no número de jogos
            $palpite ['palpite'] = $request->valorPalpite[$i];      //Passa valor do palpite para array
            $palpite['tpalpite'] = $request->tpalpite[$i];          //Passa texto do palpite para arrayu
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
        $porcentagem = config('constantes.porcentagem') / 100;      //Definição de porcentagem por meio de constante
        //Busca o usuário pelo código de segurança
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                 //Verifica restrição usuário
        if (!is_null($resposta)):                                   //Se retornou restrição
            return response()->json($resposta);                     //Retorna json com restrição encontrada
        endif;
        $apostas = Aposta::recentes($user->id);                     //Busca as apostas recentes feitas pelo usuário
        $total = $apostas->sum('valor_aposta');                     //Calcula o valor total
        $ganho_total = 0;                                           //Cria variável para acumular o ganho de cada aposta
        $lista_apostas = Array();                                   //Cria array para guardar lista de apostas
        foreach ($apostas as $aposta):                              //Itera pela lista de apostas
            $ganho_aposta = $aposta->valor_aposta * $porcentagem;   //Calcula o ganho por cada aposta
            $lista_apostas [] = [
                'aposta' => $aposta->id,
                'ganho' => $ganho_aposta
            ];                                                      //Cria array para armazenar id e ganho da aposta
            $ganho_total += $ganho_aposta;                          //Soma o ganho de cada aposta para formar o montante
        endforeach;
        $cambista = [
            'numero' => $user->id,
            'nome' => $user->name
        ];                                                          //Guarda dados de cambista
        //Retorna o dados do cambista, lista de apostas, ganho total do cambista e valor total de apostas
        return response()->json([
            'cambista' => $cambista,
            'apostas' => $lista_apostas,
            'ganho_total' => $ganho_total,
            'total' => $total,
        ]);
    }

    public function show($id)
    {

    }

    public function showAll()
    {
        $jogos = \App\Aposta::find(4)->jogo()->get();
        foreach ($jogos as $jogo) {
            dd($jogo->pivot->palpite);
        }
        dd($jogos);
    }

    /*
    * Metodo calcula valor a pagar por aposta
    * */
    public function resumoAposta()
    {
        //Obtenho todas as apostas
        $apostas = Aposta::with(['jogo'])->get();
        //Lista de apostas é passada para a view
        return view('apostaJogo.index', compact('apostas'));
    }
}