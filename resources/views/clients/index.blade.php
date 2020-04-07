@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">Clientes</div>
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('clients.index') }}">
                            <div class="input-group mb-3">
                                    <input type="text" name="query" class="form-control form-control-sm" placeholder="Pesquise por nome, email ou telefone...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" type="submit" id="button-addon2">Buscar</button>
                                    </div>
                            </div>
                            <form>
                        </div>
                        <div class="col-md-2">
                            <div class="float-right">
                                <a href="{{ route('clients.create') }}" role="button" class="btn btn-primary btn-sm">Cadastrar</a>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="card-body">
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($list) > 0)
                        <form id="client-delete-{{ $list->first()->id }}" method="POST" action="{{ route('clients.destroy', ['id' => $list->first()->id]) }}" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endif
                        @forelse($list as $client)
                        <tr>
                            <th scope="row">{{ $client->id }}</th>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>
                                <a href="{{ route('clients.edit', ['id' => $client->id]) }}" role="button" class="btn btn-success btn-sm">Editar</a>

                                <a href="#" onclick="$('#client-delete-{{ $client->id }}').submit();" role="button" class="btn btn-danger btn-sm">Excluir</a>
                                <form id="client-delete-{{ $client->id }}" method="POST" action="{{ route('clients.destroy', ['id' => $client->id]) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <a href="{{ route('clients.phones.index', ['client_id' => $client->id]) }}" role="button" class="btn btn-primary btn-sm">Telefones</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="4">Nenhum cliente cadastrado.</th>
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
