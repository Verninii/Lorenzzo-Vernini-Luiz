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
        width: 25%;
    }

</style>

<div class="d-flex justify-content-between mb-3">
    <h1>Pedidos</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary d-flex align-items-center fw-semibold fs-6">Novo Pedido</a>
</div>

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

        <div class="col-md-2 width">
            <select name="status" class="form-control">
                <option value="">Todos os status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Em Aberto</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-secondary w-70" type="submit">Filtrar</button>
        </div>
    </form>

</div>

<div class="table-responsive">
<table class="table table-striped">
    <thead class="table-dark">
        @php
            $direction = request('direction') === 'asc' ? 'desc' : 'asc';
        @endphp
        <tr>
            <th class="rounded-top-start">
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'id','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    Nº
                </a>
            </th>
            <th>Cliente</th>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'status','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    Status
                </a>
            </th>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'total','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    Total
                </a>
            </th>
            <th>
                <a href="{{ route('orders.index', array_merge(request()->all(), ['sort'=>'created_at','direction'=>$direction])) }}" class="text-decoration-none text-light">
                    Data
                </a>
            </th>
            <th class="rounded-top-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    <a href="{{ route('clients.show', $order->client) }}" class="text-decoration-none text-body-emphasis">
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
