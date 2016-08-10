@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                 <ol class="breadcrumb panel-heading">
                    <li class="active">Time</li>
                </ol>


                <div class="panel-body">
                    <p>
                        <a class="btn btn-info" href="{{ route('time.cadastrar') }}">Cadastrar</a>
                    </p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($times as $time)

                              <tr>
                                <td scope="row">{{ $time->id }}</td>
                                <td>{{ $time->descricao_time }}</td>
                                <td>
                                    <a class="btn btn-default" href="{{ route('time.detalhe',$time->id) }}">Detalhe</a>
                                    <a class="btn btn-default" href="{{ route('time.editar',$time->id) }}">Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('time.deletar',$time->id) }}' : false)">Excluir</a>
                                </td>
                            </tr>
                            
                            @endforeach
                            

                        </tbody>

                    </table>

                    <div align="center">
                        {!! $times->links() !!}

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
