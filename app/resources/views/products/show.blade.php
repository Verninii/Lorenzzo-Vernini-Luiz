@extends('layouts.app')

@section('content')
<h1>Detalhes do Produto</h1>

<ul>
    <li><strong>ID:</strong> {{ $product->id }}</li>
    <li><strong>Nome:</strong> {{ $product->name }}</li>
    <li><strong>Descrição:</strong> {{ $product->description }}</li>
    <li><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</li>
</ul>

<a href="{{ route('products.index') }}" class="btn btn-secondary">Voltar</a>
@endsection
