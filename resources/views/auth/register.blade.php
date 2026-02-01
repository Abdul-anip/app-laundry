@extends('layouts.simple')

@section('title', 'Register - LaundryKu')

@section('content')
<div class="centered-layout">
    <div class="container-sm">
        <div class="card card-lg">
            <div class="card-header">
                <h1 class="card-title">📝 Daftar Akun</h1>
                <p class="card-subtitle">Buat akun baru untuk memesan laundry</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-input" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                    >
                    @error('name')
                        <span style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

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
                        autocomplete="username"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <span style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        class="form-input" 
                        value="{{ old('phone') }}" 
                        required 
                        placeholder="Contoh: 08123456789"
                    >
                    @error('phone')
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
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                    >
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="form-input" 
                        required 
                        autocomplete="new-password"
                        placeholder="Ketik ulang password"
                    >
                    @error('password_confirmation')
                        <span style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-success btn-lg btn-block">
                        Daftar Sekarang
                    </button>
                </div>

                <!-- Login Link -->
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); text-align: center;">
                    <span style="color: var(--text-secondary); font-size: 0.875rem;">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; margin-left: 0.5rem;">
                        Login di sini
                    </a>
                </div>
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
