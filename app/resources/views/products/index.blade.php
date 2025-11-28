@extends('layouts.app')

@section('content')
<style>
    .rounded-top-start {
        border-top-left-radius: 5px;
    }
        .rounded-top-end {
        border-top-right-radius: 10px;
    }

</style>

<div class="d-flex justify-content-between mb-3">
    <h1>Produtos</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center fw-semibold fs-6">Novo Produto</a>
</div>

<form method="GET" class="row mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Buscar por nome ou descrição"
               value="{{ request('search') }}">
    </div>

    <div class="col-md-3">
        <select name="per_page" class="form-select" onchange="this.form.submit()">
            @foreach([5, 10, 20, 50] as $size)
                <option value="{{ $size }}" {{ request('per_page', 20) == $size ? 'selected' : '' }}>
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
    <thead class="table-dark">
        @php
            $direction = request('direction') === 'asc' ? 'desc' : 'asc';
        @endphp
        <tr>
            <th class="rounded-top-start">
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort'=>'id','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    ID
                </a>
            </th>
            <th>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort'=>'name','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    Nome
                </a>
            </th>
            <th>Descrição</th>
            <th>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort'=>'price','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    Preço
                </a>
            </th>
            <th class="rounded-top-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-body-emphasis">{{ $product->name }}</a>
                </td>
                <td>{{ Str::limit($product->description, 50) }}</td>
                <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('products.destroy', $product) }}"
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
                <td colspan="5">Nenhum produto encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

{{ $products->links('pagination::bootstrap-5') }}
@endsection
