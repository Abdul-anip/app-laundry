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
    $settings = \App\Models\LandingPageSetting::getSettings();
    return view('welcome', compact('services', 'settings'));
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
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('admin/orders', \App\Http\Controllers\Admin\OrderController::class)
        ->names('admin.orders')
        ->only(['index', 'show', 'update']);
    
    Route::get('admin/orders/{order}/print', [\App\Http\Controllers\Admin\OrderController::class, 'printReceipt'])
        ->name('admin.orders.print');

    Route::resource('admin/promos', \App\Http\Controllers\Admin\PromoController::class)
        ->names('admin.promos')
        ->except(['show']);

    Route::resource('admin/reviews', \App\Http\Controllers\Admin\ReviewController::class)
        ->names('admin.reviews')
        ->only(['index']);

    // Offline Orders
    Route::get('admin/offline-orders/customers', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'getCustomers'])->name('admin.orders.get_customers');
    Route::get('admin/offline-orders/create', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'create'])->name('admin.orders.create_offline');
    Route::post('admin/offline-orders', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'store'])->name('admin.orders.store_offline');

    // Reports
    Route::get('admin/reports/daily', [\App\Http\Controllers\Admin\ReportController::class, 'daily'])->name('admin.reports.daily');

    // Customers
    Route::get('admin/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers.index');

    // POS Mode
    Route::get('admin/pos', [\App\Http\Controllers\Admin\OfflineOrderController::class, 'pos'])->name('admin.pos');

    // Services & Bundles
    Route::resource('admin/services', \App\Http\Controllers\Admin\ServiceController::class)
        ->names('admin.services');
    Route::resource('admin/bundles', \App\Http\Controllers\Admin\BundleController::class)
        ->names('admin.bundles');
    
    // Landing Page Management - Per Section
    Route::get('admin/landing/hero', [\App\Http\Controllers\Admin\LandingPageController::class, 'editHero'])->name('admin.landing.hero.edit');
    Route::put('admin/landing/hero', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateHero'])->name('admin.landing.hero.update');
    
    Route::get('admin/landing/how-it-works', [\App\Http\Controllers\Admin\LandingPageController::class, 'editHowItWorks'])->name('admin.landing.how-it-works.edit');
    Route::put('admin/landing/how-it-works', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateHowItWorks'])->name('admin.landing.how-it-works.update');
    
    Route::get('admin/landing/services', [\App\Http\Controllers\Admin\LandingPageController::class, 'editServices'])->name('admin.landing.services.edit');
    Route::put('admin/landing/services', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateServices'])->name('admin.landing.services.update');
    
    Route::get('admin/landing/why-choose', [\App\Http\Controllers\Admin\LandingPageController::class, 'editWhyChoose'])->name('admin.landing.why-choose.edit');
    Route::put('admin/landing/why-choose', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateWhyChoose'])->name('admin.landing.why-choose.update');
    
    Route::get('admin/landing/cta', [\App\Http\Controllers\Admin\LandingPageController::class, 'editCta'])->name('admin.landing.cta.edit');
    Route::put('admin/landing/cta', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateCta'])->name('admin.landing.cta.update');
    
    Route::get('admin/landing/footer', [\App\Http\Controllers\Admin\LandingPageController::class, 'editFooter'])->name('admin.landing.footer.edit');
    Route::put('admin/landing/footer', [\App\Http\Controllers\Admin\LandingPageController::class, 'updateFooter'])->name('admin.landing.footer.update');
    
    // Settings
    Route::get('admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
});

/*
|--------------------------------------------------------------------------
| API Endpoint - Get Laundry Location
|--------------------------------------------------------------------------
*/
Route::get('/api/laundry-location', function() {
    $settings = \App\Models\LandingPageSetting::first();
    return response()->json([
        'latitude' => $settings->laundry_latitude ?? -0.1185067,
        'longitude' => $settings->laundry_longitude ?? 100.566124,
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
