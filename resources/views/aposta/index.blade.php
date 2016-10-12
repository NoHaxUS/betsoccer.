@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
               <ol class="breadcrumb panel-heading">
                <li><a href="{{ route('aposta.index') }}">Aposta</a></li>
                <li class="active">Aposta</li>
            </ol>

            <div class="panel-body">

                <form class="form-inline" action="{{ route('aposta.salvar')}}" method="post">
                    <div class="container">
                        <div class="row">
                            <div class="form-group">
                                <p>
                                    <button class="btn btn-success ">Apostar</button>
                                </p>
                            </div>   
                        </div>

                        <input class="form-control hide" type="number" name="users_id" value="{{Auth::user()->id}}">                 
                        <div class="form-group {{ $errors->has('nome_apostador') ? 'has-error' : ''}}">
                            <label for="nome_apostador">Nome</label>
                            <input type="text" class="form-control" name="nome_apostador" value="{{old('nome_apostador')}}" placeholder="Digite seu nome">
                            @if($errors->has('nome_apostador'))
                            <span class="help-block">
                                <strong> {{ $errors->first('nome_apostador') }} </strong>
                            </span>
                            @endif
                        </div>                        
                        <div class="form-group {{ $errors->has('valor_aposta') ? 'has-error' : ''}}">
                            <label class="sr-only" for="valor_aposta">Valor</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="number" class="form-control" value="{{old('valor_aposta')}}" name="valor_aposta" placeholder="Valor">                     
                                <div class="input-group-addon">.00</div>
                            </div>
                            @if($errors->has('valor_aposta'))
                            <span class="help-block">
                                <strong> {{ $errors->first('valor_aposta') }} </strong>
                            </span>
                            @endif
                        </div>     
                    </div> 
                    <br>

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
                                <th>-2.5</th>
                                <th>+2.5</th>
                                <th>Ambas</th>
                                <th>Campeonato</th>                                                        
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($jogos as $jo)                                            
                           {{ csrf_field() }}
                           @if (($jo->data > \Carbon\Carbon::now()->addMinutes(5)) AND ($jo->data < \Carbon\Carbon::now()->addDays(1)) AND ($jo->ativo == true))                       
                           <tr>
                            <td scope="row">
                                <div class="form-group">
                                    <div class="checkbox">
                                      <label>
                                        <input type="checkbox" name="jogo[]"  value="{{ $jo->id }}" aria-label="...">
                                        {{ $jo->id }}

                                    </label>
                                </div>
                            </div>
                        </td>
                        <td>{{ $jo->data}}</td>
                        <td>{{ $jo->time->get(0)['descricao_time'] }}</td>
                        <td>{{ $jo->time->get(1)['descricao_time'] }}</td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="palpite{{ $jo->id}}" id="palpite" value="{{ $jo->valor_casa}}" aria-label="...">
                                    {{ $jo->valor_casa}}
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
                                    <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->valor_fora}}" aria-label="...">
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
                                {{ $jo->valor_dupla}}
                            </label>
                        </div>  
                    </td>                
                    <td>
                        <div class="radio">
                            <label>
                                <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->max_gol_2 }}" aria-label="...">
                                {{ $jo->max_gol_2}}
                            </label>
                        </div> 
                    </td>
                    <td>
                        <div class="radio">
                            <label>
                                <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->min_gol_3 }}" aria-label="...">
                                {{ $jo->min_gol_3}}
                            </label>
                        </div> 

                    </td>
                    <td>
                        <div class="radio">
                            <label>
                                <input type="radio" name="palpite{{ $jo->id }}" id="palpite" value="{{ $jo->ambas_gol }}" aria-label="...">
                                {{ $jo->ambas_gol}}
                            </label>
                        </div>    
                    </td>                              
                    <td>{{ \App\Campeonato::find($jo->campeonatos_id)->descricao_campeonato}}</td>
                </tr>   
                @endif                         
                @endforeach                                                          
            </tbody>
        </table>
    </div> 

</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection