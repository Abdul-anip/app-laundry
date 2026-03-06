@extends('layouts.admin')

@section('title', 'Order ' . $order->order_code)
@section('page-title', 'Detail Order')
@section('page-subtitle', $order->order_code)

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

    {{-- LEFT: Order Info --}}
    <div class="xl:col-span-2 space-y-4">

        {{-- Order Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg font-mono">{{ $order->order_code }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                @php
                    $statusBadges = [
                        'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'pickup'    => 'bg-blue-100 text-blue-700 border-blue-200',
                        'process'   => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                        'finished'  => 'bg-green-100 text-green-700 border-green-200',
                        'delivered' => 'bg-teal-100 text-teal-700 border-teal-200',
                        'completed' => 'bg-gray-100 text-gray-600 border-gray-200',
                    ];
                @endphp
                <span class="inline-flex px-3 py-1.5 rounded-full text-sm font-semibold border {{ $statusBadges[$order->status] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Customer</p>
                    <p class="font-semibold text-gray-800">{{ $order->customer_name }}</p>
                    <p class="text-gray-500">{{ $order->phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Alamat</p>
                    <p class="text-gray-700 text-sm">{{ $order->address ?? '-' }}</p>
                    @if($order->latitude && $order->longitude)
                    <a href="https://maps.google.com/?q={{ $order->latitude }},{{ $order->longitude }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 mt-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Buka di Google Maps
                    </a>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Layanan</p>
                    <p class="font-medium text-gray-800">{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $order->service ? 'Per Kg' : 'Bundle' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Sumber Order</p>
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $order->order_source === 'online' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->order_source) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Berat</p>
                    <p class="font-semibold text-gray-800">{{ $order->weight_kg > 0 ? $order->weight_kg . ' Kg' : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Jadwal Jemput</p>
                    <p class="font-medium text-gray-800">
                        @if($order->pickup_date && $order->pickup_time)
                            {{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($order->pickup_time)->format('H:i') }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Pembayaran</p>
                    <p class="font-medium text-gray-800">{{ ucfirst($order->payment_method ?? '-') }}</p>
                </div>
            </div>

            {{-- Pricing Summary --}}
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="space-y-1.5 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->pickup_fee > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Pickup</span>
                        <span>Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($order->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon {{ $order->promo?->code ? "({$order->promo->code})" : '' }}</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-gray-800 text-base pt-1 border-t border-gray-100">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan Pesanan</p>
                <div class="bg-yellow-50 text-yellow-800 text-sm p-3 rounded-xl border border-yellow-200/60">
                    {{ $order->notes }}
                </div>
            </div>
            @endif
        </div>

        {{-- Tracking Timeline --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4 text-sm">Tracking Order</h3>
            <div class="space-y-3">
                @forelse($order->orderTrackings->sortBy('created_at') as $tracking)
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-2.5 h-2.5 rounded-full bg-primary-500 mt-1 shrink-0"></div>
                        @if(!$loop->last)
                            <div class="w-px flex-1 bg-gray-200 mt-1"></div>
                        @endif
                    </div>
                    <div class="pb-3">
                        <p class="text-sm font-medium text-gray-800 capitalize">{{ $tracking->status }}</p>
                        <p class="text-xs text-gray-500">{{ $tracking->description }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tracking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada tracking.</p>
                @endforelse
            </div>
        </div>

        {{-- Review (if any) --}}
        @if($order->review)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Review Customer</h3>
            <div class="flex items-center gap-1 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $order->review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
                <span class="text-xs text-gray-500 ml-2">{{ $order->review->rating }}/5</span>
            </div>
            <p class="text-sm text-gray-700">{{ $order->review->comment ?? '-' }}</p>
        </div>
        @endif

    </div>

    {{-- RIGHT: Actions --}}
    <div class="space-y-4">

        {{-- Advance Status --}}
        @php
            $nextLabel = match($order->status) {
                'pending'   => 'Start Pickup',
                'pickup'    => 'Start Process',
                'process'   => 'Finish Order',
                'finished'  => 'Deliver Order',
                'delivered' => 'Selesaikan Order',
                default     => null,
            };
        @endphp
        @if($nextLabel)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" x-data="{ showConfirmModal: false }">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Update Status</h3>
            <button @click="showConfirmModal = true" type="button"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-4 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                {{ $nextLabel }}
            </button>

            <!-- Alpine.js Confirmation Modal -->
            <div x-show="showConfirmModal" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Background backdrop -->
                <div x-show="showConfirmModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
              
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                  <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!-- Modal panel -->
                    <div x-show="showConfirmModal"
                         @click.away="showConfirmModal = false"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                      <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                          <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                          </div>
                          <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-bold leading-6 text-gray-900" id="modal-title">Konfirmasi Update Status</h3>
                            <div class="mt-2">
                              <p class="text-sm text-gray-500">Apakah Anda yakin ingin mengupdate status order <strong>{{ $order->order_code }}</strong> ini ke tahap selanjutnya (<strong>{{ $nextLabel }}</strong>)?</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form method="POST" action="{{ route('admin.orders.advance', $order) }}" class="inline-flex w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto transition-colors">Yakin, Update Status</button>
                        </form>
                        <button type="button" @click="showConfirmModal = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
        @endif

        {{-- Input Berat (pickup + service orders) --}}
        @if($order->status === 'pickup' && $order->service_id)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" x-data="{ open: false }">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Input Berat Aktual</h3>
            <button @click="open = !open"
                    class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-2.5 px-4 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                Input Berat (Saat ini: {{ $order->weight_kg > 0 ? $order->weight_kg . ' Kg' : 'Belum diinput' }})
            </button>
            <div x-show="open" x-cloak class="mt-3 pt-3 border-t border-gray-100">
                <form method="POST" action="{{ route('admin.orders.weight', $order) }}">
                    @csrf
                    <div class="flex gap-2">
                        <input type="number" name="weight_kg" step="0.1" min="0.1"
                               value="{{ $order->weight_kg > 0 ? $order->weight_kg : '' }}"
                               placeholder="Berat (Kg)" required
                               class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- WhatsApp Actions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">WhatsApp Notifikasi</h3>
            <div class="space-y-2">
                @if(in_array($order->status, ['pending', 'pickup']))
                    @php
                        $pickupWaSent = $order->orderTrackings->contains('description', 'WhatsApp Pickup notification sent');
                    @endphp
                    @if($pickupWaSent)
                    <div class="flex items-center gap-2 w-full bg-gray-50 text-gray-400 font-medium py-2 px-3 rounded-xl text-sm cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        WA Pickup — Sudah dikirim ✓
                    </div>
                    @else
                    <a href="{{ route('admin.orders.wa-pickup', $order) }}" target="_blank"
                       class="flex items-center justify-center gap-2 w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2 px-3 rounded-xl transition-colors text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.996 0C5.372 0 0 5.373 0 12c0 2.12.554 4.107 1.524 5.835L.057 23.927l6.266-1.44C8.01 23.47 9.966 24 11.996 24 18.627 24 24 18.627 24 12S18.627 0 11.996 0zm.004 21.89c-1.85 0-3.663-.497-5.24-1.437l-.375-.222-3.895.895.924-3.78-.244-.387A9.886 9.886 0 012.11 12c0-5.46 4.44-9.89 9.89-9.89 5.461 0 9.9 4.44 9.9 9.89 0 5.462-4.439 9.89-9.9 9.89z"/></svg>
                        Kirim WA Pickup
                    </a>
                    @endif
                @endif
                
                @if($order->status === 'pickup' && $order->weight_kg > 0)
                <a href="{{ route('admin.orders.wa-invoice', $order) }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2 px-3 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kirim WA Tagihan
                </a>
                @endif

                @if($order->status === 'finished')
                <a href="{{ route('admin.orders.wa-delivery', $order) }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2 px-3 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Kirim WA Pengiriman
                </a>
                @endif
                
                <a href="{{ route('admin.orders.wa-status', $order) }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2 px-3 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Kirim WA Update Status
                </a>
            </div>
        </div>

        {{-- Kurir --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" x-data="{ showAssign: false }">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Kurir</h3>

            {{-- Kurir yang Sedang Ditugaskan --}}
            @if($order->courier)
                <div class="flex items-center gap-3 mb-3 p-3 rounded-xl
                    {{ $order->courier->status === 'on_duty' ? 'bg-yellow-50 border border-yellow-100' : 'bg-gray-50 border border-gray-100' }}">
                    <div class="w-9 h-9 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($order->courier->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->courier->name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->courier->phone }}</p>
                        @php
                            $taskLabels = ['pickup'=>'Jemput','delivery'=>'Antar','both'=>'Jemput & Antar'];
                        @endphp
                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-medium px-2 py-0.5 rounded-full mt-0.5">
                            {{ $taskLabels[$order->courier_task_type] ?? '-' }}
                        </span>
                    </div>
                    @php
                        $waCourier = preg_replace('/[^0-9]/', '', $order->courier->phone);
                        if (str_starts_with($waCourier, '0')) $waCourier = '62' . substr($waCourier, 1);
                    @endphp
                    <a href="https://wa.me/{{ $waCourier }}" target="_blank"
                       class="shrink-0 p-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors" title="WA Kurir">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.136.562 4.14 1.535 5.876L.057 23.617a.5.5 0 00.609.61l5.957-1.51A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.927a9.912 9.912 0 01-5.031-1.371l-.361-.216-3.738.947.994-3.612-.235-.373A9.916 9.916 0 012.073 12C2.073 6.516 6.516 2.073 12 2.073S21.927 6.516 21.927 12 17.484 21.927 12 21.927z"/></svg>
                    </a>
                </div>
                <button @click="showAssign = !showAssign"
                    class="w-full text-xs text-primary-600 hover:text-primary-800 font-medium py-1.5 text-center transition-colors">
                    <span x-text="showAssign ? '↑ Tutup' : '↓ Ganti Kurir'"></span>
                </button>
            @else
                <p class="text-xs text-gray-400 mb-3">Belum ada kurir yang ditugaskan.</p>
                <button @click="showAssign = !showAssign"
                    class="w-full bg-primary-50 hover:bg-primary-100 text-primary-700 font-medium py-2 px-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tugaskan Kurir
                </button>
            @endif

            {{-- Form Assign Kurir --}}
            <div x-show="showAssign" x-cloak class="mt-3 pt-3 border-t border-gray-100">
                @if($idleCouriers->isEmpty())
                    <p class="text-xs text-center text-gray-400 py-2">
                        Tidak ada kurir yang idle saat ini.<br>
                        <a href="{{ route('admin.couriers.index') }}" class="text-primary-600 hover:underline">Kelola Kurir →</a>
                    </p>
                @else
                    <form method="POST" action="{{ route('admin.orders.assign_courier', $order) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Kurir</label>
                            <select name="courier_id" required
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($idleCouriers as $c)
                                    <option value="{{ $c->id }}" {{ $order->courier_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} (⭐ {{ $c->points }} poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Tugas</label>
                            <select name="courier_task_type" required
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                                <option value="pickup" {{ $order->courier_task_type === 'pickup' ? 'selected' : '' }}>🛵 Jemput Laundry</option>
                                <option value="delivery" {{ $order->courier_task_type === 'delivery' ? 'selected' : '' }}>📦 Antar Laundry</option>
                                <option value="both" {{ $order->courier_task_type === 'both' ? 'selected' : '' }}>🔄 Jemput & Antar</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                            Tugaskan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Print Receipt --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Dokumen</h3>
            <a href="{{ route('admin.orders.print', $order) }}"
               class="flex items-center gap-2 w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2 px-3 rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Download Struk PDF
            </a>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
            ← Kembali ke Daftar Orders
        </a>
    </div>
</div>

@endsection
