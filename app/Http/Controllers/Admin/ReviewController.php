<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'order'])->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(20)->withQueryString();

        // Statistik rating (selalu dari semua review, bukan yang difilter)
        $stats = Review::selectRaw('
            COUNT(*) as total,
            ROUND(AVG(rating), 1) as avg_rating,
            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as r5,
            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as r4,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as r3,
            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as r2,
            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as r1
        ')->first();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }
}
