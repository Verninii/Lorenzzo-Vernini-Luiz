@extends('layouts.app')

@section('content')
<h1>Detalhes do Cliente</h1>

<ul>
    <li><strong>ID:</strong> {{ $client->id }}</li>
    <li><strong>Nome:</strong> {{ $client->name }}</li>
    <li><strong>Email:</strong> {{ $client->email }}</li>
    <li><strong>Documento:</strong> {{ $client->document }}</li>
    <li><strong>Telefone:</strong> {{ $client->phone }}</li>
</ul>

<a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar</a>
@endsection
