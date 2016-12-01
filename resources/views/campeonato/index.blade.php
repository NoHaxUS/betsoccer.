@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
     <h1 style="text-align: center;">Lista De Todos os Campeonatos Cadastrados</h1>  
        <div class="table-responsive col-md-offset-2 col-md-8"> 
                    <table>
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($campeonatos as $campeonato)

                              <tr>
                                <td scope="row">{{ $campeonato->id }}</td>
                                <td>{{ $campeonato->descricao_campeonato }}</td>
                                <td>
                                    <a class="btn btn-default" href="{{ route('campeonato.editar',$campeonato->id) }}">Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Excluir esse registro')? window.location.href='{{ route('campeonato.deletar',$campeonato->id) }}' : false)">Excluir</a>
                                </td>
                            </tr>
                            
                            @endforeach
                            

                        </tbody>

                    </table>

                    <div align="center">
                        {!! $campeonatos->links() !!}
                    </div>            
        </div>
    </div>
</div>
@endsection
