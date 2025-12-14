<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        // ... existing index code ...
        $user = auth()->user();
        $store = $user->store;
        
        if (!$store) {
            return redirect()->route('seller.setup');
        }

        $stats = [
            'products_count' => $store->products()->count(),
            'transactions_count' => $store->transactions()->count(),
            'balance' => $store->transactions()->where('payment_status', 'paid')->sum('grand_total') * 0.95,
            'buyer_transactions' => \App\Models\Transaction::where('user_id', $user->id)->count(),
        ];

        // Fetch seller's products for display
        $products = $store->products()->with('productImages')->latest()->take(6)->get();

        return view('seller.dashboard', compact('stats', 'products'));
    }

    public function editStore()
    {
        $store = auth()->user()->store;
        return view('seller.store-profile', compact('store'));
    }

    public function updateStore(Request $request)
    {
        $store = auth()->user()->store;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'about', 'phone', 'address', 'city', 'postal_code']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('stores', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $store->update($data);

        return redirect()->route('seller.store.edit')->with('success', 'Store profile updated successfully!');
    }

    public function products()
    {
        $products = auth()->user()->store->products()->with('productImages')->latest()->get();
        $categories = \App\Models\ProductCategory::all();
        return view('seller.products', compact('products', 'categories'));
    }

    public function orders()
    {
        $orders = auth()->user()->store->transactions()
                    ->with(['user', 'transactionDetails.product'])
                    ->latest()
                    ->get();
                    
        return view('seller.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = \App\Models\Transaction::findOrFail($id);
        
        // Verify this order belongs to the seller's store
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255|required_if:order_status,shipped'
        ]);

        $updateData = ['order_status' => $request->order_status];
        
        if ($request->has('tracking_number')) {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function setup()
    {
        return view('seller.setup');
    }

    public function withdrawal()
    {
        $store = auth()->user()->store;
        
        if (!$store) {
            return redirect()->route('seller.setup');
        }

        // Calculate available balance: 95% of paid transactions minus approved withdrawals
        $totalEarnings = $store->transactions()
            ->where('payment_status', 'paid')
            ->sum('grand_total') * 0.95; // 95% after 5% platform fee
        
        // Get store balance record or create if doesn't exist
        $storeBalance = \App\Models\StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );
        
        // Get total withdrawn (approved withdrawals)
        $totalWithdrawn = $storeBalance->withdrawals()
            ->where('status', 'approved')
            ->sum('amount');
        
        $balance = $totalEarnings - $totalWithdrawn;
        
        // Get withdrawal history
        $withdrawals = $storeBalance->withdrawals()
            ->latest()
            ->get()
            ->toArray();
        
        return view('seller.withdrawal', compact('balance', 'withdrawals'));
    }

    public function balance()
    {
        $store = auth()->user()->store;
        
        if (!$store) {
            return redirect()->route('seller.setup');
        }

        // Calculate available balance logic (same as withdrawal)
        $totalEarnings = $store->transactions()
            ->where('payment_status', 'paid')
            ->sum('grand_total') * 0.95; 
        
        $storeBalance = \App\Models\StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );
        
        $totalWithdrawn = $storeBalance->withdrawals()
            ->where('status', 'approved')
            ->sum('amount');
        
        $balance = $totalEarnings - $totalWithdrawn;

        // Get withdrawal history
        $withdrawals = $storeBalance->withdrawals()
            ->latest()
            ->get();

        return view('seller.balance', compact('balance', 'withdrawals'));
    }

    public function categories()
    {
        $categories = \App\Models\ProductCategory::withCount('products')->get();
        return view('seller.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        \App\Models\ProductCategory::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'tagline' => $request->tagline,
            'description' => $request->description,
        ]);

        return redirect()->route('seller.categories')->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = \App\Models\ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'tagline' => $request->tagline,
            'description' => $request->description,
        ]);

        return redirect()->route('seller.categories')->with('success', 'Category updated successfully!');
    }

    public function destroyCategory($id)
    {
        $category = \App\Models\ProductCategory::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated products!');
        }

        $category->delete();

        return redirect()->route('seller.categories')->with('success', 'Category deleted successfully!');
    }

    public function productImage($id)
    {
        $product = auth()->user()->store->products()->with('productImages')->findOrFail($id);
        $images = $product->productImages;
        return view('seller.product-image', compact('product', 'images'));
    }

    public function storeProductImage(Request $request, $id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_thumbnail' => 'nullable|in:on,off'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            
            // Check if this should be the thumbnail
            $isThumbnail = $request->has('is_thumbnail') || $product->productImages()->count() == 0;
            
            if ($isThumbnail) {
                // Unset other thumbnails
                $product->productImages()->update(['is_thumbnail' => false]);
            }

            $product->productImages()->create([
                'image' => '/storage/' . $path,
                'is_thumbnail' => $isThumbnail
            ]);

            return redirect()->back()->with('success', 'Image uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No image file provided.');
    }

    public function destroyProductImage($productId, $imageId)
    {
        $product = auth()->user()->store->products()->findOrFail($productId);
        $image = $product->productImages()->findOrFail($imageId);
        
        // If deleting thumbnail, make another image thumbnail if exists
        $wasThumbnail = $image->is_thumbnail;
        $image->delete();
        
        if ($wasThumbnail) {
            $newThumbnail = $product->productImages()->first();
            if ($newThumbnail) {
                $newThumbnail->update(['is_thumbnail' => true]);
            }
        }

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }

    public function setProductThumbnail($productId, $imageId)
    {
        $product = auth()->user()->store->products()->findOrFail($productId);
        
        // Reset all thumbnails
        $product->productImages()->update(['is_thumbnail' => false]);
        
        // Set new thumbnail
        $product->productImages()->findOrFail($imageId)->update(['is_thumbnail' => true]);

        return redirect()->back()->with('success', 'Thumbnail updated successfully!');
    }

    // Product CRUD Methods
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1',
            'condition' => 'required|in:new,used',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = auth()->user()->store->products()->create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'product_category_id' => $request->product_category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'condition' => $request->condition,
            'description' => $request->description,
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $filename, 'public');
            
            $product->productImages()->create([
                'image' => '/storage/' . $path,
            ]);
        }

        return redirect()->route('seller.products')->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1',
            'condition' => 'required|in:new,used',
            'description' => 'nullable|string',
        ]);

        $product->update($request->only(['name', 'product_category_id', 'price', 'stock', 'weight', 'condition', 'description']));

        return redirect()->route('seller.products')->with('success', 'Product updated successfully!');
    }

    public function destroyProduct($id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);
        $product->delete();

        return redirect()->route('seller.products')->with('success', 'Product deleted successfully!');
    }

    public function processWithdrawal(\Illuminate\Http\Request $request)
    {
        $store = auth()->user()->store;
        
        if (!$store) {
            return redirect()->route('seller.setup');
        }

        // Get or create store balance
        $storeBalance = \App\Models\StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // Calculate available balance
        $totalEarnings = $store->transactions()
            ->where('payment_status', 'paid')
            ->sum('grand_total') * 0.95;
        
        $totalWithdrawn = $storeBalance->withdrawals()
            ->where('status', 'approved')
            ->sum('amount');
        
        $availableBalance = $totalEarnings - $totalWithdrawn;

        // Validate withdrawal request
        $request->validate([
            'amount' => 'required|numeric|min:50000|max:' . $availableBalance,
            'bank_name' => 'required|string',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ], [
            'amount.max' => 'Jumlah penarikan melebihi saldo tersedia!',
            'amount.min' => 'Minimal penarikan adalah Rp 50.000!',
        ]);

        // Create withdrawal request with auto-approved status
        $storeBalance->withdrawals()->create([
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->account_name,
            'bank_account_number' => $request->account_number,
            'status' => 'approved', // Auto-approve for immediate withdrawal
        ]);

        return redirect()->route('seller.withdrawal')
            ->with('success', 'Penarikan berhasil diproses! Saldo Anda telah dikurangi.');
    }
}
