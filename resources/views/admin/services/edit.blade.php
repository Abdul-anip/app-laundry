@extends('layouts.simple')

@section('title', 'Edit Layanan')

@section('content')
<div class="centered-layout">
    <div class="container-sm">
        <div class="card card-lg">
            <div class="card-header">
                <h1 class="card-title">✏️ Edit Layanan</h1>
                <p class="card-subtitle">Ubah informasi layanan</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0 0 0 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label" for="name">Nama Layanan *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $service->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price_per_kg">Harga per Kg (Rp) *</label>
                    <input type="number" name="price_per_kg" id="price_per_kg" class="form-input" value="{{ old('price_per_kg', $service->price_per_kg) }}" min="0" step="500" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea">{{ old('description', $service->description) }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-success btn-lg" style="flex: 1;">Update Layanan</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
