<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews - Admin</title>
    <style>
        body { font-family: sans-serif; max-width: 1000px; margin: 2rem auto; padding: 0 1rem; }
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .table th, .table td { border: 1px solid #ddd; padding: 0.75rem; text-align: left; }
        .table th { background-color: #f3f4f6; }
        .stars { color: #f59e0b; letter-spacing: 2px; }
        .date { font-size: 0.875rem; color: #666; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Ulasan Pelanggan</h1>
        <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none;">Kembali ke Dashboard</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Pelanggan</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>{{ $review->order->order_code }}</td>
                <td>{{ $review->user->name }}</td>
                <td>
                    <div class="stars">
                        @for($i=1; $i<=5; $i++)
                            {{ $i <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </div>
                </td>
                <td>{{ $review->comment }}</td>
                <td class="date">{{ $review->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada ulasan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $reviews->links() }}
    </div>
</body>
</html>
