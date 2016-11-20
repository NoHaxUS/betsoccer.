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

    /**M�todo para realiza��o de aposta via Web Service
     * @param Request $request dados da aposta
     * @return \Illuminate\Http\JsonResponse resultado da opera��o
     */
    public function apostar(Request $request)
    {
        $user = \App\User::buscarPorCodigoSeguranca($request->codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                         //Verifica restri��o usu�rio
        if (!is_null($resposta)):                                           //Se retornou restri��o
            return response()->json($resposta, 400);                        //Retorna json com restri��o encontrada
        endif;
        $jogos_invalidos = $this->verificarJogos($request->jogo);           //Valida jogos
        if (count($jogos_invalidos) > 0):                                   //Verifica se quantidade de jogos inv�lidos � maior que zero
            return response()->json(
                ['jogos_invalidos' => $jogos_invalidos], 400);              //retorna json com array com todos os jogos inv�lidos
        endif;
        /*$palpites_invalidos = $this->verificarPalpites($palpites);        //Verifica se h� palpites inv�lidos
        if (count($palpites_invalidos) > 0):                                //Verifica se quantidade de palpites inv�lidos � maior que zero
            return response()->json(
                ['palpites_invalidos' => $palpites_invalidos]);             //retorna json com array com todos os palpites inv�lidos
        endif;*/
        return response()->json(
            ['aposta' => $this->registrarAposta($request, $user)]);         //Retorna json com a aposta feita
    }

    /** M�todo que verifica se usu�rio possui alguma restri��o
     * @param $user \App\User usu�rio a ser verificado
     * @return array|null informa��o de problema do usu�rio ou null caso usu�rio esteja apto
     */
    private function verificarUsuario($user)
    {
        if (is_null($user)):                        //Verificar se usu�rio existe
            return ['status' => 'Inexistente'];     //Retorna status de usu�rio inexistente
        endif;
        if (!$user->ativo):                         //Verificar se usu�rio n�o est� ativo
            return ['status' => 'Inativo'];         //Retorna status de usu�rio inativo
        endif;
        return null;                                //Retorn null
    }

    /** M�todo que verifica se jogos est�o v�lidos para realiza��o de aposta
     * @param $jogos mixed de jogas para validar
     * @return array lista de jogos inv�lidos
     */
    private function verificarJogos($jogos)
    {
        $jogos_invalidos = Array();                 //Cria array para armazenar jogos que n�o podem receber aposta
        foreach ($jogos as $valor):                 //Realiza intera��o em todos os jogos
            $jogo = Jogo::find($valor);             //Busca jogo pelo id (valor)
            //Verificar  jogo � nulo ou se data e hora do jogo � menor hor�rio que a data atual menos 5 minutos
            if ($jogo == null || (new Carbon($jogo->data)) < Carbon::now()->subMinute(5)):
                $jogos_invalidos[] = $jogo;         //Se passou do hor�rio para apostar coloca o joga no array
            endif;
        endforeach;
        return $jogos_invalidos;                    //retorna o array com jogos que n�o podem ser feita aposta
    }

    /** M�todo que verifica validade de palpites
     * @param $palpites \Illuminate\Support\Collection com palpites a serem verificados
     * @return array palpites inv�lidos
     */
    private function verificarPalpites($palpites)
    {
        $palpites_invalidos = Array();                  //Cria array para armazenar palpites inv�lidos
        foreach ($palpites as $palpite):                //Interra sobre palpites
            $jogo = Jogo::find($palpite['jogo_id']);    //Busca jogo pelo id
            if ($jogo->$palpite['tpalpite'] == 0):       //Verifica se valor do palpite no jogo � zero
                $palpites_invalidos[] = $palpite;       //Passa palpite inv�lido para array
            endif;
        endforeach;
        return $palpites_invalidos;                     //Retorna array com palpites inv�lidos
    }

    /** M�todo que registra aposta no sistema
     * @param Request $request dados para registro
     * @param \App\User $user usu�rio que respons�vel pela aposta
     * @param \Illuminate\Support\Collection $palpites array de palpites
     * @return Aposta aposta feita
     */
    private function registrarAposta(Request $request, \App\User $user)
    {
        $aposta = new \App\Aposta($request->all());                 //Instancia uma aposta
        $aposta->users_id = $user->id;                              //Passa id do usu�rio respons�vel pela aposta
        $aposta->save();                                            //Salva aposta
        for ($i = 0; $i < count($request->jogo); $i++):             //Criar itera��o com base no n�mero de jogos
            $palpite ['palpite'] = $request->valorPalpite[$i];      //Passa valor do palpite para array
            $palpite['tpalpite'] = $request->tpalpite[$i];          //Passa texto do palpite para arrayu
            $aposta->jogo()->attach($request->jogo[$i], $palpite);  //Relaciona aposta com jogo incluindo os dados de palpite
        endfor;
        return $aposta;                                             //Retorna a aposta
    }

    /** M�todo que c�lcula o valor a ser recebido pelas apostas feitas
     * @param $codigo_seguranca string c�digo que identifica o usu�rio
     * @return \Illuminate\Http\JsonResponse json com resultado da opera��o
     */
    public function ganhosApostas($codigo_seguranca)
    {
        $porcentagem = config('constantes.porcentagem') / 100;      //Defini��o de porcentagem por meio de constante
        //Busca o usu�rio pelo c�digo de seguran�a
        $user = \App\User::buscarPorCodigoSeguranca($codigo_seguranca)->first();
        $resposta = $this->verificarUsuario($user);                 //Verifica restri��o usu�rio
        if (!is_null($resposta)):                                   //Se retornou restri��o
            return response()->json($resposta);                     //Retorna json com restri��o encontrada
        endif;
        $apostas = Aposta::recentes($user->id);                     //Busca as apostas recentes feitas pelo usu�rio
        $total = $apostas->sum('valor_aposta');                     //Calcula o valor total
        $ganho_total = 0;                                           //Cria vari�vel para acumular o ganho de cada aposta
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
        //Lista de apostas � passada para a view
        return view('apostaJogo.index', compact('apostas'));
    }
}