<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Order Offline (Walk-in) - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-weight: bold; }
        input, select, textarea { width: 100%; padding: .75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: .75rem 1.5rem; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn:hover { background: #2563eb; }
        .hidden { display: none; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single { height: auto; padding: .5rem; }
        #manual_input_section { margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 4px; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Input Order Offline</h1>
        <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none;">Kembali ke Dashboard</a>
    </div>

    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orders.store_offline') }}" method="POST" id="orderForm">
        @csrf
        
        <div class="form-group">
            <label for="customer_search">Cari / Pilih Customer</label>
            <select id="customer_search" style="width: 100%;">
                <option value="">-- Ketik untuk mencari atau pilih input manual --</option>
            </select>
            <small style="color: #666;">Ketik nama customer atau pilih "Input Manual" untuk customer baru</small>
        </div>

        <input type="hidden" name="customer_type" id="customer_type">
        <input type="hidden" name="customer_id" id="customer_id">

        <div id="manual_input_section" class="hidden">
            <div class="form-group">
                <label for="customer_name">Nama Pelanggan (Manual)</label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}">
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="form-group">
                <label for="address">Alamat (Opsional)</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Kosongkan jika tidak perlu">
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 2rem 0;">

        <div class="form-group">
            <label style="display: block; margin-bottom: 10px;">Jenis Layanan</label>
            <div>
                <label style="display: inline-block; margin-right: 20px; font-weight: normal;">
                    <input type="radio" name="order_type" id="type_service" value="service" onclick="toggleOrderType()" {{ old('order_type', 'service') == 'service' ? 'checked' : '' }}> 
                    Layanan Kiloan (Per Kg)
                </label>
                <label style="display: inline-block; font-weight: normal;">
                    <input type="radio" name="order_type" id="type_bundle" value="bundle" onclick="toggleOrderType()" {{ old('order_type') == 'bundle' ? 'checked' : '' }}> 
                    Paket Satuan (Bundle)
                </label>
            </div>
        </div>

        <!-- Service Section -->
        <div id="service_section" class="{{ old('order_type', 'service') == 'service' ? '' : 'hidden' }}">
            <div class="grid">
                <div class="form-group">
                    <label for="service_id">Pilih Service</label>
                    <select name="service_id" id="service_id">
                        <option value="">-- Pilih Service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }} /kg)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="weight_kg">Berat (Kg)</label>
                    <input type="number" name="weight_kg" id="weight_kg" step="0.1" min="1" value="{{ old('weight_kg') }}">
                </div>
            </div>
        </div>

        <!-- Bundle Section -->
        <div id="bundle_section" class="{{ old('order_type') == 'bundle' ? '' : 'hidden' }}">
            <div class="form-group">
                <label for="bundle_id">Pilih Paket</label>
                <select name="bundle_id" id="bundle_id">
                    <option value="">-- Pilih Paket Bundle --</option>
                    @foreach($bundles as $bundle)
                        <option value="{{ $bundle->id }}" {{ old('bundle_id') == $bundle->id ? 'selected' : '' }}>
                            {{ $bundle->name }} - Rp {{ number_format($bundle->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label for="fabric_type">Tipe Kain (Opsional)</label>
                <input type="text" name="fabric_type" id="fabric_type" value="{{ old('fabric_type') }}" placeholder="Contoh: Katun, Wol, dll">
            </div>
            
            <div class="form-group">
                <label for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>
        </div>

        <div class="grid">
             <div class="form-group">
                <label for="promo_code">Kode Promo (Opsional)</label>
                <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code') }}" placeholder="Masukkan kode promo jika ada">
            </div>

            <div class="form-group">
                <label for="notes">Catatan Tambahan (Opsional)</label>
                <input type="text" name="notes" id="notes" value="{{ old('notes') }}">
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn" style="width: 100%;">Buat Order & Proses</button>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customer_search').select2({
                placeholder: '-- Ketik untuk mencari atau pilih input manual --',
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.orders.get_customers") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        // Add manual input option at the top
                        data.results.unshift({
                            id: 'manual',
                            text: '📝 Input Manual (Customer Baru)',
                            type: 'manual'
                        });
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });

            $('#customer_search').on('select2:select', function (e) {
                var data = e.params.data;
                
                if (data.type === 'manual') {
                    // Show manual input
                    $('#customer_type').val('manual');
                    $('#customer_id').val('');
                    $('#manual_input_section').removeClass('hidden');
                    $('#phone').val('');
                    $('#customer_name').prop('required', true);
                } else if (data.type === 'user') {
                    // User selected
                    $('#customer_type').val('user');
                    $('#customer_id').val(data.id);
                    $('#manual_input_section').addClass('hidden');
                    $('#phone').val(data.phone || '');
                    $('#customer_name').prop('required', false);
                } else if (data.type === 'offline') {
                    // Offline customer selected
                    $('#customer_type').val('offline');
                    $('#customer_id').val(data.id);
                    $('#manual_input_section').addClass('hidden');
                    $('#phone').val(data.phone || '');
                    $('#customer_name').prop('required', false);
                }
            });

            $('#customer_search').on('select2:clear', function (e) {
                $('#customer_type').val('manual');
                $('#customer_id').val('');
                $('#manual_input_section').removeClass('hidden');
                $('#phone').val('');
                $('#customer_name').prop('required', true);
            });
        });

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
    </script>
</body>
</html>
