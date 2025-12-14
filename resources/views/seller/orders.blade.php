@extends('layouts.seller')

@section('title', 'Order Management - Seller')

@section('content')
<div class="header">
    <div>
        <h1 style="margin: 0; font-size: 2rem;">📦 Incoming Orders</h1>
        <p style="margin: 0.5rem 0 0 0; color: var(--text-muted);">Manage and fulfill customer orders</p>
    </div>
</div>

<div style="background: var(--darkl); padding: 2rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
    @if(count($orders) > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Order ID</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Customer</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Items</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Total</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Payment</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Order Status</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Date</th>
                <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 1rem;">
                    <span style="font-family:'Orbitron'; color:var(--primary); font-weight: bold;">#{{ $order->code }}</span>
                </td>
                <td style="padding: 1rem;">
                    <div>
                        <div style="font-weight: bold;">{{ $order->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:0.85rem; color:var(--text-muted)">{{ $order->city }}</div>
                    </div>
                </td>
                <td style="padding: 1rem;">
                    {{ $order->transactionDetails->count() }} item(s)
                </td>
                <td style="padding: 1rem;">
                    <span style="font-weight: bold;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </td>
                <td style="padding: 1rem;">
                    @if($order->payment_status == 'paid')
                        <span style="background: rgba(34, 197, 94, 0.2); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">PAID</span>
                    @else
                        <span style="background: rgba(239, 68, 68, 0.2); color: #ef4444; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">UNPAID</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'rgba(251, 191, 36, 0.2)', 'text' => '#fbbf24'],
                            'processing' => ['bg' => 'rgba(59, 130, 246, 0.2)', 'text' => '#3b82f6'],
                            'shipped' => ['bg' => 'rgba(168, 85, 247, 0.2)', 'text' => '#a855f7'],
                            'delivered' => ['bg' => 'rgba(34, 197, 94, 0.2)', 'text' => '#22c55e'],
                            'cancelled' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'text' => '#ef4444'],
                        ];
                        $status = $order->order_status ?? 'pending';
                        $color = $statusColors[$status] ?? ['bg' => 'rgba(100, 100, 100, 0.2)', 'text' => '#666'];
                    @endphp
                    <span style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">
                        {{ $status }}
                    </span>
                </td>
                <td style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                    {{ $order->created_at->format('d M Y') }}
                </td>
                <td style="padding: 1rem;">
                    @if($order->order_status == 'pending')
                        <form action="{{ route('seller.orders.status', $order->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="order_status" value="processing">
                            <button type="submit" style="background: var(--primary); color: black; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.85rem;">
                                Process
                            </button>
                        </form>
                    @elseif($order->order_status == 'processing')
                        <form action="{{ route('seller.orders.status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_status" value="shipped">
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="text" name="tracking_number" placeholder="Enter Tracking No." required
                                    style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.5rem; border-radius: 6px; font-size: 0.85rem; width: 140px;">
                                <button type="submit" style="background: #a855f7; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.85rem;">
                                    Ship
                                </button>
                            </div>
                        </form>
                    @elseif($order->order_status == 'shipped')
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                Tracking: <span style="color: white; font-family: monospace;">{{ $order->tracking_number ?? '-' }}</span>
                            </div>
                            <form action="{{ route('seller.orders.status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_status" value="delivered">
                                <button type="submit" style="background: #22c55e; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.85rem; width: 100%;">
                                    Mark Delivered
                                </button>
                            </form>
                        </div>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.85rem;">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
        <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
        <h3 style="margin: 0 0 0.5rem 0;">No Orders Yet</h3>
        <p style="margin: 0;">Wait for customers to purchase your products!</p>
    </div>
    @endif
</div>
@endsection