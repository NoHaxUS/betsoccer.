@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="table-responsive col-md-offset-2 col-md-8">
            <h1 style="text-align: center;">Lista De Todos os Times Cadastrados</h1>
            <table>
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
                        <!--<a class="btn btn-default" href="{{ route('time.detalhe',$time->id) }}">Detalhe</a>-->
                        @permission('editar-time')
                        <a class="btn btn-default" href="{{ route('time.editar',$time->id) }}">Editar</a>
                        @endpermission
                        @permission('excluir-time')
                        <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('time.deletar',$time->id) }}' : false)">Excluir</a>
                        @endpermission
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
@endsection
