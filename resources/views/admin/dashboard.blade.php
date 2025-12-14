@extends('layouts.admin')

@section('title', 'Admin Dashboard - FlexSport')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
<style>
    :root {
        --primary: #f97316;
        --dark: #0f172a;
        --darkl: #1e293b;
        --text-muted: #94a3b8;
    }
    
    body {
        background-color: #121212;
        color: white !important;
    }

    /* Page Header styling to match theme */
    .page-header {
        background: #1c1c1e;
        color: white !important;
        border: 2px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;
    }
    
    .page-header h1 {
        color: var(--primary) !important;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .page-header p {
        color: var(--text-muted) !important;
    }

    .page-header p strong {
        color: white !important;
    }

    /* Alert Styling */
    .alert-warning {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid var(--primary) !important;
        color: white !important;
    }

    /* Stat Cards Styling */
    .stat-card {
        background: #1c1c1e;
        border: 2px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary) !important;
        box-shadow: 0 10px 25px rgba(249, 115, 22, 0.2) !important;
    }

    /* Orange accent top border for cards */
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary);
    }

    .stat-value {
        color: white !important;
        font-weight: 800 !important;
        font-size: 3rem !important;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }

    .stat-label {
        color: var(--text-muted) !important;
        font-weight: 500 !important;
        text-transform: uppercase;
        font-size: 0.85rem !important;
        letter-spacing: 1px;
    }
    
    /* Reveal icons in orange */
    .stat-icon {
        display: block !important;
        font-size: 2.5rem !important;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
</style>
@endpush

@section('content')
<div class="content">
    <div class="page-header">
        <h1>ADMIN DASHBOARD</h1>
        <p>Welcome back, <strong>Admin</strong>. Here is your system overview.</p>
    </div>

    @if($stats['pending_stores'] > 0)
    <div class="alert alert-warning">
        ⚠️ There are <strong>{{ $stats['pending_stores'] }}</strong> stores pending verification.
        <a href="{{ route('admin.stores') }}" style="color:inherit; font-weight:bold; margin-left:10px;">Review Now →</a>
    </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon" style="display:none;">👥</span>
            <span class="stat-value">{{ $stats['total_users'] }}</span>
            <span class="stat-label">Total Users</span>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon" style="display:none;">🏪</span>
            <span class="stat-value">{{ $stats['total_stores'] }}</span>
            <span class="stat-label">Active Stores</span>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon" style="display:none;">⏳</span>
            <span class="stat-value">{{ $stats['pending_stores'] }}</span>
            <span class="stat-label">Pending Reviews</span>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon" style="display:none;">📦</span>
            <span class="stat-value">{{ $stats['total_products'] }}</span>
            <span class="stat-label">Products Listed</span>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon" style="display:none;">💰</span>
            <span class="stat-value">{{ $stats['total_transactions'] }}</span>
            <span class="stat-label">Total Orders</span>
        </div>
    </div>
</div>
@endsection