<?php
namespace App\Http\Hespers;

use Carbon\Carbon;
use App\Aposta;

class ApostaHelper
{

    /** M�todo que verifica se usu�rio possui alguma restri��o
     * @param $user \App\User usu�rio a ser verificado
     * @return array|null informa��o de problema do usu�rio ou null caso usu�rio esteja apto
     */
    public static function verificarUsuario($user)
    {
        if (is_null($user)):                                    //Verificar se usu�rio existe
            return ['status' => 'Inexistente', 'erro' => 400];  //Retorna status de usu�rio inexistente
        endif;
        if (!$user->ativo):                                     //Verificar se usu�rio n�o est� ativo
            return ['status' => 'Inativo', 'erro' => 401];      //Retorna status de usu�rio inativo
        endif;
        return null;                                            //Retorna null
    }

    /** M�todo que verifica se jogos est�o v�lidos para realiza��o de aposta
     * @param $jogos mixed de jogas para validar
     * @return array|string lista de jogos inv�lidos ou informa��o de n�mero m�nimo de jogos
     */
    public static function verificarJogos($jogos)
    {
        if (count($jogos) < 2):                     //Verifica se quantidade de jogos � menor que 2
            return ['status' => "Minimo 2 jogos",
                'erro' => 402];                 //Retorna mensagem e erro
        endif;
        $jogos_invalidos = Array();                 //Cria array para armazenar jogos que n�o podem receber aposta
        foreach ($jogos as $valor):                 //Realiza intera��o em todos os jogos
            $jogo = \App\Jogo::find($valor);             //Busca jogo pelo id (valor)
            //Verificar  jogo � nulo ou se data e hora do jogo � menor hor�rio que a data atual menos 5 minutos
            if ($jogo == null || $jogo->data < Carbon::now()->addMinute(5)):
                $jogos_invalidos[] = $jogo;         //Passou jogo para array
                $jogos_invalidos['erro'] = 403;     //Passa c�digo de erro para array
            endif;
        endforeach;
        return $jogos_invalidos;                    //retorna o array com jogos que n�o podem ser feita aposta
    }

    /** M�todo que verifica validade de palpites
     * @param $palpites \Illuminate\Support\Collection com palpites a serem verificados
     * @return array palpites inv�lidos
     */
    public static function verificarPalpites($palpites)
    {
        $palpites_invalidos = Array();                      //Cria array para armazenar palpites inv�lidos
        foreach ($palpites as $palpite):                    //Interra sobre palpites
            $jogo = \App\Jogo::find($palpite['jogo_id']);   //Busca jogo pelo id
            if ($jogo->$palpite['tpalpite'] == 0):          //Verifica se valor do palpite no jogo � zero
                $palpites_invalidos[] = $palpite;           //Passa palpite inv�lido para array
            endif;
        endforeach;
        return $palpites_invalidos;                     //Retorna array com palpites inv�lidos
    }

    /** M�todo que formata dados de palpites de jogos em array
     * @param $jogos \Illuminate\Support\Collection jogos com respectivos palpites
     * @return array rela��o de palpites de cada jogo
     */
    public static function dadosPalpites($jogos)
    {
        $palpites = array();                                //Cria array para armazenar dados de palpites
        foreach ($jogos as $jogo):                          //Percorre cole��o de jogos
            $palpites[] = [
                'jogos_id' => $jogo->pivot->jogos_id,       //Passa id do jogo
                'palpite' => $jogo->pivot->palpite,         //Passa valor para o palpite
                'tpalpite' => $jogo->pivot->tpalpite        //Passa texto do palpite
            ];
        endforeach;
        return $palpites;                                   //Retorna array com dados de palpites
    }

