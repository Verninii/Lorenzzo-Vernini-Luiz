@extends('layouts.app')

@section('content')
<h1>Editar Cliente</h1>

<form method="POST" action="{{ route('clients.update', $client) }}" class="mt-3">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $client->name) }}">
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}">
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Documento</label>
        <input type="text" name="document" class="form-control" value="{{ old('document', $client->document) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
