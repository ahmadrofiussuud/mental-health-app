@extends('layouts.seller')

@section('title', 'Store Profile - Seller')

@section('content')
<div class="header">
    <div>
        <h1 style="margin: 0; font-size: 2rem;">🏪 Store Profile</h1>
        <p style="margin: 0.5rem 0 0 0; color: var(--text-muted);">Manage your store information</p>
    </div>
</div>

@if(session('success'))
<div style="background: rgba(34, 197, 94, 0.2); border: 1px solid #22c55e; color: #22c55e; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
    {{ session('success') }}
</div>
@endif

<div class="card" style="background: var(--darkl); border-radius: 16px; padding: 2.5rem; border: 1px solid rgba(255,255,255,0.05);">
    <form action="{{ route('seller.store.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 3rem;">
            <!-- Logo Section -->
            <div style="text-align: center;">
                <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 3px solid var(--primary); margin: 0 auto 1.5rem auto; background: #000;">
                    <img src="{{ asset($store->logo) }}" alt="Store Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <label for="logo" style="display: inline-block; background: var(--dark); border: 1px solid rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; color: white; font-size: 0.9rem; transition: all 0.2s;">
                    Change Logo
                    <input type="file" id="logo" name="logo" style="display: none;" accept="image/*" onchange="previewLogo(event)">
                </label>
            </div>

            <!-- Form Fields -->
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Store Name</label>
                    <input type="text" name="name" value="{{ old('name', $store->name) }}" required
                        style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: white;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">About Store</label>
                    <textarea name="about" rows="4" required
                        style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: white;">{{ old('about', $store->about) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $store->phone) }}" required
                            style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: white;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">City</label>
                        <input type="text" name="city" value="{{ old('city', $store->city) }}" required
                            style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: white;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Address</label>
                        <input type="text" name="address" value="{{ old('address', $store->address) }}" required
                            style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: white;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $store->postal_code) }}" required
                            style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: white;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('img[alt="Store Logo"]').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
