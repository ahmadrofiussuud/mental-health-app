@extends('layouts.seller')

@section('title', 'Manage Categories - Seller')

@section('content')
<div class="header">
    <div>
        <h1 style="margin: 0; font-size: 2rem;">📂 Manage Categories</h1>
        <p style="margin: 0.5rem 0 0 0; color: var(--text-muted);">Manage product categories for your store</p>
    </div>
    <button onclick="openAddModal()" class="btn btn-primary" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <span>➕</span> Add New Category
    </button>
</div>

@if(session('success'))
<div style="background: rgba(34, 197, 94, 0.2); border: 1px solid #22c55e; color: #22c55e; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
    {{ session('error') }}
</div>
@endif

@if(count($categories) > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
    @foreach($categories as $cat)
    <div style="background: var(--darkl); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; transition: transform 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.05)'; this.style.transform='translateY(0)'">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="flex: 1;">
                 <h3 style="margin: 0; font-size: 1.25rem; color: white;">{{ $cat['name'] }}</h3>
                 <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.85rem;">{{ $cat['tagline'] ?? '' }}</p>
            </div>
            <div style="background: rgba(255, 69, 0, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; white-space: nowrap; margin-left: 1rem;">
                {{ $cat['product_count'] }} Items
            </div>
        </div>
        
        <p style="color: #ccc; font-size: 0.9rem; flex: 1; line-height: 1.5; margin: 0;">{{ Str::limit($cat['description'], 120) }}</p>
        
        <div style="display: flex; gap: 0.5rem; margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05);">
             <button onclick="openEditModal({{ $cat['id'] }}, '{{ addslashes($cat['name']) }}', '{{ addslashes($cat['tagline'] ?? '') }}', '{{ addslashes($cat['description']) }}')" 
                     class="btn" style="flex: 1; background: rgba(255,255,255,0.1); color: white; border: none; padding: 0.5rem; border-radius: 8px; cursor: pointer; transition: background 0.2s;">
                 Edit
             </button>
             @if($cat['product_count'] == 0)
                <form method="POST" action="{{ route('seller.categories.destroy', $cat['id']) }}" style="flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="width: 100%; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.5); padding: 0.5rem; border-radius: 8px; cursor: pointer;" onclick="return confirm('Delete this category?')">
                        Delete
                    </button>
                </form>
             @else
                <button disabled style="flex: 1; background: rgba(255,255,255,0.05); color: var(--text-muted); border: none; padding: 0.5rem; border-radius: 8px; cursor: not-allowed;" title="Cannot delete category with products">
                    Delete
                </button>
             @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div style="text-align: center; padding: 4rem; background: var(--darkl); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
    <div style="font-size: 4rem; margin-bottom: 1rem;">📂</div>
    <h3 style="margin: 0 0 0.5rem 0;">No Categories Yet</h3>
    <p style="margin: 0 0 1.5rem 0; color: var(--text-muted);">Create your first product category</p>
    <button onclick="openAddModal()" class="btn btn-primary">
        + Add Category
    </button>
</div>
@endif

<!-- Modals -->
<style>
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        background-color: rgba(0,0,0,0.8);
        backdrop-filter: blur(4px);
    }
    .modal-content {
        background-color: #1e293b;
        margin: 5% auto; 
        padding: 2rem; 
        border: 1px solid rgba(255,255,255,0.1); 
        width: 90%;
        max-width: 500px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        position: relative;
        animation: modalSlideIn 0.3s ease-out;
    }
    @keyframes modalSlideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .close {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        color: var(--text-muted);
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s;
    }
    .close:hover {
        color: white;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
        font-family: inherit;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
    }
</style>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2 style="margin: 0 0 1.5rem 0; font-size: 1.5rem;">Add Category</h2>
        <form method="POST" action="{{ route('seller.categories.store') }}">
            @csrf
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" required placeholder="e.g., Running Shoes">
            </div>
            <div class="form-group">
                <label>Tagline (Optional)</label>
                <input type="text" name="tagline" placeholder="Short catchphrase">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required rows="4" placeholder="Describe this category..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding: 0.75rem;">Create Category</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2 style="margin: 0 0 1.5rem 0; font-size: 1.5rem;">Edit Category</h2>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="edit_category_id">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Tagline (Optional)</label>
                <input type="text" name="tagline" id="edit_tagline">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="edit_description" required rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding: 0.75rem;">Update Category</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddModal() { 
    document.getElementById('addModal').style.display = 'block'; 
}

function closeAddModal() { 
    document.getElementById('addModal').style.display = 'none'; 
}

function openEditModal(id, name, tagline, description) {
    document.getElementById('edit_category_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_tagline').value = tagline;
    document.getElementById('edit_description').value = description;
    
    // Decoding description for textarea 
    // (Wait, addslashes handles quotes in JS string, but text content should be safe)
    
    const form = document.getElementById('editForm');
    form.action = "{{ route('seller.categories.update', ':id') }}".replace(':id', id);
    
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() { 
    document.getElementById('editModal').style.display = 'none'; 
}

window.onclick = function(e) {
    if (e.target.classList.contains('modal')) {
        closeAddModal();
        closeEditModal();
    }
}
</script>
@endpush
@endsection