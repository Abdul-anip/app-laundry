<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $services = \App\Models\Service::all();
    return view('welcome', compact('services'));
});

// Public Tracking Routes
Route::get('/tracking', [App\Http\Controllers\TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking', [App\Http\Controllers\TrackingController::class, 'search'])->name('tracking.search');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (After Login)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->to('/admin');
    }

    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    // POS Mode
    Route::get('admin/pos', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'pos'])->name('admin.pos');
    
    // Offline Orders (Restored for POS)
    Route::get('admin/offline-orders/customers', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'getCustomers'])->name('admin.orders.get_customers');
    Route::get('admin/offline-orders/create', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'create'])->name('admin.orders.create_offline');
    Route::post('admin/offline-orders', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'store'])->name('admin.orders.store_offline');

    // Print Receipt
    Route::get('admin/orders/{order}/print', [\App\Http\Controllers\Admin\OrderController::class, 'printReceipt'])
        ->name('admin.orders.print');
});

/*
|--------------------------------------------------------------------------
| API Endpoint - Get Laundry Location
|--------------------------------------------------------------------------
*/
Route::get('/api/laundry-location', function() {
    return response()->json([
        'latitude' => -0.1185067,
        'longitude' => 100.566124,
    ]);
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::resource('customer/orders', \App\Http\Controllers\Customer\OrderController::class)
        ->names('customer.orders')
        ->only(['index', 'create', 'store', 'show']);

    Route::get('/customer/orders/{order}/proof', [\App\Http\Controllers\Customer\OrderController::class, 'downloadProof'])
        ->name('customer.orders.proof');

    Route::post('/customer/orders/{order}/confirm', [\App\Http\Controllers\Customer\OrderController::class, 'confirm'])
        ->name('customer.orders.confirm');
        
    Route::post('/customer/orders/{order}/review', [\App\Http\Controllers\Customer\ReviewController::class, 'store'])
        ->name('customer.reviews.store');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
