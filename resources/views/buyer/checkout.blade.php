@extends('layouts.app')

@section('title', 'Checkout - FlexSport')

@section('content')
<div class="container" style="padding-top: 8rem; padding-bottom: 4rem;">
    <h1 style="font-family: 'Orbitron'; margin-bottom: 2rem;">CHECKOUT</h1>

    <form action="{{ route('checkout.process') }}" method="POST" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        @csrf
        
        <div class="checkout-form" style="background: var(--darkl); padding: 2rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
            <h3 style="margin-bottom: 1.5rem;">Shipping Details</h3>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Full Name</label>
                <input type="text" value="{{ auth()->user()->name }}" disabled 
                       style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Street Address</label>
                <textarea name="address" required rows="3"
                          style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">City</label>
                    <input type="text" name="city" required 
                           style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Postal Code</label>
                    <input type="number" name="postal_code" required 
                           style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white;">
                </div>
            </div>

            <h3 style="margin-top: 2rem; margin-bottom: 1.5rem;">Payment Method</h3>
            <div style="display: flex; gap: 1rem;">
                <label style="flex: 1; padding: 1rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="radio" name="payment_method" value="transfer" checked>
                    <span>Bank Transfer</span>
                </label>
                <label style="flex: 1; padding: 1rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="radio" name="payment_method" value="cod">
                    <span>COD (Cash on Delivery)</span>
                </label>
            </div>
        </div>

        <div class="checkout-summary" style="background: var(--darkl); padding: 2rem; border-radius: 16px; height: fit-content; border: 1px solid rgba(255,255,255,0.05);">
            <h3 style="margin-bottom: 1.5rem; font-family: 'Orbitron';">ORDER SUMMARY</h3>
            
            @php 
                $total = 0; 
                foreach($cart as $item) $total += $item['price'] * $item['qty'];
                $shipping = 15000; // Flat rate for demo
            @endphp

            @foreach($cart as $item)
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                <span>{{ \Illuminate\Support\Str::limit($item['name'], 20) }} x{{ $item['qty'] }}</span>
                <span>Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            
            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;"></div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Subtotal</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span style="color: var(--text-muted);">Shipping</span>
                <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
            </div>
            
            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;"></div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 1.2rem; font-weight: bold;">
                <span>Total</span>
                <span style="color: var(--primary);">Rp {{ number_format($total + $shipping, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 1rem;">PLACE ORDER</button>
        </div>
    </form>
</div>
@endsection