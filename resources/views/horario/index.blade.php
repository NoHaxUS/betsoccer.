@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li class="active">Horario</li>
                </ol>


                <div class="panel-body">
                    <p>
                        <a class="btn btn-info" href="{{ route('horario.cadastrar') }}">Cadastrar</a>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descrição</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach($horario as $horario)

                                  <tr>
                                    <td scope="row">{{ $horario->id }}</td>
                                    <td>{{ $horario->data }}</td>
                                    <td>
                                        <a class="btn btn-default" href="{{ route('horario.editar',$horario->id) }}">Editar</a>
                                        <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('horario.deletar',$horario->id) }}' : false)">Excluir</a>
                                    </td>
                                </tr>
                                
                                @endforeach
                                

                            </tbody>

                        </table>
                    </div>
                    <div align="center">
                        

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
