@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="table-responsive col-md-offset-3 col-md-5">
            <table class="table table-sm table-inverse ">
                <thead>
                    <tr>
                        <th>Cod</th>
                        <th>Casa</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Fora</th>
                    </tr>
                </thead>
                <tbody>
                 <form action="{{ route('jogo.addPlacar') }}" method="post">
                    {{ csrf_field() }}
                    @foreach($jogos as $jo)
                    <tr>

                        <td scope="row">
                            <label>
                             <input type="checkbox" name="jogo[]" value="{{ $jo->id }}" checked class="hide">
                             {{ $jo->id }}
                         </label>
                         </td>
                         <td class="success">{{ $jo->time->get(0)['descricao_time'] }}</td>
                         <td><input name="r_casa{{$jo->id}}" maxlength="2" size="2" required></td>
                         <td>X</td>
                         <td><input name="r_fora{{$jo->id}}" maxlength="2" size="2" required></td>
                         <td class="warning">{{ $jo->time->get(1)['descricao_time'] }}</td>
                     </tr>
                     @endforeach


                 </tbody>
             </table>

             <div class="form-group">
                <button class="btn btn-info">Cadastrar Placares</button>
            </div>
        </form>
    </div>

</div>
</div>

@endsection