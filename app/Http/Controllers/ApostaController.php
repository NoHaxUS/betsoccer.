<?php

namespace App\Http\Controllers;

use App\Aposta;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\jogo;
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
        /*buscando todas as informacoes dos times
        foreach ($aposta->jogo as $key) {
               echo "JOGO" . $key->time;
               echo $key->pivot->palpite;
             }
        $aposta = \App\Aposta::paginate(10); 
      */
        $results = DB::select('select DISTINCT  CAST(data AS date) AS dataS , campeonatos_id from jogos order by data');
        $jogos = \App\Jogo::with('time', 'campeonato')->get();
        //dd($apostas);
        /*
        foreach ($apostas as $aposta) {
            echo $aposta->jogo->get('id');
        }
        */
        $campeonatos = \App\Campeonato::all();
        //$res = array_merge($results, $jogos->toArray(), $campeonatos->toArray());
        return response()->json(array("jogos" => $jogos));

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
        //Busca o usuário pelo código de segurança
        $user = \App\User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
        //Verificar se usuário existe
        if ($user == null):
            //Retorna json com informação que usuário não existe
            return response()->json(['status' => 'Inexistente']);
        endif;
        //Verificar se usuário não está ativo
        if (!$user->ativo):
            return response()->json(['status' => 'Inativo']);
        endif;
        //transforma json em array
        $jogos = json_decode($request->jogos, true);
        //Valida jogos
        $jogos_invalidos = $this->validarJogos($jogos);
        if (count($jogos_invalidos) > 0):
            return response()->json($jogos_invalidos);
        endif;
        //Instancia uma aposta
        $aposta = new \App\Aposta($request->all());
        $aposta->users_id = $user->id;
        //Cria um coleção de palpites a partir do json (Array) passado
        $palpites = collect(json_decode($request->get('palpites'), true))->collapse()->all();
        //Cria contador para coleção de palpites
        $cont = 0;
        //Intera sobre jogos
        foreach ($jogos as $value):
            //Busca jogo pelo id
            $jogo = Jogo::find($value)->first();
            $text = $palpites[$cont++];
            $palpite ['palpite'] = $jogo->$text;
            $palpite ['tpalpite'] = $text;
            //Incluir validação de palpite
            $aposta->save();
            $aposta->jogo()->attach($value, $palpite);
        endforeach;
        return response()->json(['status' => 'Aposta feita']);
    }

    /** Método que verifica se jogos estão válidos para realização de aposta
     * @param $jogos mixed de jogas para validar
     * @return array lista de jogos inválidos
     */
    private function validarJogos($jogos)
    {
        //Cria array para armazenar jogos que não podem receber aposta
        $jogos_invalidos = Array();
        //Realiza interação em todos os jogos
        foreach ($jogos as $valor):
            //Busca jogo pelo id (valor)
            $jogo = Jogo::find($valor)->first();
            //Verificar horário
            if ($jogo == null || $jogo->data < Carbon::now()->subMinute(5)):
                //Se passou do horário para apostar coloca o joga no array
                $jogos_invalidos[] = $jogo;
            endif;
        endforeach;
        //retorna o array com jogos que não podem ser feita aposta
        return $jogos_invalidos;
    }

    /** Método que cálcula o valor a ser recebido pelas apostas feitas
     * @param $codigo_seguranca string código que identifica o usuário
     * @return \Illuminate\Http\JsonResponse json com resultado da operação
     */
    public function ganhosApostas($codigo_seguranca)
    {
        //Definição de porcentagem por meio de constante
        $porcentagem = config('constantes.porcentagem') / 100;
         //Busca o usuário pelo código de segurança
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        //Verificar se usuário existe
        if ($user == null):
            //Retorna json com informação que usuário não existe
            return response()->json(['status' => 'Inexistente']);
        endif;
        //Verificar se usuário não está ativo
        if (!$user->ativo):
            return response()->json(['status' => 'Inativo']);
        endif;
        //Busca as apostas recentes (últimos 7 dias) feitas pelo usuário
        $total = Aposta::recentes($user->id)->sum('valor_aposta');
        //Calcula o valor a ser recebido pelo usuário
        $ganho = $total * $porcentagem;
        //Retorna o total de aposta e o valor que o usuário deverá receber
        return response()->json(['total' => $total, 'ganho' => $ganho]);
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
}