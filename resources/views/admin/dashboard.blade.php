@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ringkasan bisnis Anda hari ini')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-6">

    {{-- Monthly Revenue --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 col-span-2 xl:col-span-1">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-gray-500 truncate">Total Pendapatan</p>
                <p class="text-lg font-bold text-gray-800 leading-tight">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                <p class="text-xs text-green-600 font-medium">Bulan ini</p>
            </div>
        </div>
    </div>

    {{-- Monthly Orders --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold text-gray-800 leading-tight">{{ $monthlyOrders }}</p>
                <p class="text-xs text-blue-600 font-medium">Bulan ini</p>
            </div>
        </div>
    </div>

    {{-- New Customers --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Customer Baru</p>
                <p class="text-2xl font-bold text-gray-800 leading-tight">{{ $newCustomers }}</p>
                <p class="text-xs text-purple-600 font-medium">Bulan ini</p>
            </div>
        </div>
    </div>

    {{-- Active Orders --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Active Orders</p>
                <p class="text-2xl font-bold text-gray-800 leading-tight">{{ $activeOrders }}</p>
                <p class="text-xs text-orange-600 font-medium">Sekarang</p>
            </div>
        </div>
    </div>

</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">

    {{-- Revenue Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Grafik Pendapatan</h3>
                <p class="text-xs text-gray-500">30 hari terakhir</p>
            </div>
        </div>
        <div class="h-56">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Order Status Donut --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="mb-4">
            <h3 class="text-sm font-bold text-gray-800">Status Orders</h3>
            <p class="text-xs text-gray-500">Semua order</p>
        </div>
        <div class="h-44 flex items-center justify-center">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="mt-3 space-y-1.5">
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-400', 'pickup' => 'bg-blue-400',
                    'process' => 'bg-indigo-400', 'finished' => 'bg-green-400',
                    'delivered' => 'bg-teal-400',  'completed' => 'bg-gray-400',
                ];
            @endphp
            @foreach($statusCounts as $status => $count)
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full {{ $statusColors[$status] ?? 'bg-gray-300' }}"></div>
                    <span class="capitalize text-gray-600">{{ $status }}</span>
                </div>
                <span class="font-semibold text-gray-800">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Latest Orders --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div>
            <h3 class="text-sm font-bold text-gray-800">Order Terbaru</h3>
            <p class="text-xs text-gray-500">6 order paling baru</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-primary-600 hover:text-primary-700 text-xs font-medium">Lihat Semua →</a>
    </div>

    @php
        $badges = [
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'pickup'    => 'bg-blue-100 text-blue-700',
            'process'   => 'bg-indigo-100 text-indigo-700',
            'finished'  => 'bg-green-100 text-green-700',
            'delivered' => 'bg-teal-100 text-teal-700',
            'completed' => 'bg-gray-100 text-gray-700',
        ];
    @endphp

    {{-- DESKTOP: Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <th class="text-left px-5 py-3">Kode</th>
                    <th class="text-left px-5 py-3">Customer</th>
                    <th class="text-left px-5 py-3">Layanan</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Total</th>
                    <th class="text-right px-5 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($latestOrders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-primary-600 hover:text-primary-700 font-medium text-xs">
                            {{ $order->order_code }}
                        </a>
                    </td>
                    <td class="px-5 py-3 text-gray-700">{{ $order->customer_name }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-medium text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $order->created_at->format('d M, H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada order</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE: Cards --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($latestOrders as $order)
        <a href="{{ route('admin.orders.show', $order) }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
            <div class="flex items-center justify-between mb-1">
                <span class="font-mono text-primary-600 font-bold text-xs">{{ $order->order_code }}</span>
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $order->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">{{ $order->created_at->format('d M, H:i') }}</p>
                </div>
            </div>
        </a>
        @empty
        <div class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada order</div>
        @endforelse
    </div>
</div>


@endsection

@push('scripts')
<script>
// Revenue Line Chart
const rCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(rCtx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Pendapatan',
            data: @json($chartData),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 5,
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: {
            callbacks: { label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') }
        }},
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 8 } },
            y: { grid: { color: '#f3f4f6' }, ticks: {
                font: { size: 10 },
                callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k'
            }}
        }
    }
});

// Status Donut Chart
const sCtx = document.getElementById('statusChart').getContext('2d');
const statusData = @json($statusCounts);
new Chart(sCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: ['#facc15','#60a5fa','#818cf8','#4ade80','#2dd4bf','#9ca3af'],
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '65%'
    }
});
</script>
@endpush
