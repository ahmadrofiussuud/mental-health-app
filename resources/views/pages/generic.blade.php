@extends('layouts.app')

@section('title', $title . ' - FlexSport')

@push('styles')
<style>
    .static-page {
        padding: 4rem 0;
        min-height: 60vh;
    }
    
    .static-content {
        background: rgba(30, 41, 59, 0.5);
        padding: 3rem;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-muted);
        line-height: 1.8;
    }
    
    .static-content h1 {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        background: linear-gradient(to right, #fff, var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        display: inline-block;
    }
    
    .static-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: var(--secondary);
    }
    
    .static-content p {
        margin-bottom: 1rem;
        font-size: 1.05rem;
    }
</style>
@endpush

@section('content')
<div class="static-page">
    <div class="container">
        <div class="static-content">
            {!! $content !!}
        </div>
    </div>
</div>
@endsection
