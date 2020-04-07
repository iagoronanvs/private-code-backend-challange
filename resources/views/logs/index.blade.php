@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Log de Atividades
                </div>

                <div class="card-body">
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                        <th scope="col">Usuário</th>
                        <th scope="col">Ação</th>
                        <th scope="col">Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $activity)
                        <tr>
                            <td>{{ $activity->causer->name }}</td>
                            <td>{{ $activity->description }}</td>
                            <th scope="row">{{ date('d/m/Y H:i:s', strtotime($activity->created_at)) }}</th>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="4">Nenhum log registrado.</th>
                        </tr>
                        @endforelse
                    </tbody>
                    </table>
                    {{ $list->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
