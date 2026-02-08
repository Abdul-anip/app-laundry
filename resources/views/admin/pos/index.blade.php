@extends('layouts.pos')

@section('title', 'Mode Kasir POS')

@push('styles')
<style>
    /* Select2 Tailwind Override */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important; /* gray-300 */
        border-radius: 0.5rem !important; /* rounded-lg */
        padding: 0.375rem 0.75rem !important;
        background-color: #fff !important;
        background-position: right 0.5rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.5em 1.5em !important;
        padding-right: 2.5rem !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #111827 !important; /* gray-900 */
        padding-left: 0 !important;
    }
    .select2-container--focus .select2-selection--single {
        border-color: #d97706 !important; /* primary-600 */
        box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.2) !important;
        outline: 0 !important;
    }
    
    .toggle-btn { transition: all 0.2s; }
    .toggle-btn.active {
        background-color: rgb(251 191 36); /* amber-400 */
        color: #fff;
        border-color: rgb(245 158 11);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto p-4 lg:p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-950">Mode Kasir</h1>
            <p class="text-sm text-gray-500">Buat pesanan baru untuk pelanggan walk-in atau terdaftar.</p>
        </div>
        <a href="{{ route('filament.admin.pages.dashboard') }}" 
           class="rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
           &larr; Kembali ke Dashboard
        </a>
    </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6 ring-1 ring-inset ring-green-600/10">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ session('success') }}</h3>
                        @if (session('print_order_id'))
                            <div class="mt-2">
                                <a href="{{ route('admin.orders.print', session('print_order_id')) }}" target="_blank" class="rounded-md bg-green-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                                    🖨️ Cetak Struk
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-6 ring-1 ring-inset ring-red-600/10">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan input:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.orders.store_offline') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        
        <!-- Left Column: Order Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Customer Card -->
            <div class="bg-white shadow-sm ring-1 ring-gray-950/5 rounded-xl p-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-3 mb-4">Informasi Pelanggan</h2>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="customer_search" class="block text-sm font-medium leading-6 text-gray-900">Cari Pelanggan</label>
                        <div class="mt-2">
                            <select id="customer_search" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-amber-600 sm:text-sm sm:leading-6">
                                <option value="">-- Ketik nama/HP atau pilih input manual --</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="customer_type" id="customer_type" value="manual">
                    <input type="hidden" name="customer_id" id="customer_id">

                    <!-- Manual Input Section -->
                    <div id="manual_input_section" class="sm:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                            <div class="mt-2">
                                <input type="text" name="customer_name" id="customer_name" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-600 sm:text-sm sm:leading-6" required placeholder="Nama Pelanggan">
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon (WhatsApp)</label>
                            <div class="mt-2">
                                <input type="tel" name="phone" id="phone" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-600 sm:text-sm sm:leading-6" required placeholder="08...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Card -->
            <div class="bg-white shadow-sm ring-1 ring-gray-950/5 rounded-xl p-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-3 mb-4">Detail Pesanan</h2>
                
                <div class="space-y-4">
                    <!-- Type Toggle -->
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Jenis Layanan</label>
                        <div class="grid grid-cols-2 gap-2 bg-gray-100 p-1 rounded-lg">
                            <button type="button" onclick="switchType('service')" class="toggle-btn active w-full rounded-md py-2 text-sm font-semibold text-gray-900 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 ring-1 ring-inset ring-gray-300 bg-white hover:bg-gray-50">
                                Kiloan / Satuan
                            </button>
                            <button type="button" onclick="switchType('bundle')" class="toggle-btn w-full rounded-md py-2 text-sm font-semibold text-gray-900 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 ring-1 ring-inset ring-gray-300 bg-white hover:bg-gray-50">
                                Bundling
                            </button>
                        </div>
                        <input type="hidden" name="order_type" id="order_type" value="service">
                    </div>

                    <!-- Services Grid -->
                    <div id="service_section" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto p-1">
                        @foreach($services as $service)
                            <div onclick="selectService({{ $service->id }})" class="service-card cursor-pointer relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-amber-500 hover:border-amber-400 hover:bg-amber-50 transition-all">
                                <div class="min-w-0 flex-1">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <p class="text-sm font-medium text-gray-900">{{ $service->name }}</p>
                                    <p class="truncate text-sm text-gray-500">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }} / {{ $service->unit ?? 'kg' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="service_id" id="service_id">

                    <!-- Weight Input -->
                    <div id="weight_input_group" class="mt-4">
                        <label for="weight_kg" class="block text-sm font-medium leading-6 text-gray-900">Total Berat (Kg/Pcs)</label>
                        <div class="mt-2">
                            <input type="number" name="weight_kg" id="weight_kg" step="0.1" min="0.1" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-600 sm:text-sm sm:leading-6" placeholder="0.0">
                        </div>
                    </div>

                    <!-- Bundles Grid (Hidden by default) -->
                    <div id="bundle_section" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto p-1">
                        @foreach($bundles as $bundle)
                            <div onclick="selectBundle({{ $bundle->id }})" class="service-card cursor-pointer relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-amber-500 hover:border-amber-400 hover:bg-amber-50 transition-all">
                                <div class="min-w-0 flex-1">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <p class="text-sm font-medium text-gray-900">{{ $bundle->name }}</p>
                                    <p class="truncate text-sm text-gray-500">Rp {{ number_format($bundle->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="bundle_id" id="bundle_id">
                </div>
            </div>
        </div>

        <!-- Right Column: Summary & Payment -->
        <div class="space-y-6">
            <div class="bg-white shadow-sm ring-1 ring-gray-950/5 rounded-xl p-6 sticky top-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-3 mb-4">Pembayaran</h2>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div onclick="selectPayment('cash')" class="payment-card cursor-pointer rounded-lg border border-gray-300 p-3 text-center hover:border-amber-500 hover:bg-amber-50 active-payment ring-2 ring-amber-600 bg-amber-50">
                            <span class="block text-sm font-medium text-gray-900">Tunai</span>
                        </div>
                        <div onclick="selectPayment('transfer')" class="payment-card cursor-pointer rounded-lg border border-gray-300 p-3 text-center hover:border-amber-500 hover:bg-amber-50">
                            <span class="block text-sm font-medium text-gray-900">Transfer / QRIS</span>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method" value="cash">
                </div>

                <!-- Promo Code -->
                <div class="mb-6">
                    <label for="promo_code" class="block text-sm font-medium leading-6 text-gray-900">Kode Promo (Opsional)</label>
                    <div class="mt-2">
                        <input type="text" name="promo_code" id="promo_code" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-600 sm:text-sm sm:leading-6" placeholder="Masukkan kode promo">
                    </div>
                </div>
                
                <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-900/5 mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Ringkasan</h3>
                    <p class="text-xs text-gray-500">Harga final akan dihitung setelah pesanan dibuat.</p>
                </div>

                <button type="submit" class="w-full rounded-lg bg-amber-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Buat Pesanan
                </button>
            </div>
            
            <!-- Helper Info -->
            <div class="rounded-xl bg-blue-50 p-4 ring-1 ring-blue-700/10">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="text-sm text-blue-700">Pastikan nomor WhatsApp pelanggan benar.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Function to initialize POS logic
    function initPOS() {
        // Destroy existing Select2 if it exists to prevent duplication issues
        if ($('#customer_search').hasClass("select2-hidden-accessible")) {
             $('#customer_search').select2('destroy');
        }

        $('#customer_search').select2({
            placeholder: '-- Cari Pelanggan --',
            allowClear: true,
            minimumInputLength: 0, 
            width: '100%',
            ajax: {
                url: '{{ route("admin.orders.get_customers") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) { 
                    return { 
                        q: params.term || '' 
                    }; 
                },
                processResults: function (data) {
                    if (!data.results) return { results: [] };
                    const hasManual = data.results.some(r => r.id === 'manual');
                    if (!hasManual) {
                        data.results.unshift({ id: 'manual', text: '+ Input Manual', type: 'manual' });
                    }
                    return { results: data.results };
                },
                cache: false
            }
        });

        // Re-bind events
        $('#customer_search').off('select2:select').on('select2:select', function (e) {
            var data = e.params.data;
            if (data.type === 'manual') {
                $('#customer_type').val('manual');
                $('#customer_id').val('');
                $('#manual_input_section').removeClass('hidden').addClass('grid');
                $('#phone').val('');
                $('#customer_name').prop('required', true).focus();
            } else {
                $('#customer_type').val(data.type);
                $('#customer_id').val(data.id);
                $('#manual_input_section').addClass('hidden').removeClass('grid');
                $('#phone').val(data.phone || '');
                $('#customer_name').prop('required', false);
            }
        });
    }

    // Initialize on various events to handle fresh loads and SPA navigations
    $(document).ready(function() {
        initPOS();
    });
    
    // Support for Turbo/Livewire: Use a polling mechanism to ensure it initializes even if events are missed
    // This is a robust fallback for SPA transitions where events might fire before DOM is ready
    let initInterval = setInterval(function() {
        if ($('#customer_search').length > 0 && !$('#customer_search').hasClass("select2-hidden-accessible")) {
            initPOS();
        }
    }, 500);

    // Stop polling after 5 seconds to save resources
    setTimeout(() => clearInterval(initInterval), 5000);
    
    document.addEventListener("turbo:load", initPOS);
    document.addEventListener("livewire:navigated", initPOS);

    function switchType(type) {
        $('#order_type').val(type);
        const btns = document.querySelectorAll('.toggle-btn');
        btns.forEach(b => {
             b.classList.remove('active', 'bg-amber-400', 'text-white', 'border-amber-500'); 
             b.classList.add('bg-white', 'text-gray-900', 'border-gray-300');
        });
        
        if (type === 'service') {
            $('#service_section').removeClass('hidden');
            $('#weight_input_group').removeClass('hidden');
            $('#bundle_section').addClass('hidden');
            btns[0].classList.add('active', 'bg-amber-400', 'text-white', 'border-amber-500');
            btns[0].classList.remove('bg-white', 'text-gray-900');
        } else {
            $('#service_section').addClass('hidden');
            $('#weight_input_group').addClass('hidden');
            $('#bundle_section').removeClass('hidden');
            btns[1].classList.add('active', 'bg-amber-400', 'text-white', 'border-amber-500');
            btns[1].classList.remove('bg-white', 'text-gray-900');
        }
    }

    function selectService(id) {
        $('#service_id').val(id);
        $('.service-card').removeClass('ring-2 ring-amber-500 border-amber-500 bg-amber-50');
        $(event.currentTarget).addClass('ring-2 ring-amber-500 border-amber-500 bg-amber-50');
    }

    function selectBundle(id) {
        $('#bundle_id').val(id);
        $('.service-card').removeClass('ring-2 ring-amber-500 border-amber-500 bg-amber-50');
        $(event.currentTarget).addClass('ring-2 ring-amber-500 border-amber-500 bg-amber-50');
    }

    function selectPayment(method) {
        $('#payment_method').val(method);
        $('.payment-card').removeClass('active-payment ring-2 ring-amber-600 bg-amber-50').addClass('border-gray-300');
        $(event.currentTarget).removeClass('border-gray-300').addClass('active-payment ring-2 ring-amber-600 bg-amber-50');
    }
    
    // Initial State
    switchType('service');
</script>
@endpush
