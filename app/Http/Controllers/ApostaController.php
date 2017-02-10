<?php

namespace App\Http\Controllers;
use App\Http\Hespers\ApostaHelper;
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
        $this->middleware('auth');
    }

    public function index()
    {
        $results = DB::select('select DISTINCT  CAST(data AS date) AS dataS , campeonatos_id from jogos order by data');
        $jogos = \App\Jogo::with('time', 'campeonato')->get();
        $campeonatos = \App\Campeonato::all();
        return view('aposta.index', compact('jogos', 'campeonatos', 'results'));
    }
    public function apostaDel($codigo){
        $aposta = Aposta::BuscarPorAtributo('codigo',$codigo)->first();
        foreach ($aposta->jogo as $j) {
           $aposta->jogo()->detach($j->id);
       }
       dd($aposta->delete());
   }
   public function apostaDelU(){
    $apostas = Aposta::BuscarPorAtributo('users_id',14);
    foreach ($apostas as $aposta) {
        foreach ($aposta->jogo as $j) {
           $aposta->jogo()->detach($j->id);
       }
       $aposta->delete();
   }
   dd(true);
}

public function listaAposta()
{
    $apostaWins = [];
    $total = [];
    $apostas = \App\Aposta::with('user')
    ->where('pago', false)
    ->where('ativo', true)
    ->where('users_id', '<>', 0)
    ->get();

    $temp = ApostaHelper::calcRetorno($apostas);

    foreach ($apostas as $key => $aposta) {
        if (ApostaHelper::apostasWins($aposta)) {
            array_push($apostaWins, $aposta);
            array_push($total, $temp[$key]);
        }
    }
        // dd($apostaWins);
    return view('aposta.wins', compact('apostaWins', 'total'));
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

public function resumoAposta()
{
    $apostas = Aposta::with(['jogo', 'user'])
    ->where('pago', false)
    ->where('ativo', true)
    ->where('users_id', '<>', 0)
    ->get();
    $apostasPagas = Aposta::with(['jogo', 'user'])
    ->where('pago', true)
    ->where('ativo', true)
    ->where('users_id', '<>', 0)
    ->get();
    $users = DB::table('users')->select('id', 'name')->get();
    $premiacao_possivel = 0;
    $premios = ApostaHelper::calcRetorno($apostas);
    $premiosPago = ApostaHelper::calcRetorno($apostasPagas);
    $premiacao_possivel += array_sum(ApostaHelper::calcRetorno(ApostaHelper::removerFechadas($apostas)));
    $receber_cambistas = $this->ganhosApostasTodosCambistas($apostas);
    $ganhosRecebidos = $this->ganhosApostasTodosCambistas($apostasPagas); 
    $apostas = ApostaHelper::removerFechadas($apostas);
        //Lista de apostas é passada para a view
    return view('aposta.allapostas', compact('users', 'receber_cambistas','ganhosRecebidos','apostas', 'premios', 'premiacao_possivel', 'premiosPago'));
}

public function apostaCambista(Request $request)
{
    $id = $request->get('cambista');
    $cambista = \App\User::find($id);
    $apostas = Aposta::with(['jogo'])
    ->where('users_id', '=', $id)
    ->where('pago', false)
    ->orderBy('created_at','desc')
    ->get();
    $apostasPagas = Aposta::with(['jogo'])
    ->where('pago', '=', true)
    ->where('users_id', '=', $id)
    ->orderBy('created_at','desc')
    ->get();
    $users = DB::table('users')->select('id', 'name')->get();
    $premiacao_possivel = 0;
    $premios = ApostaHelper::calcRetorno($apostas);
    $premiosPago = ApostaHelper::calcRetorno($apostasPagas);
    $premiacao_possivel += array_sum(ApostaHelper::calcRetorno(ApostaHelper::removerFechadas($apostas)));
    $receber_cambista = $this->ganhosApostasPorCambista($cambista,$apostas);
    $ganhosRecebidos = $this->ganhosApostasPorCambista($cambista,$apostasPagas);
    return view('aposta.apostaCambista', compact('users','cambista', 'apostas', 'receber_cambista','ganhosRecebidos', 'premios', 'premiacao_possivel', 'apostasPagas', 'premiosPago'));
}
     /** Método que retorna o valor a ser recebido pelas apostas feitas,
     * exceto as em aberto (com jogos não concluídos)
     * @param $codigo_seguranca string código que identifica o usuário
     * @return \Illuminate\Http\JsonResponse json com resultado da operação
     */
     public function ganhosApostasPorCambista($user,$apostas)
     {        
        $dados['com_abertas'] = ApostaHelper::dadosGanhos($user,$apostas);    //Formata dados de todas as apostas
        //Formata dados de apostas sem as abertas
        $dados['sem_abertas'] = ApostaHelper::dadosGanhos($user, ApostaHelper::removerAbertas($apostas));
        return $dados;                                       //Retorna Array com dados

    }
    /** Método que retorna o valor a ser recebido pelas apostas feitas,
     * exceto as em aberto (com jogos não concluídos)
     * @param $codigo_seguranca string código que identifica o usuário
     * @return \Illuminate\Http\JsonResponse json com resultado da operação
     */
    public function ganhosApostasTodosCambistas($apostas)
    {       
        $dados['com_abertas'] = ApostaHelper::calcularGanho($apostas);    //Formata dados de todas as apostas
        //Formata dados de apostas sem as abertas
        $dados['sem_abertas'] = ApostaHelper::calcularGanho(ApostaHelper::removerAbertas($apostas));
        return $dados;                                       //Retorna json com dados

    }
}