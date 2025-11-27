@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Clientes</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Novo Cliente</a>
</div>

<form method="GET" class="row mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Buscar por nome, email, documento"
               value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <select name="per_page" class="form-control" onchange="this.form.submit()">
            @foreach([10,20,50,100] as $size)
                <option value="{{ $size }}" {{ request('per_page',20)==$size ? 'selected' : '' }}>
                    {{ $size }} por página
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-secondary" type="submit">Filtrar</button>
    </div>
</form>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
        @php
            $direction = request('direction') === 'asc' ? 'desc' : 'asc';
        @endphp
        <tr>
            <th>
                <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => $direction])) }}">
                    Nº
                </a>
            </th>
            <th>
                <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => $direction])) }}">
                    Nome
                </a>
            </th>
            <th>
                <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => $direction])) }}">
                    Email
                </a>
            </th>
            <th>Documento</th>
            <th>Telefone</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>
                    <a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a>
                </td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->document }}</td>
                <td>{{ $client->phone }}</td>
                <td>
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('clients.destroy', $client) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Nenhum cliente encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

{{ $clients->links() }}
@endsection
