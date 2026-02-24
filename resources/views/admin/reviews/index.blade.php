@extends('layouts.admin')
@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')

{{-- Rating Summary Card --}}
@if($stats->total > 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
    <div class="flex flex-col sm:flex-row items-center gap-6">

        {{-- Big Average Number --}}
        <div class="text-center shrink-0">
            <p class="text-6xl font-black text-gray-800 leading-tight">{{ number_format($stats->avg_rating, 1) }}</p>
            <div class="flex justify-center gap-0.5 my-1">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($stats->avg_rating))
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @elseif($i - 0.5 <= $stats->avg_rating)
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @else
                        <svg class="w-5 h-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endif
                @endfor
            </div>
            <p class="text-xs text-gray-400">{{ $stats->total }} review</p>
        </div>

        {{-- Distribution Bars --}}
        <div class="flex-1 w-full space-y-1.5">
            @foreach([5,4,3,2,1] as $star)
            @php $count = $stats->{"r{$star}"} ?? 0; $pct = $stats->total > 0 ? ($count / $stats->total * 100) : 0; @endphp
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('admin.reviews.index', ['rating' => $star]) }}"
                   class="flex items-center gap-1 text-xs text-gray-500 hover:text-yellow-600 shrink-0 w-14">
                    {{ $star }} <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </a>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full bg-yellow-400 transition-all duration-500"
                         style="width: {{ number_format($pct, 1) }}%"></div>
                </div>
                <span class="text-xs text-gray-400 w-8 text-right">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="flex flex-wrap gap-3 items-center justify-between mb-4">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex gap-2">
        <select name="rating" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="">Semua Rating</option>
            @for($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                {{ str_repeat('⭐', $i) }} ({{ $i }} bintang)
            </option>
            @endfor
        </select>
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">Filter</button>
        <a href="{{ route('admin.reviews.index') }}" class="text-gray-500 hover:text-gray-700 text-sm px-3 py-2">Reset</a>
    </form>
    <p class="text-sm text-gray-500">{{ $reviews->total() }} review</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="text-left px-5 py-3">Rating</th>
                <th class="text-left px-5 py-3">Customer</th>
                <th class="text-left px-5 py-3">Order</th>
                <th class="text-left px-5 py-3">Komentar</th>
                <th class="text-right px-5 py-3">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($reviews as $review)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                        {{ str_repeat('⭐', $review->rating) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-700">
                    {{-- Nama dimasking untuk privasi --}}
                    @php
                        $parts = explode(' ', $review->user?->name ?? 'Unknown');
                        $masked = array_map(fn($p) => substr($p,0,1).str_repeat('*', max(0,strlen($p)-1)), $parts);
                    @endphp
                    {{ implode(' ', $masked) }}
                </td>
                <td class="px-5 py-3">
                    <a href="{{ route('admin.orders.show', $review->order_id) }}"
                       class="font-mono text-primary-600 hover:text-primary-800 text-xs font-medium">
                        {{ $review->order?->order_code ?? '-' }}
                    </a>
                </td>
                <td class="px-5 py-3 text-gray-700 max-w-xs truncate">{{ $review->comment ?? '-' }}</td>
                <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $review->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada review.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reviews->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
