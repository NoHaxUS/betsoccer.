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
                   <div class="table-responsive"> 
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Horário</th>
                                <th>Time Casa</th>
                                <th>Time Fora</th>
                                <th>Valor Casa</th>
                                <th>Valor Emp</th>
                                <th>Valor Fora</th>
                                <th>Valor Gol</th>
                                <th>Valor Dupla</th>
                                <th>Campeonato</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($jogos as $jo)

                              <tr>
                                <td scope="row">{{ $jo->id }}</td>
                                <td>{{ $jo->horario }}</td>
                                <td>{{ $jo->timecasa }}</td>
                                <td>{{ $jo->timefora }}</td>
                                <td>{{ $jo->valorcasa }}</td>
                                <td>{{ $jo->valoremp }}</td>
                                <td>{{ $jo->valorfora }}</td>
                                <td>{{ $jo->valorgoal }}</td>
                                <td>{{ $jo->valordupla }}</td>
                                <td>{{ $jo->campeonato_id }}</td>
                                <td>
                                    <a class="btn btn-success" href="#">Apostar</a>
                                </td>
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
