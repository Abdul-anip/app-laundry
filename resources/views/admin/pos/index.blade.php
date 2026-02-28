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
        background-color: rgb(251 191 36); /* primary-400 */
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
        <a href="{{ route('admin.dashboard') }}" 
           class="rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
           &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Flash Messages (Toast Notifications) -->
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none" style="min-width: 320px; max-width: 400px;">
        @if (session('success'))
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 6000)"
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto flex items-start gap-3 bg-white border border-gray-100 shadow-xl rounded-xl p-4 overflow-hidden relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500"></div>
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center mt-0.5">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900">Berhasil!</p>
                    <p class="text-sm text-gray-500 truncate">{{ session('success') }}</p>
                    @if (session('print_order_id'))
                        <div class="mt-3">
                            <a href="{{ route('admin.orders.print', session('print_order_id')) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Cetak Struk
                            </a>
                        </div>
                    @endif
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        
        @if ($errors->any())
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100);"
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto flex items-start gap-3 bg-white border border-gray-100 shadow-xl rounded-xl p-4 overflow-hidden relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-50 flex items-center justify-center mt-0.5">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900">Terjadi Kesalahan</p>
                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
    </div>

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
                            <select id="customer_search" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
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
                                <input type="text" name="customer_name" id="customer_name" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required placeholder="Nama Pelanggan">
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon (WhatsApp)</label>
                            <div class="mt-2">
                                <input type="tel" name="phone" id="phone" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required placeholder="08...">
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
                            <button type="button" onclick="switchType('service')" class="toggle-btn active w-full rounded-md py-2 text-sm font-semibold text-gray-900 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 ring-1 ring-inset ring-gray-300 bg-white hover:bg-gray-50">
                                Kiloan / Satuan
                            </button>
                            <button type="button" onclick="switchType('bundle')" class="toggle-btn w-full rounded-md py-2 text-sm font-semibold text-gray-900 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 ring-1 ring-inset ring-gray-300 bg-white hover:bg-gray-50">
                                Bundling
                            </button>
                        </div>
                        <input type="hidden" name="order_type" id="order_type" value="service">
                    </div>

                    <!-- Services Grid -->
                    <div id="service_section" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto p-1">
                        @foreach($services as $service)
                            <div onclick="selectService(event, {{ $service->id }})" class="service-card cursor-pointer relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-primary-500 hover:border-primary-400 hover:bg-primary-50 transition-all">
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
                            <input type="number" name="weight_kg" id="weight_kg" step="0.1" min="0.1" class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="0.0">
                        </div>
                    </div>

                    <!-- Bundles Grid (Hidden by default) -->
                    <div id="bundle_section" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto p-1">
                        @foreach($bundles as $bundle)
                            <div onclick="selectBundle(event, {{ $bundle->id }})" class="service-card cursor-pointer relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-primary-500 hover:border-primary-400 hover:bg-primary-50 transition-all">
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
                        <div onclick="selectPayment(event, 'cash')" class="payment-card cursor-pointer rounded-lg border border-gray-300 p-3 text-center hover:border-primary-500 hover:bg-primary-50 active-payment ring-2 ring-primary-600 bg-primary-50">
                            <span class="block text-sm font-medium text-gray-900">Tunai</span>
                        </div>
                        <div onclick="selectPayment(event, 'transfer')" class="payment-card cursor-pointer rounded-lg border border-gray-300 p-3 text-center hover:border-primary-500 hover:bg-primary-50">
                            <span class="block text-sm font-medium text-gray-900">Transfer / QRIS</span>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method" value="cash">
                </div>

                <!-- Promo Code -->
                <div class="mb-6">
                    <label for="promo_code" class="block text-sm font-medium leading-6 text-gray-900">Kode Promo (Opsional)</label>
                    <div class="mt-2 flex rounded-md shadow-sm">
                        <div class="relative flex flex-grow items-stretch focus-within:z-10">
                            <input type="text" name="promo_code" id="promo_code" class="block w-full rounded-none rounded-l-lg border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 uppercase" placeholder="KODE PROMO">
                        </div>
                        <button type="button" onclick="checkPromo()" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-lg px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Cek
                        </button>
                    </div>
                    <p id="promo_message" class="mt-2 text-sm hidden"></p>
                </div>
                
                <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-900/5 mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Ringkasan</h3>
                    <p class="text-xs text-gray-500">Harga final akan dihitung setelah pesanan dibuat.</p>
                    <div id="promo_summary" class="mt-2 text-sm font-medium text-green-600 hidden"></div>
                </div>

                <button type="submit" class="w-full rounded-lg bg-primary-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
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
             b.classList.remove('active', 'bg-primary-400', 'text-white', 'border-primary-500'); 
             b.classList.add('bg-white', 'text-gray-900', 'border-gray-300');
        });
        
        if (type === 'service') {
            $('#service_section').removeClass('hidden');
            $('#weight_input_group').removeClass('hidden');
            $('#bundle_section').addClass('hidden');
            btns[0].classList.add('active', 'bg-primary-400', 'text-white', 'border-primary-500');
            btns[0].classList.remove('bg-white', 'text-gray-900');
        } else {
            $('#service_section').addClass('hidden');
            $('#weight_input_group').addClass('hidden');
            $('#bundle_section').removeClass('hidden');
            btns[1].classList.add('active', 'bg-primary-400', 'text-white', 'border-primary-500');
            btns[1].classList.remove('bg-white', 'text-gray-900');
        }
    }

    function selectService(e, id) {
        $('#service_id').val(id);
        $('.service-card').removeClass('ring-2 ring-primary-500 border-primary-500 bg-primary-50');
        $(e.currentTarget).addClass('ring-2 ring-primary-500 border-primary-500 bg-primary-50');
    }

    function selectBundle(e, id) {
        $('#bundle_id').val(id);
        $('.service-card').removeClass('ring-2 ring-primary-500 border-primary-500 bg-primary-50');
        $(e.currentTarget).addClass('ring-2 ring-primary-500 border-primary-500 bg-primary-50');
    }

    function selectPayment(e, method) {
        $('#payment_method').val(method);
        $('.payment-card').removeClass('active-payment ring-2 ring-primary-600 bg-primary-50').addClass('border-gray-300');
        $(e.currentTarget).removeClass('border-gray-300').addClass('active-payment ring-2 ring-primary-600 bg-primary-50');
    }
    
    function checkPromo() {
        const code = $('#promo_code').val().toUpperCase();
        if(!code) return;
        
        const type = $('#order_type').val();
        let subtotal = 0;
        
        // This is a simplified calculation just to check if promo is valid
        // The real subtotal calculation is too complex to mirror in JS without all pricing data
        // For the POS UI, we just want to validate the code exists.
        
        $.ajax({
            url: '{{ route("admin.orders.check_promo") }}',
            data: { code: code, subtotal: 100000 }, // Mock subtotal just for validation
            success: function(res) {
                const msg = $('#promo_message');
                const sum = $('#promo_summary');
                
                msg.removeClass('hidden text-red-600 text-green-600');
                if(res.valid) {
                    msg.addClass('text-green-600').text('Promo ditemukan dan valid!');
                    let discountText = res.discount_type === 'percent' ? res.value + '%' : 'Rp ' + res.value.toLocaleString('id-ID');
                    sum.removeClass('hidden').text('Promo aktif: Diskon ' + discountText);
                } else {
                    msg.addClass('text-red-600').text(res.message || 'Promo tidak valid');
                    sum.addClass('hidden');
                }
            },
            error: function() {
                $('#promo_message').removeClass('hidden text-green-600').addClass('text-red-600').text('Gagal mengecek promo');
                $('#promo_summary').addClass('hidden');
            }
        });
    }

    // Initial State
    switchType('service');
</script>
@endpush
