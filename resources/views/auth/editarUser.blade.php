@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Situação</th>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user as $users)
                        <tr>
                            <td>{{ $users->name }}</td>
                            <td>{{ $users->email }}</td>
                            @if($users->ativo == false)
                                <td>Desativado</td>
                            @else
                                <td>Ativado</td>
                            @endif
                            <td>
                                @if($users->ativo == false)
                                    @permission('ativar-user')
                                    <a class="btn btn-success"
                                       href="javascript:(confirm('Deseja ativar?')? window.location.href='{{ route('user.ativ',$users->id) }}': false)">Ativar</a>
                                    @endpermission
                                @else
                                    @permission('desativar-user')
                                    <a class="btn btn-danger"
                                       href="javascript:(confirm('Deseja desativar?')? window.location.href='{{ route('user.desat',$users->id) }}' : false)">Desativar</a>
                                    @endpermission
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
