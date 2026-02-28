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
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes (Native Laravel — Filament replaced)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/advance', [\App\Http\Controllers\Admin\OrderController::class, 'advanceStatus'])->name('orders.advance');
    Route::post('orders/{order}/weight', [\App\Http\Controllers\Admin\OrderController::class, 'inputWeight'])->name('orders.weight');
    Route::get('orders/{order}/wa-pickup', [\App\Http\Controllers\Admin\OrderController::class, 'waPickup'])->name('orders.wa-pickup');
    Route::get('orders/{order}/wa-invoice', [\App\Http\Controllers\Admin\OrderController::class, 'waInvoice'])->name('orders.wa-invoice');
    Route::get('orders/{order}/wa-status', [\App\Http\Controllers\Admin\OrderController::class, 'waStatus'])->name('orders.wa-status');
    Route::get('orders/{order}/print', [\App\Http\Controllers\Admin\OrderController::class, 'printReceipt'])->name('orders.print');

    // POS (Offline Orders)
    Route::get('pos', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'pos'])->name('pos');
    Route::get('offline-orders/customers', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'getCustomers'])->name('orders.get_customers');
    Route::post('offline-orders', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'store'])->name('orders.store_offline');
    Route::get('offline-orders/check-promo', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'checkPromo'])->name('orders.check_promo');

    // Services
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)
        ->except(['show']);

    // Bundles
    Route::resource('bundles', \App\Http\Controllers\Admin\BundleController::class)
        ->except(['show']);

    // Promos
    Route::resource('promos', \App\Http\Controllers\Admin\PromoController::class)
        ->except(['show']);

    // Customers (read-only)
    Route::get('customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');

    // Reviews (read-only)
    Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');

    // Reports
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'downloadPdf'])->name('reports.pdf');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Notifications
    Route::get('notifications/fetch', [\App\Http\Controllers\Admin\NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::get('notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
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
