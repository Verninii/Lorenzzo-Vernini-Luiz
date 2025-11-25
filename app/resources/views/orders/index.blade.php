@extends('layouts.app')

@section('content')
<h1>Pedidos</h1>

<div class="d-flex justify-content-between mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-2">
            <input type="number" name="order_id" class="form-control"
                   placeholder="ID do pedido"
                   value="{{ request('order_id') }}">
        </div>

        <div class="col-md-3">
            <select name="client_id" class="form-control">
                <option value="">Todos os clientes</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">Todos os status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Em Aberto</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
            </select>
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
            <button class="btn btn-secondary w-100" type="submit">Filtrar</button>
        </div>
    </form>

    <div>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Novo Pedido</a>
    </div>
</div>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
        @php
            $direction = request('direction') === 'asc' ? 'desc' : 'asc';
        @endphp
        <tr>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'id','direction'=>$direction])) }}">
                    #
                </a>
            </th>
            <th>Cliente</th>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'status','direction'=>$direction])) }}">
                    Status
                </a>
            </th>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'total','direction'=>$direction])) }}">
                    Total
                </a>
            </th>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'created_at','direction'=>$direction])) }}">
                    Data
                </a>
            </th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    <a href="{{ route('clients.show', $order->client) }}">
                        {{ $order->client->name }}
                    </a>
                </td>
                <td>
                    @if($order->status === 'open')
                        Em Aberto
                    @elseif($order->status === 'paid')
                        Pago
                    @else
                        Cancelado
                    @endif
                </td>
                <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir o pedido?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Nenhum pedido encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

{{ $orders->links() }}
@endsection
