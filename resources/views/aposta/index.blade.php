@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li class="active">Aposta</li>
                </ol>
                 <div class="panel-body">
                 <p>
                        <a class="btn btn-info" href="{{ route('aposta.cadastrar') }}">Apostar</a>
                 </p>
                   <div class="table-responsive"> 
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>T.Casa</th>
                                <th>T.Fora</th>
                                <th>Casa</th>
                                <th>Emp</th>
                                <th>Fora</th>
                                <th>Gol</th>
                                <th>Dupla</th>
                                <th>Ambas</th>
                                <th>-2.5</th>
                                <th>+2.5</th>
                                <th>Campeonato</th>                                
                            </tr>
                        </thead>
                        <tbody>
                        <form action="{{ route('aposta.salvar')}}" method="post">
                             @foreach($jogos as $jo)
                                                         
                            
                              <tr>
                                <td scope="row">
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="lista_jogos" id="blankCheckbox" value=" {{ $jo->id }}" aria-label="...">
                                     {{ $jo->id }}
                                  </label>
                                </div>

                               

                                </td>

                                <td>{{ \App\Horario::find($jo->horarios_id)->data}}</td>
                                <td>{{ $jo->time->get(0)['descricao_time'] }}</td>
                                <td>{{ $jo->time->get(1)['descricao_time'] }}</td>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpitepalpite" value="{{$jo->valor_casa}}" aria-label="...">
                                            {{ $jo->valor_casa }}
                                        </label>
                                    </div>
                                </td>
                                <td>   
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->valor_empate}}" aria-label="...">
                                            {{ $jo->valor_empate}}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value=" {{ $jo->valor_fora}}" aria-label="...">
                                            {{ $jo->valor_fora}}
                                        </label>
                                    </div>                                
                                </td>                               
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->valor_1_2}}" aria-label="...">
                                                {{ $jo->valor_1_2}}
                                        </label>
                                    </div>  
                                
                                </td>
                                <td>
                                 <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->valor_dupla}}" aria-label="...">
                                                {{ $jo->valor_dupla }}
                                        </label>
                                    </div>  
                                </td>
                                <td>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->valor_ambas }}" aria-label="...">
                                                {{ $jo->ambas_gol }}
                                        </label>
                                    </div>    
                                </td>
                                <td>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->max_gol_2 }}" aria-label="...">
                                               {{ $jo->max_gol_2 }}
                                        </label>
                                    </div> 
                                
                                </td>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->min_gol_3 }}" aria-label="...">
                                               {{ $jo->min_gol_3 }}
                                        </label>
                                    </div> 
                                </td>                              
                                <td>{{ \App\Campeonato::find($jo->campeonatos_id)->descricao_campeonato}}</td>                                
                            </tr>
                            
                            @endforeach
                         </form>
                        </tbody>
                    </table>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
