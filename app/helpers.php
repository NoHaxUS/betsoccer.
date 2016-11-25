<?php


    /**
     * Converte DateTime em Time.
     *
     * @param   DateTime    $data
     * @return  Hora H:i
     */

    function toHora($data)
    {

     $datetime = date_create($data);
     return date_format($datetime,"H:i");
   }
   
    /**
     * Converte DateTime em Date.
     *
     * @param   DataTime    $data
     * @return  Date Y-m-d
     */
    function toData($data)
    {

     $datetime = date_create($data);
     return date_format($datetime,"Y-m-d");
   }

    /**
     * Pega os campeontatos que tenha relação com uma data
     * passada por paramentro e colocar em um array aux que será usado na view.
     * @param   DataTime    $data, Jogo    $jogos 
     * @return  Campeonatos[] aux;
     */
    function campsHora($data,$jogos) 
    {
      $aux = [];
            //Inteira pelos jogos passados por paramentro
      foreach ($jogos as $j) {
                //verifica se alum jogo->data é igual a data passada por paramentro
        if ($data == toData($j->data)) {
                    //vefica se o campeonato ja foi inserido no vertor aux
          if (!in_array($j->campeonato->descricao_campeonato,$aux)) {
                        //Por fim insere o campeonato no array 
            array_push($aux,$j->campeonato->descricao_campeonato);
          }

        }
      }

      return $aux;
    }