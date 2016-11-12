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

    /**M�todo para realiza��o de aposta via Web Service
     * @param Request $request dados da aposta
     * @return \Illuminate\Http\JsonResponse resultado da opera��o
     */
    public function apostar(Request $request)
    {
        //Busca o usu�rio pelo c�digo de seguran�a
        $user = \App\User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
        //Verifica restri��o usu�rio
        $resposta = $this->verificarUsuario($user);
        //Se retornou restri��o
        if (!is_null($resposta)):
            //Retorna json com restri��o encontrada
            return response()->json($resposta);
        endif;
        //transforma json de jogos em array
        $jogos = json_decode($request->jogos, true);
        //Valida jogos
        $jogos_invalidos = $this->validarJogos($jogos);
        //Verifica se quantidade de jogos inv�lidos � maior que zero
        if (count($jogos_invalidos) > 0):
            //retorna json com array com todos os jogos inv�lidos
            return response()->json($jogos_invalidos);
        endif;
        //Instancia uma aposta
        $aposta = new \App\Aposta($request->all());
        //Passa id do usu�rio respons�vel pela aposta
        $aposta->users_id = $user->id;
        //Cria um cole��o de palpites a partir do json (Array) passado
        $palpites = collect(json_decode($request->get('palpites'), true))->collapse()->all();
        //Cria contador para cole��o de palpites
        $cont = 0;
        //Intera sobre jogos
        foreach ($jogos as $value):
            //Busca jogo pelo id
            $jogo = Jogo::find($value)->first();
            $text = $palpites[$cont++];
            $palpite ['palpite'] = $jogo->$text;
            $palpite ['tpalpite'] = $text;
            //Incluir valida��o de palpite
            $aposta->save();
            $aposta->jogo()->attach($value, $palpite);
        endforeach;
        //Retorna json com informa��o de que a aposta foi feita
        return response()->json(['status' => 'Aposta feita']);
    }

    /** M�todo que verifica se usu�rio possui alguma restri��o
     * @param $user \App\User usu�rio a ser verificado
     * @return array|null informa��o de problema do usu�rio ou null caso usu�rio esteja apto
     */
    private function verificarUsuario($user)
    {
        //Verificar se usu�rio existe
        if (is_null($user)):
            //Retorna status de usu�rio inexistente
            return ['status' => 'Inexistente'];
        endif;
        //Verificar se usu�rio n�o est� ativo
        if (!$user->ativo):
            //Retorna status de usu�rio inativo
            return ['status' => 'Inativo'];
        endif;
        //Retorn null
        return null;
    }

    /** M�todo que verifica se jogos est�o v�lidos para realiza��o de aposta
     * @param $jogos mixed de jogas para validar
     * @return array lista de jogos inv�lidos
     */
    private function validarJogos($jogos)
    {
        //Cria array para armazenar jogos que n�o podem receber aposta
        $jogos_invalidos = Array();
        //Realiza intera��o em todos os jogos
        foreach ($jogos as $valor):
            //Busca jogo pelo id (valor)
            $jogo = Jogo::find($valor)->first();
            //Verificar hor�rio
            if ($jogo == null || $jogo->data < Carbon::now()->subMinute(5)):
                //Se passou do hor�rio para apostar coloca o joga no array
                $jogos_invalidos[] = $jogo;
            endif;
        endforeach;
        //retorna o array com jogos que n�o podem ser feita aposta
        return $jogos_invalidos;
    }

    /** M�todo que c�lcula o valor a ser recebido pelas apostas feitas
     * @param $codigo_seguranca string c�digo que identifica o usu�rio
     * @return \Illuminate\Http\JsonResponse json com resultado da opera��o
     */
    public function ganhosApostas($codigo_seguranca)
    {
        //Defini��o de porcentagem por meio de constante
        $porcentagem = config('constantes.porcentagem') / 100;
        //Busca o usu�rio pelo c�digo de seguran�a
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        //Verifica restri��o usu�rio
        $resposta = $this->verificarUsuario($user);
        //Se retornou restri��o
        if (!is_null($resposta)):
            //Retorna json com restri��o encontrada
            return response()->json($resposta);
        endif;
        //Busca as apostas recentes (�ltimos 7 dias) feitas pelo usu�rio
        $total = Aposta::recentes($user->id)->sum('valor_aposta');
        //Calcula o valor a ser recebido pelo usu�rio
        $ganho = $total * $porcentagem;
        //Retorna o total de aposta e o valor que o usu�rio dever� receber
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