    /** M�todo que formata dados de aposta em array
     * @param $aposta Aposta aposta cujos dados ser�o passados para array
     * @param float $ganho valor do ganho do cambista
     * @return array array com dados da aposta
     */
    public static function dadosAposta($aposta, $ganho = 0)
    {
        return [
            'codigo' => $aposta->codigo,                                            //C�digo
            'data' => $aposta->created_at,                                          //Data
            'apostador' => $aposta->nome_apostador,                                 //Nome do apostador
            'valor_apostado' => number_format($aposta->valor_aposta, 2, ',', '.'),  //Valor apostado
            'ativa' => (boolean)$aposta->ativo,                                     //Se ativa
            'ganho' => number_format($ganho, 2, ',', '.'),                          //Ganho do cambista
            'jogos' => self::dadosJogos($aposta->jogo)                             //Rela��o de jogos
        ];
    }


    /** M�todo que formata dados de jogos em array
     * @param $jogos array lista de jogos
     * @return array array com dados dos jogos
     */
    public static function dadosJogos($jogos)
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

    /**M�todo que formata os dados dos ganhos com as apostas
     * @param $user \App\User cambista
     * @param $apostas \Illuminate\Support\Collection lista de apostas
     * @return array lista de dados para serem retornados
     */
    public static function dadosGanhos($user, $apostas)
    {
        return ['codigo' => $user->codigo,              //Passa c�digo do cambista
            'cambista' => $user->name]             //Passa nome do cambista
        + self::calcularGanho($apostas);       //C�lcula ganhos da aposta
    }

    /** M�todo que formata os dados dos pr�mios
     * @param $user \App\User cambista
     * @param $apostas \Illuminate\Support\Collection lista de apostas
     * @return array lista de dados para serem retornados
     */
    public static function dadosPremios($user, $apostas)
    {
        return ['codigo' => $user->codigo,             //C�digo do cambista
            'cambista' => $user->name]             //Nome do cambista
        + self::detalharApostas($apostas);     //Dados detalhados das apostas
    }

    /** M�todo que calcula ganhos com apostas
     * @param $apostas \Illuminate\Support\Collection lista de apostas para c�lculo
     * @return array dados detalhados de ganhos de apostas
     */
    public static function calcularGanho($apostas)
    {
        $ganho_simples = 0;                                                     //Cria vari�vel para acumular comiss�o com apostas simples (2 jogos)
        $ganho_mediano = 0;                                                     //Cria vari�vel para acumular comiss�o com apostas medianas (3 jogos)
        $ganho_maximo = 0;                                                      //Cria vari�vel para acumular comiss�o com apostas m�ximas (mais de 2 jogos)
        $premiacao = 0;                                                         //Cria vari�vel para acumular valor de premia��o
        $qtd_jogos = 0;                                                         //Cria vari�vel para acumular quantidade de jogos
        foreach ($apostas as $aposta):                                          //Itera pela lista de apostas
            switch ($aposta->jogo()->count()) :                                 //Seleciona quantidade de jogos como par�metro
                case 2:
                    //C�lcula ganho para aposta com dois jogos
                    $ganho_simples += $aposta->valor_aposta * (config('constantes.porcentagem_simples') / 100);
                    break;
                case 3:
                    //C�lcula ganho para aposta com tr�s jogos
                    $ganho_mediano += $aposta->valor_aposta * (config('constantes.porcentagem_mediana') / 100);
                    break;
                default:
                    //C�lcula ganho para aposta com mais tr�s jogos
                    $ganho_maximo += $aposta->valor_aposta * (config('constantes.porcentagem_maxima') / 100);
                    break;
            endswitch;
            if (self::apostasWins($aposta)):                                    //Verifica se aposta foi vencedora
                $premiacao += self::calcularPremio($aposta);                    //soma a premia��o
            endif;
            $qtd_jogos += $aposta->jogo()->count();                             //Acrescenta quantidade de jogos a vari�vel
        endforeach;
        $ganho_total = $ganho_simples + $ganho_mediano + $ganho_maximo;           //Obt�m o valor total da comiss�o somando as comiss�es de aposta simples, mediana e m�xima
        $total_apostado = $apostas->sum('valor_aposta');                        //Soma total apostado
        $liquido = $total_apostado - $premiacao - $ganho_total;                 //Obt�m o valor liquido
        return ['qtd_apostas' => $apostas->count(),                             //Quantidade de apostas
            'qtd_jogos' => $qtd_jogos,                                          //Quantidade de jogos
            'comissao_simples' => number_format($ganho_simples, 2, ',', '.'),   //Comiss�o de apostas simples
            'comissao_mediana' => number_format($ganho_mediano, 2, ',', '.'),   //Comiss�o de apostas medianas
            'comissao_maxima' => number_format($ganho_maximo, 2, ',', '.'),     //Comiss�o de apostas m�ximas
            'comissao_total' => number_format($ganho_total, 2, ',', '.'),       //Total de comiss�o
            'total_apostado' => number_format($total_apostado, 2, ',', '.'),    //Total apostado
            'total_premiacao' => number_format($premiacao, 2, ',', '.'),        //Total de premia��o
            'liquido' => number_format($liquido, 2, ',', '.')];                 //Valor l�quido
    }

