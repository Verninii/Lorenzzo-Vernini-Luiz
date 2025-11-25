@extends('layouts.app')

@section('content')
<h1>Pedido #{{ $order->id }}</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<ul>
    <li><strong>Cliente:</strong>
        <a href="{{ route('clients.show', $order->client) }}">
            {{ $order->client->name }}
        </a>
    </li>
    <li><strong>Status:</strong>
        @if($order->status === 'open')
            Em Aberto
        @elseif($order->status === 'paid')
            Pago
        @else
            Cancelado
        @endif
    </li>
    <li><strong>Desconto:</strong> R$ {{ number_format($order->discount_value, 2, ',', '.') }}</li>
    <li><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</li>
    <li><strong>Criado em:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
</ul>

<h3>Itens</h3>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor unitário</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>
                    <a href="{{ route('products.show', $item->product) }}">
                        {{ $item->product->name }}
                    </a>
                </td>
                <td>{{ $item->quantity }}</td>
                <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item->total, 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

<a href="{{ route('orders.index') }}" class="btn btn-secondary">Voltar</a>
<a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">Editar</a>
@endsection
