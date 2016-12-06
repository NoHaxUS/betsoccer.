<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Hashids\Hashids;
use DB;

class JogoController extends Controller
{


    public function __construct()
    {
        //$this->middleware('auth');

    }

    public function index()
    {

        //buscando todas as informacoes dos times
        //$datas = DB::select('select DISTINCT  descricao_campeonato AS dataDay , campeonatos_id from jogos');
        //dd($datas);
        $jogos = \App\Jogo::with('campeonato')->get();
        $datas = $this->arrayDatas($jogos);
        $campeonatos = $this->arrayCamps($jogos);

        return view('jogo.index', compact('jogos', 'campeonatos', 'datas'));
    }

    public function arrayDatas($jogos)
    {
        $datas = [];
        foreach ($jogos as $key => $jogo) {
            if ($key == 0) {
                array_push($datas, toData($jogo->data));
            } elseif (!in_array(toData($jogo->data), $datas)) {
                array_push($datas, toData($jogo->data));
            }
        }
        return $datas;
    }

    public function arrayCamps($jogos)
    {
        $camps = [];
        foreach ($jogos as $key => $jogo) {
            if ($key == 0) {
                array_push($camps, $jogo->campeonato->descricao_campeonato);
            } elseif (!in_array($jogo->campeonato->descricao_campeonato, $camps)) {
                array_push($camps, $jogo->campeonato->descricao_campeonato);
            }
        }
        return $camps;
    }

    public function cadastrar()
    {
        $campeonatos = \App\Campeonato::all();
        $times = \App\Time::all();
        return view('jogo.cadastrar', compact('campeonatos', 'datas', 'times'));
    }

    public function salvar(\App\Http\Requests\JogoRequest $request)
    {
        //dd($request);
        $jogo = \App\Jogo::create($request->all());
        $hashids = new Hashids('betsoccer', 5);
        $jogo->codigo = $hashids->encode($jogo->id);
        $jogo->save();
        $time = [];
        $time [] = $request->get('time_id');
        $time [] = $request->get('timef_id');
        $jogo->time()->attach($time);

        \Session::flash('flash_message', [
            'msg' => "Cadastro do Jogo realizado com sucesso!!!",
            'class' => "alert-success"
        ]);

        return redirect()->route('jogo.cadastrar');

    }

    public function allJogosPlacar()
    {
        $jogos = \App\Jogo::with('time', 'campeonato')
            ->where('data', '<', Carbon::now())
            ->where('r_casa', '=', null)
            ->where('r_fora', '=', null)
            ->get();
        return view('jogo.resultado', compact('jogos'));
    }

    public function addPlacar(Request $request)
    {
        $jogo = [];
        $jogo = $request->get('jogo');
        foreach ($jogo as $id) {
            $jogo = \App\Jogo::find($id);
            $jogo->r_casa = $request->get("r_casa" . $id);
            $jogo->r_fora = $request->get("r_fora" . $id);
            $jogo->save();
        }
        \Session::flash('flash_message', [
            'msg' => "Placares Adicionados Com Sucesso!!!",
            'class' => "alert-success"
        ]);
        return redirect()->route('jogo.index');
    }

    public function editar($id)
    {
        $jogo = \App\Jogo::find($id);
        $campeonatos = \App\Campeonato::all();
        $times = \App\Time::all();
        if (!$jogo) {
            \Session::flash('flash_message', [
                'msg' => "Não existe esse jogo cadastrado!!! Deseja cadastrar um novo Jogo?",
                'class' => "alert-danger"
            ]);
            return redirect()->route('jogo.cadastrar');
        }
        return view('jogo.editar', compact('jogo', 'campeonatos', 'datas', 'times'));
    }

    public function atualizar(\App\Http\Requests\JogoRequest $request, $id)
    {
        $jogo = \App\Jogo::find($id);
        $jogo->update($request->all());
        $timeOld [] = $jogo->time->get(0)['id'];
        $timeOld [] = $jogo->time->get(1)['id'];
        $jogo->time()->detach($timeOld);
        $time [] = $request->get('time_id');
        $time [] = $request->get('timef_id');
        $jogo->time()->attach($time);
        $jogo->save();

        \Session::flash('flash_message', [
            'msg' => "Jogo atualizado com sucesso!!!",
            'class' => "alert-success"
        ]);
        return redirect()->route('jogo.index');

    }

