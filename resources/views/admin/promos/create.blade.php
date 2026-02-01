<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Promo - Admin</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-weight: bold; }
        input, select { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: .75rem 1.5rem; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2563eb; }
        .error { color: red; font-size: 0.875rem; }
    </style>
</head>
<body>
    <h1>Tambah Promo Baru</h1>

    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.promos.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="code">Kode Promo</label>
            <input type="text" name="code" id="code" value="{{ old('code') }}" required placeholder="Contoh: MERDEKA45">
        </div>

        <div class="form-group">
            <label for="discount_type">Tipe Diskon</label>
            <select name="discount_type" id="discount_type" required>
                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="value">Nilai Diskon</label>
            <input type="number" name="value" id="value" step="0.01" value="{{ old('value') }}" required placeholder="Contoh: 10 (untuk 10%) atau 5000 (untuk Rp 5.000)">
        </div>

        <div class="form-group">
            <label for="expired_at">Tanggal Kadaluarsa (Opsional)</label>
            <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at') }}">
            <small style="color: #666;">Kosongkan jika berlaku selamanya</small>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: auto;">
                Aktifkan Promo Ini
            </label>
        </div>

        <button type="submit" class="btn">Simpan Promo</button>
        <a href="{{ route('admin.promos.index') }}" style="margin-left: 1rem; color: #666; text-decoration: none;">Batal</a>
    </form>
</body>
</html>
