<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;

// Resource Controllers
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\AttributeValueController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PaymentTransactionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductVariantController;

// ──────────────────────────────────────────────
// Public Routes (không cần đăng nhập)
// ──────────────────────────────────────────────

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Products (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Categories (public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Payment methods (active only, public)
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

// Product variants (public)
Route::get('/products/{product}/variants', [ProductVariantController::class, 'index']);

// ──────────────────────────────────────────────
// Authenticated Routes (cần đăng nhập)
// ──────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/email/resend', [VerificationController::class, 'resend']);
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // Addresses
    Route::apiResource('addresses', AddressController::class)->except(['show']);
    Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::put('/cart/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Orders (customer)
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);

    // Coupons — apply
    Route::post('/coupons/apply', [CouponController::class, 'apply']);
});

// ──────────────────────────────────────────────
// Admin Routes (cần đăng nhập + quyền admin)
// ──────────────────────────────────────────────

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Products
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Product Variants
    Route::post('/variants', [ProductVariantController::class, 'store']);
    Route::put('/variants/{variant}', [ProductVariantController::class, 'update']);
    Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Attributes
    Route::apiResource('attributes', AttributeController::class);

    // Attribute Values
    Route::apiResource('attribute-values', AttributeValueController::class);

    // Coupons
    Route::apiResource('coupons', CouponController::class);

    // Payment Methods
    Route::get('/payment-methods', [PaymentMethodController::class, 'adminIndex']);
    Route::post('/payment-methods', [PaymentMethodController::class, 'store']);
    Route::get('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'show']);
    Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update']);
    Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy']);

    // Payment Transactions
    Route::apiResource('transactions', PaymentTransactionController::class);
    Route::get('/orders/{order}/transactions', [PaymentTransactionController::class, 'byOrder']);

    // Orders (admin)
    Route::get('/orders', [OrderController::class, 'adminIndex']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
});
