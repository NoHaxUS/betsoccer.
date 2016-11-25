<?php

    /**
     * Retorna data e hora.
     *
     * @param   bool    $hora   Se vai retornar hora também
     * @return  string
     */

    function toHora($data)
    {

       $datetime = date_create($data);
       return date_format($datetime,"H:i");
   }
   function toData($data)
   {

       $datetime = date_create($data);
       return date_format($datetime,"Y-m-d");
   }

   function campsHora($hora,$jogos) 
   {
    $aux = [];
            //Inteira pelos jogos quem vem do Web service
    foreach ($jogos as $j) {
                //verifica se alum jogo.data e igual a data passada por paramentro
        if ($hora == toData($j->data)) {
                    //vefica se esse se o campeonato não ja foi inserido no vertor
            if (!in_array($j->campeonato->descricao_campeonato,$aux)) {
                        //Por fim insere no array o campeonato
                array_push($aux,$j->campeonato->descricao_campeonato);
            }

        }
    }

    return $aux;
}