    /** M�todo que calcula valor do pr�mio de uma aposta vencedora
     * @param $aposta Aposta vencedora
     * @return mixed valor do pr�mio
     */
    public static function calcularPremio($aposta)
    {
        $premio = $aposta->valor_aposta;            //Passa valor da aposta para vari�vel pr�mio
        foreach ($aposta->jogo as $jogo):           //Percorre rela��o de jogos da aposta
            if ($jogo->ativo):
                $premio *= $jogo->pivot->palpite;   //Multiplica o valor do pr�mio pelo do palpite
            endif;
        endforeach;
        return $premio;                             //Retorna valor do pr�mio
    }

    /**M�todo que remove dados desnecess�rios de jogos
     * @param $jogos array de jogos para remover dados
     */
    public static function removerDadosDeJogos(&$jogos)
    {
        foreach ($jogos as &$jogo):                 //Percorre array de jogos
            foreach ($jogo['times'] as &$time):     //Percorre array de times
                unset($time['created_at']);         //Remove campo created_at do time
                unset($time['updated_at']);         //Remove campo updated_at do time
                unset($time['pivot']);              //Remove dados de pivot do time
            endforeach;
        endforeach;
    }

    /** M�todo que remove apostas em aberto da lista de apostas passada
     * @param $apostas \Illuminate\Support\Collection com rela��o de apostas
     * @return mixed rela��o de apostas sem as em aberto
     */
    public static function removerAbertas($apostas)
    {
        //Chama m�todo para remo��o de apostas, passando fun��o para realizar essa tarefa
        $apostas = $apostas->reject(function ($aposta) {
            foreach ($aposta->jogo as $jogo):            //Percorre rela��o de jogos da aposta
                //Verifica se jogo est� ativo e caso, positivo, ee resultado de casa ou de fora est� nulo
                if ($jogo->ativo && (is_null($jogo->r_casa) || is_null($jogo->r_fora))):
                    return true;                        //Retorna verdadeiro
                endif;
            endforeach;
        });
        return $apostas;                                //Retorna cole��o de apostas sem as abertas
    }

    /** M�todo que remove apostas finalizadas da lista de apostas passada
     * @param $apostas \Illuminate\Support\Collection com rela��o de apostas
     * @return mixed rela��o de apostas sem as em aberto
     */
    public static function removerFechadas($apostas)
    {
        //Chama m�todo para remo��o de apostas, passando fun��o para realizar essa tarefa
        $apostas = $apostas->reject(function ($aposta) {
            foreach ($aposta->jogo as $jogo):            //Percorre rela��o de jogos da aposta
                //Verifica se jogo est� ativo e, caso esteja, se resultado de casa ou de fora est� nulo
                if ($jogo->ativo && (!is_null($jogo->r_casa) || !is_null($jogo->r_fora))):
                    return true;                        //Retorna verdadeiro
                endif;
            endforeach;
        });
        return $apostas;                                //Retorna cole��o de apostas sem as abertas
    }

