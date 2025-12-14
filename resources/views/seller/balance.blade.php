@extends('layouts.seller')

@section('title', 'Balance - Seller')

@section('content')
<div class="header">
    <div>
        <h1 style="margin: 0; font-size: 2rem;">💰 Store Balance</h1>
        <p style="margin: 0.5rem 0 0 0; color: var(--text-muted);">Manage your earnings</p>
    </div>
</div>

<div style="background: var(--darkl); padding: 3rem; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 2rem;">
    <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Total Balance</div>
    <div style="font-size: 3rem; font-weight: bold; color: var(--primary); margin-bottom: 1.5rem;">Rp {{ number_format($balance ?? 0, 0, ',', '.') }}</div>
    <a href="{{ route('seller.withdrawal') }}" style="background: var(--primary); color: black; border: none; padding: 1rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none;" class="btn btn-primary">Withdraw Funds</a>
</div>

<div style="background: var(--darkl); padding: 2rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
    <h2 style="margin: 0 0 1.5rem 0;">Transaction History</h2>
    @if($withdrawals->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted);">Date</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted);">Type</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted);">Amount</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted);">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $withdrawal)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 1rem;">{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                    <td style="padding: 1rem;">Withdrawal</td>
                    <td style="padding: 1rem; color: #ef4444;">- Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        @if($withdrawal->status == 'approved')
                            <span style="background: rgba(34, 197, 94, 0.2); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">Success</span>
                        @elseif($withdrawal->status == 'pending')
                            <span style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">Pending</span>
                        @else
                            <span style="background: rgba(239, 68, 68, 0.2); color: #ef4444; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">Rejected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
            <p>No transaction history yet</p>
        </div>
    @endif
</div>
@endsection