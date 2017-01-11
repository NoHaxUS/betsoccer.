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
            ->where('users_id', '<>', 0)
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
        $total = 0;
        $totalPago = 0;
        $premios = $this->calcRetorno($apostas);
        $premiosPago = $this->calcRetorno($apostasPagas);
        $totalPago += array_sum($premiosPago);
        $total += array_sum($premios);
        $receber = $this->receberDoCambista($apostas);
        $recebido = $this->receberDoCambista($apostasPagas);
        //Lista de apostas é passada para a view
        return view('aposta.allapostas', compact('users', 'apostas', 'premios', 'total','receber','recebido', 'apostasPagas', 'premiosPago', 'totalPago'));
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
            return response()->json(['status' => 'Credenciais Insuficientes', 'erro' => 501], 501);          //Retorna json com restrição encontrada
        }
        $apostas = Aposta::recentes($cambista);
        $apostas = $this->removerAbertas($apostas);
        foreach ($apostas as $aposta) {
                $aposta->pago = true;
                $aposta->save();
            }    
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
        $cambista = \App\User::find($id);
        $apostas = Aposta::with(['jogo'])
            ->where('users_id', '=', $id)
            ->where('pago', false)
            ->orderBy('created_at','desc')
            ->get();
        $apostasPagas = Aposta::with(['jogo'])
            ->where('pago', '=', true)
            ->orderBy('created_at','desc')
            ->get();
        $users = DB::table('users')->select('id', 'name')->get();
        $total = 0;
        $totalPago = 0;
        $premios = $this->calcRetorno($apostas);
        $premiosPago = $this->calcRetorno($apostasPagas);
        $totalPago += array_sum($premiosPago);
        $total += array_sum($premios);
        $receber = $this->receberDoCambista($apostas);
        $recebido = $this->receberDoCambista($apostasPagas);

        return view('aposta.apostaCambista', compact('users','cambista', 'apostas', 'receber', 'premios', 'total', 'apostasPagas', 'premiosPago', 'recebido'));
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
    /** Método que remove apostas em aberto da lista de apostas passada
     * @param $apostas \Illuminate\Support\Collection com relação de apostas
     * @return mixed relação de apostas sem as em aberto
     */
    private function removerAbertas($apostas){
        //Chama método para remoção de apostas, passando função para realizar essa tarefa
        $apostas = $apostas->reject(function($aposta){
            foreach($aposta->jogo as $jogo):            //Percorre relação de jogos da aposta
                //Se resultado de casa ou de fora for nulo
                if(is_null($jogo->r_casa)  || is_null($jogo->r_fora)):
                    return true;                        //Retorna verdadeiro
                endif;
            endforeach;
        });
        return $apostas;                                //Retorna coleção de apostas sem as abertas
    }
}