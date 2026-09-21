<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MidtransController;
use Illuminate\Http\Request;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\WishlistController;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::controller(HomeController::class)->group(function() {
    Route::get("/", "home");
    Route::get("/products", "products")->name("products.index");
    Route::get("/about", "about");
    Route::get("/products/{id}", 'detailProduct')->name("product.detail");
});

// ============================================
// AUTH ROUTES (GUEST - Belum Login)
// ============================================
Route::middleware("guest")->group(function() {
    Route::get("/login", [AuthController::class, 'login'])->name("login");
    Route::post("/login", [AuthController::class, 'auth'])->name("login.auth");
    
    Route::get("/register", [AuthController::class, 'showRegister'])->name('register');
    Route::post("/register", [AuthController::class, 'register'])->name('register');
});

// ============================================
// EMAIL VERIFICATION ROUTES
// ============================================
Route::get('/email/verify', function () {
    return view('pages.auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = App\Models\User::findOrFail($id);
    
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('error', 'Link verifikasi tidak valid!');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login')->with('info', 'Email sudah diverifikasi.');
    }

    $user->markEmailAsVerified();

    // Redirect ke LOGIN, bukan dashboard
    return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// ============================================
// AUTH ROUTES (LOGGED IN)
// ============================================
Route::middleware("auth")->group(function() {
    Route::match(['get', 'post'], "/logout", [AuthController::class, "logout"])->name("logout");

    // PROFILE ROUTES
    Route::prefix("profile")->controller(ProfileController::class)->group(function() {
        Route::get("/", 'index')->name("user.profile");
        Route::get("/settings", 'settings')->name("user.profile.settings");
        Route::put("/update", 'update')->name("user.profile.update");
        Route::put("/update-password", 'updatePassword')->name("user.profile.update.password");
        
        // Address Routes
        Route::post("/address", 'addAddress')->name("user.profile.address.store");
        Route::put("/address/{id}", 'updateAddress')->name("user.profile.address.update");
        Route::delete("/address/{id}", 'deleteAddress')->name("user.profile.address.delete");
        Route::put("/address/{id}/default", 'setDefaultAddress')->name("user.profile.address.default");
        Route::get("/address/{id}/edit", 'getAddress')->name("user.profile.address.edit");
    });

    // ORDER CANCEL
    Route::put("/order/{id}/cancel", [OrderController::class, 'cancel'])->name("order.cancel");

    // ============================================
    // MIDTRANS ROUTES
    // ============================================
    Route::prefix("midtrans")->group(function() {
        Route::post("/checkout", [MidtransController::class, 'checkout'])->name("midtrans.checkout");
        Route::get("/success", [MidtransController::class, 'success'])->name("midtrans.success");
        Route::get("/failed", [MidtransController::class, 'failed'])->name("midtrans.failed");
        Route::post("/notification", [MidtransController::class, 'notification'])->name("midtrans.notification");
        Route::get("/pay/{id}", [MidtransController::class, 'pay'])->name("midtrans.pay");
    });

    // ============================================
    // CHECKOUT ROUTES
    // ============================================
    Route::prefix("checkout")->controller(CheckoutController::class)->group(function() {
        Route::get("/", 'index')->name("checkout.index");
        Route::post("/process", 'process')->name("checkout.process");
        Route::get("/success/{id}", 'success')->name("checkout.success");
    });

    // ============================================
    // ORDER DETAIL ROUTE
    // ============================================
    Route::get("/order/{id}", [OrderController::class, 'detail'])->name("order.detail");
    Route::put("/order/{id}/confirm-payment", [OrderController::class, 'confirmPayment'])->name("order.confirm.payment");

    // ============================================
    // DASHBOARD ROUTES (HANYA ADMIN)
    // ============================================
    Route::prefix("dashboard")->middleware('check.role.admin')->group(function() {
        // Dashboard Page
        Route::controller(DashboardController::class)->group(function() {
            Route::get("/", 'index')->name("dashboard");
        });

        // Product Page
        Route::prefix("product")->controller(ProductController::class)->group(function() {
            Route::get("/", 'index')->name("admin.products.index");
            Route::post("/store", 'store')->name("admin.products.store");
            Route::put("/update/{id}", 'update')->name("admin.products.update");
            Route::delete("/delete/{id}", 'delete')->name("admin.products.destroy");
            Route::post("/set-primary", [ProductController::class, 'setPrimary'])->name('admin.products.set.primary');
        });

        // Order Page (Admin)
        Route::prefix("orders")->controller(OrderController::class)->group(function() {
            Route::get("/", 'adminIndex')->name("admin.orders.index");
            Route::get("/{id}", 'detail')->name("admin.orders.detail");
            Route::put("/{id}/status", 'updateStatus')->name("admin.orders.update.status");
            Route::put("/{id}/verify-payment", 'verifyPayment')->name("admin.orders.verify.payment");
            Route::put("/{id}/reject-payment", 'rejectPayment')->name("admin.orders.reject.payment");
            Route::put("/{id}/add-resi", 'addTrackingNumber')->name("admin.orders.add.resi");
        });

        // Sales Report
        Route::prefix("report")->controller(SalesReportController::class)->group(function() {
            Route::get("/", 'index')->name("admin.report.index");
            Route::get("/export", 'export')->name("admin.report.export");
        });
    });

    // ============================================
    // KERANJANG / CART - HANYA untuk user (role_id 2)
    // ============================================
    Route::prefix("keranjang")
        ->middleware(['auth', 'check.role'])
        ->controller(CartController::class)
        ->group(function() {
            Route::get("/", 'index')->name("user.keranjang.index");
            Route::post("/", "create")->name("user.keranjang.create");
            Route::put("/{id}", "update")->name("user.keranjang.update");
            Route::delete("/{id}", "delete")->name("user.keranjang.delete");
        });

    // ============================================
    // WISHLIST ROUTES
    // ============================================
    Route::prefix("wishlist")->controller(WishlistController::class)->group(function() {
        Route::get("/", 'index')->name("user.wishlist.index");
        Route::post("/toggle", 'toggle')->name("user.wishlist.toggle");
        Route::delete("/{id}", 'delete')->name("user.wishlist.delete");
    });
});