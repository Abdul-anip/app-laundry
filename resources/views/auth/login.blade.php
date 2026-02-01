@extends('layouts.simple')

@section('title', 'Login - LaundryKu')

@section('content')
<div class="centered-layout">
    <div class="container-sm">
        <div class="card card-lg">
            <div class="card-header">
                <h1 class="card-title">🔐 Login</h1>
                <p class="card-subtitle">Masuk ke akun Anda</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-input" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <span style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-input" 
                        required 
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                    >
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember_me"
                            style="width: auto; margin-right: 0.5rem;"
                        >
                        <span style="font-size: 0.875rem; color: var(--text-secondary);">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit and Links -->
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Masuk
                    </button>
                </div>

                <div style="margin-top: 1.5rem; text-align: center;">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--primary); text-decoration: none; font-size: 0.875rem;">
                            Lupa password?
                        </a>
                    @endif
                </div>

                @if (Route::has('register'))
                <div style="margin-top: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--border); text-align: center;">
                    <span style="color: var(--text-secondary); font-size: 0.875rem;">Belum punya akun?</span>
                    <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; margin-left: 0.5rem;">
                        Daftar Sekarang
                    </a>
                </div>
                @endif
            </form>

            <div style="margin-top: 1.5rem; text-align: center;">
                <a href="{{ url('/') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
                    ← Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
