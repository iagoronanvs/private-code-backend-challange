@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Regras
                    <div class="float-right">
                        <a href="{{ route('roles.create') }}" role="button" class="btn btn-primary btn-sm">Cadastrar</a>
                    </div>
                </div>

                <div class="card-body">
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $role)
                        <tr>
                            <th scope="row">{{ $role->id }}</th>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a href="{{ route('roles.edit', ['id' => $role->id]) }}" role="button" class="btn btn-success btn-sm">Editar</a>
                                <a href="#" onclick="$('#client-delete-{{ $role->id }}').submit();" role="button" class="btn btn-danger btn-sm">Excluir</a>
                                <form id="client-delete-{{ $role->id }}" method="POST" action="{{ route('roles.destroy', ['id' => $role->id]) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="4">Nenhuma regra cadastrada.</th>
                        </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