    public function atiDes($id)
    {
        $jogo = \App\Jogo::find($id);
        $boolean = $jogo->ativo;
        $jogo->ativo = !$boolean;
        $jogo->save();
        return redirect()->route('jogo.index');
    }

    public function deletar($id)
    {
        $jogo = \App\Jogo::find($id);
        $time = [];
        $time [] = $jogo->time->get(0);
        $time [] = $jogo->time->get(1);
        /*if($jogo->deletarTime()){
                      \Session::flash('flash_message',[
                  'msg'=>"Registro não pode ser deletado!!!",
                  'class'=>"alert-danger"
                  ]);
                  return redirect()->route('time.index');
                  }
          */
        $jogo->time()->detach($time);
        $jogo->delete();

        \Session::flash('flash_message', [
            'msg' => "Jogo apagado com sucesso!!!",
            'class' => "alert-danger"
        ]);
        return redirect()->route('jogo.index');

    }

    /** Método que realiza estatistica de palpites de jogo
     * @param $id int identificador do jogo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function totalPalpites($id)
    {
        $jogo = \App\Jogo::find($id);                               //Busca jogo pelo id passado
        if (is_null($jogo)):                                        //Verifica se jogo é nulo
            return redirect()->back();                              //Redireciona a página anterior
        endif;
        $palpites = $this->calcularPalpites($jogo->apostas);        //realiza calculo de palpites das apostas
        return view('jogo.palpites', compact('jogo', 'palpites'));  //Retorna a view de exibição passando jogo e array de palpites
    }

    /** Método que busca jogos com maior número de apostas
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maisApostados()
    {
        $resultado = DB::table('aposta_jogo')                           //Realiza consulta na tabela aposta_jogo
        ->select(DB::raw('count(*) as qtd, jogos_id as jogo'))          //Seleciona a quantidade e jogos_id
        ->groupBy('jogos_id')                                           //Agrupa por jogos_id
        ->orderBy('qtd', 'desc')                                        //Ordena de forma decrescente pela quantidade
        ->take(config('constantes.jogos_mais_apostados'))               //Pega os primeiro de acordo com quantidade definida
        ->get();
        $palpites = array();                                            //Cria array para armazenar os palpites
        $jogos = array();                                               //Cria array para armazenar os jogos
        foreach ($resultado as $item):                                  //Percorre a lista de resultado
            $jogo = \App\Jogo::find($item->jogo);                       //Busca jogo pelo id
            $palpites[] = $this->calcularPalpites($jogo->apostas);      //Solicita cálculo de de palpites e armazena no array
            $jogos[] = $jogo;                                           //Armazena o jogo no array
        endforeach;
        return view('jogo.maisapostados', compact('jogos', 'palpites'));//Retorna view para exibição
    }

    /** Método que cálcula palpites de apostas
     * @param $apostas \Illuminate\Support\Collection lista de apostas
     * @return array relação de palpites
     */
    private function calcularPalpites($apostas)
    {
        $palpites = array();                                //Cria array para guardar dados de palpites
        foreach ($apostas as $aposta):                      //Itera pelas apostas
            $indice = $aposta->pivot->tpalpite;             //Cria indice com texto do palpite
            if (array_key_exists($indice, $palpites)):      //Verifica se índice já existe no array
                $palpites[$indice]['qtd_' . $indice]++;     //Incrementa quantidade de palpites
                /*Calcula o valor do prêmio para o palpite com base no valor da aposta e do palpite
                adicionando ao que consta no array*/
                $palpites[$indice]['total_' . $indice] += $aposta->pivot->palpite * $aposta->valor_aposta;
            else:
                $palpites[$indice]['qtd_' . $indice] = 1;   //Atribui 1 para a quantidade de palpites
                //Calcula o valor do prêmio para o palpite com base no valor da aposta e do palpite
                $palpites[$indice]['total_' . $indice] = $aposta->pivot->palpite * $aposta->valor_aposta;
            endif;
        endforeach;
        return $palpites;
    }
}