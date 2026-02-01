@extends('layouts.simple')

@section('title', 'Tambah Paket')

@section('content')
<div class="centered-layout">
    <div class="container-sm">
        <div class="card card-lg">
            <div class="card-header">
                <h1 class="card-title">➕ Tambah Paket Baru</h1>
                <p class="card-subtitle">Isi form untuk menambahkan paket bundle</p>
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

            <form action="{{ route('admin.bundles.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="name">Nama Paket *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" placeholder="Contoh: Paket Bed Cover" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Harga (Rp) *</label>
                    <input type="number" name="price" id="price" class="form-input" value="{{ old('price') }}" placeholder="50000" min="0" step="1000" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea" placeholder="Deskripsi paket...">{{ old('description') }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-success btn-lg" style="flex: 1;">Simpan Paket</button>
                    <a href="{{ route('admin.bundles.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
