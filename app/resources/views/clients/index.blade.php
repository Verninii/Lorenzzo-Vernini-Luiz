@extends('layouts.app')

@section('content')

<style>
    .rounded-top-start {
        border-top-left-radius: 5px;
    }
        .rounded-top-end {
        border-top-right-radius: 10px;
    }
    .width {
        width: 200px;
    }

</style>
<div class="d-flex justify-content-between mb-3">
    <h1>Clientes</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary d-flex align-items-center fw-semibold fs-6">Novo Cliente</a>
</div>

<form method="GET" class="row mb-3">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control"
               placeholder="Buscar por nome, email, documento"
               value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <select name="per_page" class="form-select" onchange="this.form.submit()">
            @foreach([5, 10, 20, 50] as $size)
                <option value="{{ $size }}" {{ request('per_page', 20) == $size ? 'selected' : '' }}>
                    {{ $size }} por página
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-1">
        <button class="btn btn-secondary" type="submit">Filtrar</button>
    </div>
</form>

<form method="POST" action="{{ route('clients.bulk-destroy') }}" id="bulk-delete-form">
    @csrf
    @method('DELETE')

<div class="table-responsive">
<table class="table table-striped rounded-top">
    <thead class="table-dark">
        @php
            $direction = request('direction') === 'asc' ? 'desc' : 'asc';
        @endphp
        <tr>
            <th class="rounded-top-start">Seleção</th>
            <th>
                <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => $direction])) }}" class="text-decoration-none text-light">
                    ID
                </a>
            </th>
            <th>
                <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => $direction])) }}" class="text-decoration-none text-light">
                    Nome
                </a>
            </th>
            <th>
                <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => $direction])) }}" class="text-decoration-none text-light">
                    Email
                </a>
            </th>
            <th>Documento</th>
            <th>Telefone</th>
            <th class="rounded-top-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clients as $client)
            <tr>
                <td>
                    <input type="checkbox"
                               name="ids[]"
                               value="{{ $client->id }}"
                               class="client-checkbox">
                </td>
                <td>{{ $client->id }}</td>
                <td>
                    <a href="{{ route('clients.show', $client) }}" class="text-decoration-none text-body-emphasis">{{ $client->name }}</a>
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
<div class="d-flex justify-content-between mb-2">
        <div></div>
        <button type="submit"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Tem certeza que deseja excluir os clientes selecionados?')">
            Excluir selecionados
        </button>
    </div>
</form>

{{ $clients->links('pagination::bootstrap-5') }}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.client-checkbox');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }
    });
</script>
@endsection

