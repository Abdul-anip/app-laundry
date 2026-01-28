<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - Laundry App</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-weight: bold; }
        input, select, textarea { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: .75rem 1.5rem; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2563eb; }
        .error { color: red; font-size: 0.875rem; }
        .card { border: 1px solid #ddd; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <h1>Buat Pesanan Baru</h1>

    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.orders.store') }}" method="POST">
        @csrf

        <div class="card">
            <h3>Informasi Pelanggan</h3>
            <div class="form-group">
                <label for="customer_name">Nama Pelanggan</label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
            </div>
            <div class="form-group">
                <label for="address">Alamat Penjemputan</label>
                <textarea name="address" id="address" rows="3" required>{{ old('address', auth()->user()->address) }}</textarea>
            </div>
        </div>

        <div class="card">
            <h3>Pilih Layanan</h3>
            <div class="form-group">
                <label>Tipe Pesanan</label>
                <div>
                    <input type="radio" name="order_type" value="service" id="type_service" {{ old('order_type', 'service') == 'service' ? 'checked' : '' }} onclick="toggleOrderType()">
                    <label for="type_service" style="display:inline; font-weight:normal;">Layanan Kiloan (Service)</label>
                    <br>
                    <input type="radio" name="order_type" value="bundle" id="type_bundle" {{ old('order_type') == 'bundle' ? 'checked' : '' }} onclick="toggleOrderType()">
                    <label for="type_bundle" style="display:inline; font-weight:normal;">Paket Satuan (Bundle)</label>
                </div>
            </div>

            <div id="service_section" class="form-group">
                <label for="service_id">Pilih Service (Per Kg)</label>
                <select name="service_id" id="service_id">
                    <option value="">-- Pilih Service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} - Rp {{ number_format($service->price_per_kg, 0, ',', '.') }} /kg
                        </option>
                    @endforeach
                </select>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="weight_kg">Perkiraan Berat (Kg)</label>
                    <input type="number" name="weight_kg" id="weight_kg" step="0.1" min="1" value="{{ old('weight_kg') }}">
                </div>
            </div>

            <div id="bundle_section" class="form-group hidden">
                <label for="bundle_id">Pilih Paket (Bundle)</label>
                <select name="bundle_id" id="bundle_id">
                    <option value="">-- Pilih Paket --</option>
                    @foreach($bundles as $bundle)
                        <option value="{{ $bundle->id }}" {{ old('bundle_id') == $bundle->id ? 'selected' : '' }}>
                            {{ $bundle->name }} - Rp {{ number_format($bundle->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fabric_type">Jenis Kain (Opsional)</label>
                <input type="text" name="fabric_type" id="fabric_type" placeholder="Contoh: Katun, Sutra, Wool" value="{{ old('fabric_type') }}">
            </div>
            
            <div class="form-group">
                <label for="notes">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" id="notes" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="card">
            <h3>Detail Penjemputan & Pembayaran</h3>
            <div class="form-group">
                <label for="pickup_date">Tanggal Penjemputan</label>
                <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date') }}" required>
            </div>
            <div class="form-group">
                <label for="pickup_time">Waktu Penjemputan</label>
                <input type="time" name="pickup_time" id="pickup_time" value="{{ old('pickup_time') }}" required>
            </div>
            <div class="form-group">
                <label for="distance_km">Jarak Penjemputan (KM)</label>
                <input type="number" name="distance_km" id="distance_km" step="0.1" min="0" value="{{ old('distance_km') }}" required>
                <small style="color: grey;">Gratis ongkir untuk jarak <= 2 KM. Lebih dari itu 5.000/km.</small>
            </div>
            <div class="form-group">
                <label for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>
            <div class="form-group">
                <label for="promo_code">Kode Promo (Opsional)</label>
                <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code') }}">
            </div>
        </div>

        <button type="submit" class="btn">Buat Pesanan</button>
        <a href="{{ route('customer.dashboard') }}" style="margin-left: 1rem; color: #666; text-decoration: none;">Kembali</a>
    </form>

    <script>
        function toggleOrderType() {
            const isService = document.getElementById('type_service').checked;
            const serviceSection = document.getElementById('service_section');
            const bundleSection = document.getElementById('bundle_section');

            if (isService) {
                serviceSection.classList.remove('hidden');
                bundleSection.classList.add('hidden');
            } else {
                serviceSection.classList.add('hidden');
                bundleSection.classList.remove('hidden');
            }
        }
        // Run on load to set correct state based on old input
        toggleOrderType();
    </script>
</body>
</html>
