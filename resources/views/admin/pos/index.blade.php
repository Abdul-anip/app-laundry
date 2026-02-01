@extends('layouts.simple')

@section('title', 'Mode Kasir POS')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body { background: #F8FAFC; }
    .pos-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        padding: 1.5rem;
        min-height: 100vh;
    }
    .pos-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
    }
    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid var(--primary);
    }
    .pos-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }
    .exit-btn {
        padding: 0.75rem 1.5rem;
        background: var(--danger);
        color: white;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }
    .service-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .service-card {
        padding: 1.5rem;
        background: #F9FAFB;
        border: 2px solid var(--border);
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .service-card:hover {
        background: #EFF6FF;
        border-color: var(--primary);
        transform: translateY(-2px);
    }
    .service-card.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    .service-card .name {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .service-card .price {
        font-size: 0.95rem;
        opacity: 0.9;
    }
    .toggle-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .toggle-btn {
        flex: 1;
        padding: 1rem;
        background: #F9FAFB;
        border: 2px solid var(--border);
        border-radius: 0.75rem;
        cursor: pointer;
        text-align: center;
        font-weight: 600;
        transition: all 0.2s;
    }
    .toggle-btn:hover {
        background: #EFF6FF;
        border-color: var(--primary);
    }
    .toggle-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    .info-box {
        background: #EFF6FF;
        padding: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid var(--primary);
        margin-bottom: 1rem;
    }
    .info-box h3 {
        color: var(--primary);
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
    }
    .info-box ol, .info-box ul {
        margin: 0.5rem 0 0 1.5rem;
        color: #1E40AF;
    }
    .info-box li {
        margin-bottom: 0.5rem;
    }
    .select2-container .select2-selection--single {
        height: 48px !important;
        padding: 0.5rem !important;
        border: 2px solid var(--border) !important;
        border-radius: 0.75rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }
    
    @media (max-width: 1024px) {
        .pos-layout {
            grid-template-columns: 1fr;
        }
        .service-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="pos-layout">
    <!-- Left Panel: Form -->
    <div class="pos-card">
        <div class="pos-header">
            <h1>Mode Kasir POS</h1>
            <a href="{{ route('admin.dashboard') }}" class="exit-btn">✕ Keluar</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0 0 0 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.orders.store_offline') }}" method="POST">
            @csrf
            
            <!-- Customer Section -->
            <h3 class="section-title">Data Pelanggan</h3>
            <div class="form-group">
                <label class="form-label" for="customer_search">Cari Customer</label>
                <select id="customer_search" style="width: 100%;">
                    <option value="">-- Ketik atau pilih manual --</option>
                </select>
            </div>
            <input type="hidden" name="customer_type" id="customer_type" value="manual">
            <input type="hidden" name="customer_id" id="customer_id">
            
            <div id="manual_input_section">
                <div class="form-group">
                    <label class="form-label" for="customer_name">Nama Pelanggan</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-input" placeholder="Nama customer" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="phone">Nomor Telepon</label>
                <input type="tel" name="phone" id="phone" class="form-input" placeholder="08123456789" required>
            </div>

            <!-- Service Selection -->
            <h3 class="section-title" style="margin-top: 2rem;">Pilih Layanan</h3>
            <div class="toggle-group">
                <div class="toggle-btn active" onclick="switchType('service')">Per Kilogram</div>
                <div class="toggle-btn" onclick="switchType('bundle')">Paket Bundle</div>
            </div>
            <input type="hidden" name="order_type" id="order_type" value="service">

            <!-- Service Section -->
            <div id="service_section">
                <div class="service-grid">
                    @foreach($services as $service)
                        <div class="service-card" onclick="selectService({{ $service->id }})">
                            <div class="name">{{ $service->name }}</div>
                            <div class="price">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg</div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="service_id" id="service_id">
                <div class="form-group">
                    <label class="form-label" for="weight_kg">Berat (Kg)</label>
                    <input type="number" name="weight_kg" id="weight_kg" step="0.5" min="1" class="form-input" placeholder="Masukkan berat">
                </div>
            </div>

            <!-- Bundle Section -->
            <div id="bundle_section" class="hidden">
                <div class="service-grid">
                    @foreach($bundles as $bundle)
                        <div class="service-card" onclick="selectBundle({{ $bundle->id }})">
                            <div class="name">{{ $bundle->name }}</div>
                            <div class="price">Rp {{ number_format($bundle->price, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="bundle_id" id="bundle_id">
            </div>

            <!-- Payment -->
            <h3 class="section-title" style="margin-top: 2rem;">Pembayaran</h3>
            <div class="toggle-group">
                <div class="toggle-btn active" onclick="selectPayment('cash')">Tunai</div>
                <div class="toggle-btn" onclick="selectPayment('transfer')">Transfer</div>
            </div>
            <input type="hidden" name="payment_method" id="payment_method" value="cash">

            <button type="submit" class="btn btn-success btn-lg btn-block" style="margin-top: 2rem;">Proses Pesanan</button>
        </form>
    </div>

    <!-- Right Panel: Info -->
    <div class="pos-card">
        <h3 class="section-title">ℹ️ Panduan Kasir</h3>
        <div class="info-box">
            <h3>Langkah-langkah:</h3>
            <ol>
                <li>Cari/input nama customer</li>
                <li>Pilih layanan kiloan atau paket</li>
                <li>Input berat (jika kiloan)</li>
                <li>Pilih metode pembayaran</li>
                <li>Klik "Proses Pesanan"</li>
            </ol>
        </div>
        <div style="background: #FEF3C7; padding: 1rem; border-radius: 0.75rem; border: 1px solid #F59E0B;">
            <strong style="color: #92400E;">⚠️ Catatan:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem; color: #92400E;">
                <li>Order langsung berstatus "Process"</li>
                <li>Tidak ada biaya antar untuk walk-in</li>
                <li>Struk bisa dicetak setelah order dibuat</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#customer_search').select2({
            placeholder: '-- Ketik atau pilih manual --',
            allowClear: true,
            ajax: {
                url: '{{ route("admin.orders.get_customers") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    data.results.unshift({ id: 'manual', text: '📝 Input Manual', type: 'manual' });
                    return { results: data.results };
                },
                cache: true
            },
            minimumInputLength: 0
        });

        $('#customer_search').on('select2:select', function (e) {
            var data = e.params.data;
            if (data.type === 'manual') {
                $('#customer_type').val('manual');
                $('#customer_id').val('');
                $('#manual_input_section').removeClass('hidden');
                $('#phone').val('');
                $('#customer_name').prop('required', true);
            } else if (data.type === 'user') {
                $('#customer_type').val('user');
                $('#customer_id').val(data.id);
                $('#manual_input_section').addClass('hidden');
                $('#phone').val(data.phone || '');
                $('#customer_name').prop('required', false);
            } else if (data.type === 'offline') {
                $('#customer_type').val('offline');
                $('#customer_id').val(data.id);
                $('#manual_input_section').addClass('hidden');
                $('#phone').val(data.phone || '');
                $('#customer_name').prop('required', false);
            }
        });
    });

    function switchType(type) {
        $('#order_type').val(type);
        if (type === 'service') {
            $('#service_section').removeClass('hidden');
            $('#bundle_section').addClass('hidden');
            $('.toggle-group .toggle-btn').removeClass('active');
            $('.toggle-group .toggle-btn:first').addClass('active');
        } else {
            $('#service_section').addClass('hidden');
            $('#bundle_section').removeClass('hidden');
            $('.toggle-group .toggle-btn').removeClass('active');
            $('.toggle-group .toggle-btn:last').addClass('active');
        }
    }

    function selectService(id) {
        $('#service_id').val(id);
        $('#service_section .service-card').removeClass('active');
        event.target.closest('.service-card').classList.add('active');
    }

    function selectBundle(id) {
        $('#bundle_id').val(id);
        $('#bundle_section .service-card').removeClass('active');
        event.target.closest('.service-card').classList.add('active');
    }

    function selectPayment(method) {
        $('#payment_method').val(method);
        $('.toggle-group .toggle-btn').removeClass('active');
        event.target.classList.add('active');
    }
</script>
@endpush
