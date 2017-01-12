@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="table-responsive col-md-offset-3 col-md-6">
            <form action="{{ route('jogo.addPlacar') }}" method="post">
                <table class="table table-sm table-inverse">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>hora</th>
                            <th>Casa</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Fora</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{ csrf_field() }}
                        @foreach($jogos as $jo)
                        <tr>
                            <td class="success">
                                <label>
                                 <input type="checkbox" name="jogo[]" value="{{ $jo->id }}" checked class="hide">
                             {{$jo->codigo}}
                             </label>                             
                         </td>
                         <td class="success"> {{toHora($jo->data)}}</td>
                         <td class="warning">{{ $jo->time->get(0)['descricao_time'] }}</td>
                         <td><input class="form-group" name="r_casa{{$jo->id}}" maxlength="2" size="2"></td>
                         <td>X</td>
                         <td><input  class="form-group" name="r_fora{{$jo->id}}" maxlength="2" size="2"></td>
                         <td class="warning">{{ $jo->time->get(1)['descricao_time'] }}</td>
                     </tr>
                     @endforeach
                     <div class="form-group">
                        <button class="btn btn-info">Cadastrar Placares</button>
                    </div>
                </form>
            </tbody>
        </table>             
        
    </div>
</div>
</div>

@endsection