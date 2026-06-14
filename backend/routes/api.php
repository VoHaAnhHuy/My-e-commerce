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
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\InventoryLocationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PaymentTransactionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\RefundController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ShipmentController;

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

// Product images (public)
Route::get('/products/{product}/images', [ProductImageController::class, 'index']);

// Product reviews (public)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);

// Categories (public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Payment methods (active only, public)
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

// Product variants (public)
Route::get('/products/{product}/variants', [ProductVariantController::class, 'index']);

// ──────────────────────────────────────────────
// Cart Routes (Guest + Authenticated)
// ──────────────────────────────────────────────

Route::middleware('optionalAuth')->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/items/{cartItem}', [CartController::class, 'removeItem']);
    Route::delete('/', [CartController::class, 'clear']);
});

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

    // Cart — merge (cần đăng nhập)
    Route::post('/cart/merge', [CartController::class, 'merge']);

    // Orders (customer)
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);

    // Coupons — apply
    Route::post('/coupons/apply', [CouponController::class, 'apply']);

    // Reviews (authenticated user)
    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::post('/reviews', [ReviewController::class, 'store']);
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

    // Product Images
    Route::post('/product-images', [ProductImageController::class, 'store']);
    Route::get('/product-images/{productImage}', [ProductImageController::class, 'show']);
    Route::put('/product-images/{productImage}', [ProductImageController::class, 'update']);
    Route::delete('/product-images/{productImage}', [ProductImageController::class, 'destroy']);

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

    // Reviews (admin: update status, delete)
    Route::get('/reviews/{review}', [ReviewController::class, 'show']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Shipments
    Route::apiResource('shipments', ShipmentController::class);
    Route::get('/orders/{order}/shipments', [ShipmentController::class, 'byOrder']);

    // Refunds
    Route::apiResource('refunds', RefundController::class);
    Route::get('/orders/{order}/refunds', [RefundController::class, 'byOrder']);

    // Inventory Locations
    Route::apiResource('inventory-locations', InventoryLocationController::class);

    // Audit Logs (read-only)
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show']);
    Route::get('/audit-logs/{entityType}/{entityId}', [AuditLogController::class, 'byEntity']);
});
