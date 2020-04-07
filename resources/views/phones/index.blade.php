@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Telefones de {{ $client->name }}

                    <div class="float-right">
                        <a href="{{ route('clients.phones.create', ['client_id' => $client->id]) }}" role="button" class="btn btn-primary btn-sm">Cadastrar</a>
                    </div>
                </div>

                <div class="card-body">
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Number</th>
                        <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $index => $phone)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $phone->number }}</td>
                            <td>
                                <a href="{{ route('clients.phones.edit', ['client_id' => $client->id, 'id' => $phone->id]) }}" role="button" class="btn btn-success btn-sm">Editar</a>
                                <a href="#" onclick="$('#phone-delete-{{ $phone->id }}').submit();" role="button" class="btn btn-danger btn-sm">Excluir</a>
                                <form id="phone-delete-{{ $phone->id }}" method="POST" action="{{ route('clients.phones.destroy', ['client_id' => $client->id, 'id' => $phone->id]) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="tel:{{ $phone->number }}" role="button" class="btn btn-primary btn-sm">Ligar</a>
                                <a href="https://api.whatsapp.com/send?phone={{ $phone->number }}&text=Escreva%20sua%20mensagem%20aqui..." target="_blank" role="button" class="btn btn-success btn-sm">Whatsapp</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="4">Nenhum telefone cadastrado.</th>
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
