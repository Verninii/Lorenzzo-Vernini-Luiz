@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Produtos</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Novo Produto</a>
</div>

<form method="GET" class="row mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Buscar por nome ou descrição"
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
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort'=>'id','direction'=>$direction])) }}">
                    #
                </a>
            </th>
            <th>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort'=>'name','direction'=>$direction])) }}">
                    Nome
                </a>
            </th>
            <th>Descrição</th>
            <th>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort'=>'price','direction'=>$direction])) }}">
                    Preço
                </a>
            </th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
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

{{ $products->links() }}
@endsection
