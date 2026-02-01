<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.reviews.index', compact('reviews'));
    }
}
