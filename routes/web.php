<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ChatbotController;

// Public/Buyer Routes
Route::get('/', [BuyerController::class, 'index'])->name('home');
Route::get('/collection', [BuyerController::class, 'collection'])->name('collection');
Route::get('/product/{id?}', [BuyerController::class, 'product'])->name('product.detail'); 

Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'page'])->name('page');

// Store Registration Routes (for buyers who want to become sellers)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/store/register', [BuyerController::class, 'showStoreRegistration'])->name('store.register');
    Route::post('/store/register', [BuyerController::class, 'submitStoreRegistration'])->name('store.register.submit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Redirect based on role
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isSeller()) {
            return redirect()->route('seller.dashboard');
        }
        return redirect()->route('home'); // or buyer dashboard if exists
    })->name('dashboard');

    Route::get('/transaction-history', [BuyerController::class, 'history'])->name('transaction.history');
    Route::get('/checkout', [BuyerController::class, 'checkout'])->name('checkout');
    
    // Checkout Processing
    Route::post('/checkout/process', [BuyerController::class, 'processCheckout'])->name('checkout.process');
});

// Cart Routes (Public Access)
Route::post('/cart/add', [BuyerController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [BuyerController::class, 'showCart'])->name('cart.show');
Route::delete('/cart/{id}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');

// Chatbot Routes (Public Access)
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/stores', [AdminController::class, 'stores'])->name('stores');
    Route::post('/stores/verify', [AdminController::class, 'verifyStore'])->name('stores.verify');

});

// Seller Routes - Only requires auth and verified store
Route::middleware(['auth', 'verified'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'index'])->name('dashboard');
    Route::get('/store/edit', [SellerController::class, 'editStore'])->name('store.edit');
    Route::put('/store/update', [SellerController::class, 'updateStore'])->name('store.update');
    Route::get('/products', [SellerController::class, 'products'])->name('products');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [SellerController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/orders', [SellerController::class, 'orders'])->name('orders');
    Route::post('/orders/{id}/status', [SellerController::class, 'updateOrderStatus'])->name('orders.status');
    Route::get('/setup', [SellerController::class, 'setup'])->name('setup');
    Route::get('/withdrawal', [SellerController::class, 'withdrawal'])->name('withdrawal');
    Route::post('/withdrawal', [SellerController::class, 'processWithdrawal'])->name('withdrawal.process');
    Route::get('/balance', [SellerController::class, 'balance'])->name('balance');
    
    // Categories Management
    Route::get('/categories', [SellerController::class, 'categories'])->name('categories');
    Route::post('/categories', [SellerController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [SellerController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [SellerController::class, 'destroyCategory'])->name('categories.destroy');

    // Product Images Management
    Route::get('/products/{id}/images', [SellerController::class, 'productImage'])->name('product.images');
    Route::post('/products/{id}/images', [SellerController::class, 'storeProductImage'])->name('product.images.store');
    Route::delete('/products/{id}/images/{image_id}', [SellerController::class, 'destroyProductImage'])->name('product.images.destroy');
    Route::post('/products/{id}/images/{image_id}/thumbnail', [SellerController::class, 'setProductThumbnail'])->name('product.images.thumbnail');

    Route::get('/profile', function() {
        return view('profile.edit');
    })->name('profile');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
