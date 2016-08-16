@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li class="active">Aposta</li>
                </ol>
                 <p>
                        <a class="btn btn-info" href="{{ route('aposta.cadastrar') }}">Apostar</a>
                 </p>

                <div class="panel-body">
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
                            
                              </label>
                            </div>
                              <tr>
                                <td scope="row">
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" id="blankCheckbox" value=" {{ $jo->id }}" aria-label="...">
                                     {{ $jo->id }}
                                  </label>
                                </div>

                               

                                </td>
                                <td>{{ \App\Horario::find($jo->horarios_id)->data}}</td>
                                <td>{{ \App\Time::find($jo->time_casa_id)->descricao_time }}</td>
                                <td>{{ \App\Time::find($jo->time_fora_id)->descricao_time }}</td>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{$jo->valor_casa}}" aria-label="...">
                                            {{ $jo->valor_casa }}
                                        </label>
                                    </div>
                                </td>
                                <td>   
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{ $jo->valor_empate}}" aria-label="...">
                                            {{ $jo->valor_empate}}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value=" {{ $jo->valor_fora}}" aria-label="...">
                                            {{ $jo->valor_fora}}
                                        </label>
                                    </div>                                
                                </td>                               
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{ $jo->valor_1_2}}" aria-label="...">
                                                {{ $jo->valor_1_2}}
                                        </label>
                                    </div>  
                                
                                </td>
                                <td>
                                 <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{ $jo->valor_dupla}}" aria-label="...">
                                                {{ $jo->valor_dupla }}
                                        </label>
                                    </div>  
                                </td>
                                <td>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{ $jo->valor_ambas }}" aria-label="...">
                                                {{ $jo->ambas_gol }}
                                        </label>
                                    </div>    
                                </td>
                                <td>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{ $jo->max_gol_2 }}" aria-label="...">
                                               {{ $jo->max_gol_2 }}
                                        </label>
                                    </div> 
                                
                                </td>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="aposta" id="aposta" value="{{ $jo->min_gol_3 }}" aria-label="...">
                                               {{ $jo->min_gol_3 }}
                                        </label>
                                    </div> 
                                </td>                              
                                <td>{{ $jo->campeonatos_id}}</td>                                
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
@endsection
