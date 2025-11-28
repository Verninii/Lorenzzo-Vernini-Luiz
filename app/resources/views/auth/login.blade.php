@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <h1 class="mb-4 d-flex justify-content-center">Login</h1>

        <form method="POST" action="{{ route('login.perform') }}">
            @csrf

            <div class="mb-1">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" required autofocus>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-1">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check d-flex justify-content-between align-items-center">
                <div>
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>
                <a href="{{ route('register') }}" class="link-underline-light">Cadastre-se</a>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>
@endsection
