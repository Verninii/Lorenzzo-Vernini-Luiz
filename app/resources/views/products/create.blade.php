@extends('layouts.app')

@section('content')
<h1>Novo Produto</h1>

<form method="POST" action="{{ route('products.store') }}" class="mt-3">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Preço</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}">
        @error('price') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
