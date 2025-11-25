@extends('layouts.app')

@section('content')
<h1>Editar Pedido #{{ $order->id }}</h1>

<form method="POST" action="{{ route('orders.update', $order) }}" class="mt-3">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="open" {{ $order->status === 'open' ? 'selected' : '' }}>Em Aberto</option>
            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pago</option>
            <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Cancelado</option>
        </select>
        @error('status') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Desconto (R$)</label>
        <input type="number" step="0.01" name="discount_value" class="form-control"
               value="{{ old('discount_value', $order->discount_value) }}">
        @error('discount_value') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
