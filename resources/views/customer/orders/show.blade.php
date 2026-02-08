<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail - VIP Laundry</title>
    <!-- Use Tailwind for easy styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center">
            <div class="text-6xl mb-4">✓</div>
            <h1 class="text-3xl font-bold mb-2">Pesanan Berhasil Dibuat!</h1>
            <p class="opacity-90">Terima kasih telah mempercayakan laundry Anda kepada kami.</p>
        </div>

        <div class="p-6">
            <!-- Order Info -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Order Code: {{ $order->order_code }}</h3>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-bold 
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'pickup' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'process' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $order->status === 'finished' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'delivered' ? 'bg-gray-100 text-gray-800' : '' }}
                ">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <!-- Stepper / Timeline -->
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-900 mb-4">Status Transaksi</h4>
                <ol class="relative border-l border-gray-200 ml-3">                  
                    @php
                        $steps = ['pending', 'pickup', 'process', 'finished', 'delivered'];
                        $currentStepIndex = array_search($order->status, $steps);
                        $labels = [
                            'pending' => 'Pesanan Diterima',
                            'pickup' => 'Penjemputan Laundry',
                            'process' => 'Sedang Diproses (Cuci/Setrika)',
                            'finished' => 'Selesai & Siap Diantar',
                            'delivered' => 'Pesanan Diterima Customer'
                        ];
                    @endphp

                    @foreach($steps as $index => $step)
                        <li class="mb-10 ml-6">
                            <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -left-4 ring-4 ring-white 
                                {{ $index <= $currentStepIndex ? 'bg-blue-600' : 'bg-gray-200' }}
                            ">
                                @if($index < $currentStepIndex)
                                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                    </svg>
                                @elseif($index === $currentStepIndex)
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                                @else
                                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                @endif
                            </span>
                            <h3 class="font-medium leading-tight {{ $index <= $currentStepIndex ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ $labels[$step] }}
                            </h3>
                            @if($index === $currentStepIndex)
                                <p class="text-sm text-gray-500 mt-1">Status saat ini.</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>

            <hr class="my-6 border-gray-200">

            <!-- Details -->
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Pelanggan</span>
                    <span class="font-medium text-gray-900">{{ $order->customer_name }}</span>
                </div>
                <!-- ... other details ... -->
                 <div class="flex justify-between">
                    <span class="text-gray-600">Tipe Pesanan</span>
                    <span class="font-medium text-gray-900">
                        @if($order->service_id)
                            {{ $order->service->name }} 
                            @if(in_array($order->status, ['pending', 'pickup']))
                                <span class="text-xs text-yellow-600">(Menunggu Penimbangan)</span>
                            @else
                                ({{ floatval($order->weight_kg) }} kg)
                            @endif
                        @elseif($order->bundle_id)
                            {{ $order->bundle->name }}
                        @endif
                    </span>
                </div>
                
                {{-- Conditional Price Display --}}
                @if(!in_array($order->status, ['pending', 'pickup']))
                     <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endif

                 <div class="flex justify-between">
                    <span class="text-gray-600">Ongkos Kirim ({{ floatval($order->distance_km) }} km)</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
                </div>

                @if(!in_array($order->status, ['pending', 'pickup']))
                    @if($order->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon Promo</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-lg font-bold border-t pt-3 mt-3">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="flex justify-between text-lg font-bold border-t pt-3 mt-3 text-gray-500">
                        <span>Total Bayar</span>
                        <span class="text-sm italic">Menunggu Penimbangan & Konfirmasi Admin</span>
                    </div>
                @endif
            </div>

            <!-- Review Section -->
             @if(in_array($order->status, ['finished', 'delivered']))
                <hr class="my-6 border-gray-200">
                
                @if($order->review)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold mb-2">Ulasan Anda</h3>
                        <div class="text-yellow-400 text-xl mb-1">
                            @for($i=1; $i<=5; $i++)
                                {{ $i <= $order->review->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                        <p class="text-gray-600 italic">"{{ $order->review->comment }}"</p>
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold mb-4">Beri Ulasan</h3>
                        
                        @if(session('error'))
                            <div class="text-red-500 mb-2">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="text-green-500 mb-2">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('customer.reviews.store', $order) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <select name="rating" class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="5">★★★★★ (Sangat Puas)</option>
                                    <option value="4">★★★★ (Puas)</option>
                                    <option value="3">★★★ (Cukup)</option>
                                    <option value="2">★★ (Kurang)</option>
                                    <option value="1">★ (Kecewa)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                                <textarea name="comment" rows="3" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Tulis pengalaman Anda..." required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Kirim Ulasan</button>
                        </form>
                    </div>
                @endif
            @endif

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('customer.orders.proof', $order) }}" class="flex-1 text-center bg-green-500 text-white py-3 rounded-xl font-bold hover:bg-green-600 transition shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Invoice
                </a>
                <a href="{{ route('customer.dashboard') }}" class="flex-1 text-center bg-white border-2 border-gray-200 text-gray-700 py-3 rounded-xl font-bold hover:border-gray-300 transition">
                    Kembali ke Dashboard
                </a>
                 <a href="{{ route('customer.orders.create') }}" class="flex-1 text-center bg-white border-2 border-blue-100 text-blue-600 py-3 rounded-xl font-bold hover:bg-blue-50 transition">
                    Pesanan Baru
                </a>
            </div>
        </div>
    </div>
</body>
</html>
