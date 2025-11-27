@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <h1 class="mb-4">Cadastre-se</h1>

        <form method="POST" action="{{ route('register.perform') }}" id="register-form">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar</button>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}">Já tem conta? Entrar</a>
            </div>
        </form>
    </div>
</div>
@endsection
