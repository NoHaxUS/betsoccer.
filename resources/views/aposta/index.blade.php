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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Time Casa</th>
                                <th>Time Fora</th>
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
                             @foreach($jogos as $jo)
                              <tr>
                                <td scope="row">{{ $jo->id }}</td>
                                <td>{{ $jo->horario }}</td>
                                <td>{{ $jo->time_casa }}</td>
                                <td>{{ $jo->time_fora }}</td>
                                <td>{{ $jo->valor_casa }}</td>
                                <td>{{ $jo->valor_empate }}</td>
                                <td>{{ $jo->valor_fora }}</td>
                                <td>{{ $jo->valor_1_2 }}</td>
                                <td>{{ $jo->valor_dupla }}</td>
                                <td>{{ $jo->valor_ambas }}</td>
                                <td>{{ $jo->max_gol_2 }}</td>
                                <td>{{ $jo->min_gol_3 }}</td>                              
                                <td>{{ $jo->campeonato_id }}</td>                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
