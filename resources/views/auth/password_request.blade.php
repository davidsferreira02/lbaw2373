@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf



        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
        @error('email')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <label for="password">Nova Password</label>
        <input id="password" type="password" name="password" required>
        @error('password')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <label for="password_confirmation">Confirme a Nova Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
        @error('password_confirmation')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <button type="submit">
            Redefinir Senha
        </button>
    </form>
@endsection