    /** M�todo que realiza detalhamento de apostas
     * @param $apostas \Illuminate\Support\Collection rela��o de apostas a serem detalhadas
     * @return array lista com dados detalhados das apostas
     */
    public static function detalharApostas($apostas)
    {
        $premiacao_total = 0;                                           //Cria vari�vel para acumular premia��o total
        $ganho_total = 0;                                               //Cria vari�vel para acumular quantidade de jogos
        $premiacao_paga = 0;                                            //Cria vari�vel para acumular premia��o paga
        $premiacao_nao_paga = 0;                                        //Cria vari�vel para acumular premia��o n�o paga
        $lista_apostas = Array();                                       //Cria array para armazenar lista de apostas
        $apostas_vencedoras = Array();                                  //Cria array para armazenar lista de apostas vencedoras
        foreach ($apostas as $aposta):                                  //Itera pela lista de apostas
            switch ($aposta->jogo()->count()) :                         //Seleciona a quantidade de jogos como par�metro
                case 2:
                    //Gera valor para c�lculo de ganho para aposta com dois jogos
                    $porcentagem = config('constantes.porcentagem_simples') / 100;
                    break;
                case 3:
                    //Gera valor para c�lculo de ganho para aposta com tr�s jogos
                    $porcentagem = config('constantes.porcentagem_mediana') / 100;
                    break;
                default:
                    //Gera valor para c�lculo de ganho para aposta com mais tr�s jogos
                    $porcentagem = config('constantes.porcentagem_maxima') / 100;
                    break;
            endswitch;
            $ganho_aposta = $aposta->valor_aposta * $porcentagem;               //Calcula o ganho por cada aposta
            $premiacao_aposta = self::calcularPremio($aposta);                 //Calcula o poss�vel pr�mio
            $dados_aposta = self::dadosAposta($aposta, $ganho_aposta);         //Gera dados de aposta
            //Passa para array dados da aposta mais retorno poss�vel
            $lista_apostas [] = $dados_aposta + ['retorno_possivel' => number_format($premiacao_aposta, 2, ',', '.')];
            if (self::apostasWins($aposta)):                                   //Se aposta for vencedora
                $apostas_vencedoras[] = $dados_aposta
                    + ['premio' => number_format($premiacao_aposta, 2, ',', '.'),
                        'paga' => $aposta->pago];                               //Passa dados de aposta mais premia��o e informa��o se j� pago
                if ($aposta->pago):                                             //Verifica se aposta foi paga (o pr�mio)
                    $premiacao_paga += $premiacao_aposta;                       //Soma valor a de premia��es pagas
                else:                                                           //Se n�o foi paga
                    $premiacao_nao_paga += $premiacao_aposta;                   //Soma valor a premia��es n�o pagas
                endif;
                $premiacao_total += $premiacao_aposta;                          //Acrescenta a premia��o da aposta a premia��o total
            endif;
            $ganho_total += $ganho_aposta;                                      //Soma o ganho de cada aposta para formar o montante
        endforeach;
        return [
            'apostas_vencedoras' => $apostas_vencedoras,                        //Rela��o de apostas vencedoras
            'total_premiacao' => number_format($premiacao_total, 2, ',', '.'),  //Total de premia��o
            'apostas' => $lista_apostas,                                        //Lista de apostas
        ];
    }

    /**
     * M�todo que verifica se uma aposta foi vencedora ou n�o pega a lista de jogos de uma aposta
     * e verificar o resultados do jogo em rela�ao aos palpites do apostador
     * Caso ele acerte todos os palpites retorna um true.
     * @param $apostas Aposta
     * @return Boolean true or false
     */
    public static function apostasWins($aposta)
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
        return $i == count($aposta->jogo);
    }

    /**
     * M�todo que c�lcula o valor a ser pago com premios por cada aposta
     * Passada por paramentro e reotnar um array com a lista dos premios
     * @param $apostas Aposta Listas de aposta
     * @return Array com cole��o dos premios das apotas passadas.
     */
    public static function calcRetorno($apostas)
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

    public static function receberDoCambista($apostas)
    {
        $valor_aposta = 0;
        foreach ($apostas as $key => $aposta) {
            $valor_aposta += $aposta->valor_aposta;
        }
        return $valor_aposta;
    }
}