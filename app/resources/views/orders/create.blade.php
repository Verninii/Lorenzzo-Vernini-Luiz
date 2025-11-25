@extends('layouts.app')

@section('content')
<h1>Novo Pedido</h1>

<form method="POST" action="{{ route('orders.store') }}" class="mt-3">
    @csrf

    <div class="mb-3">
        <label class="form-label">Cliente</label>
        <select name="client_id" class="form-control">
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }} ({{ $client->email }})
                </option>
            @endforeach
        </select>
        @error('client_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Em Aberto</option>
            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Pago</option>
            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
        </select>
        @error('status') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Desconto (R$)</label>
        <input type="number" step="0.01" name="discount_value" class="form-control"
               value="{{ old('discount_value', 0) }}">
        @error('discount_value') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <h3>Itens do Pedido</h3>

    <div class="table-responsive mb-3">
        <table class="table" id="items-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Lote (quantidade)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="items-body">
                <tr>
                    <td>
                        <select name="product_ids[]" class="form-control">
                            <option value="">Selecione um produto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="quantities[]" class="form-control" min="1" value="1">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">Adicionar Item</button>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Salvar Pedido</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<script>
function addRow() {
    const tbody = document.getElementById('items-body');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);

    // limpa valores
    newRow.querySelectorAll('select, input').forEach(el => {
        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
        } else {
            el.value = 1;
        }
    });

    tbody.appendChild(newRow);
}

function removeRow(button) {
    const tbody = document.getElementById('items-body');
    if (tbody.rows.length === 1) {
        return; // não deixar remover todas
    }
    button.closest('tr').remove();
}
</script>
@endsection
