@extends('layouts.seller')

@section('title', 'Penarikan Saldo - FlexSport')

@push('styles')
<style>
    body {
        background: var(--dark);
    }
    
    .container {
        padding: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .card {
        background: var(--darkl);
        border-radius: 16px;
        padding: 2.5rem;
        border: 1px solid rgba(255,255,255,0.05);
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .card h1 {
        color: white;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .balance-display {
        background: linear-gradient(135deg, rgba(255, 69, 0, 0.2) 0%, rgba(255, 69, 0, 0.1) 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 69, 0, 0.3);
    }
    
    .balance-display p {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: var(--text-muted);
    }
    
    .balance-display h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        color: var(--primary);
    }
    
    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .alert-error {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: white;
    }
    
    input, select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        font-family: 'Sora', sans-serif;
        font-size: 1rem;
        background: rgba(0,0,0,0.3);
        color: white;
    }
    
    input:focus, select:focus {
        outline: none;
        border-color: var(--primary);
        background: rgba(0,0,0,0.4);
    }
    
    input::placeholder {
        color: var(--text-muted);
    }
    
    select option {
        background: #1e293b;
        color: white;
    }
    
    .btn {
        padding: 1rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Sora', sans-serif;
        transition: all 0.3s;
        width: 100%;
        font-size: 1rem;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 69, 0, 0.4);
    }
    
    .btn-secondary {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background: rgba(255,255,255,0.15);
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    
    th {
        background: rgba(255, 69, 0, 0.1);
        color: var(--primary);
        font-weight: 700;
    }
    
    tr:hover {
        background: rgba(255,255,255,0.02);
    }
    
    .badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }
    
    .badge-pending {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
    }
    
    .badge-approved {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }
    
    .badge-rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    small {
        display: block;
        margin-top: 0.3rem;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
<div class="header-actions" style="margin-bottom: 1.5rem;">
    <a href="{{ route('seller.balance') }}" class="btn btn-secondary" style="width: fit-content; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; font-size: 0.9rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Balance
    </a>
</div>

<div class="container">
    <div class="card">
        <h1>💸 Tarik Saldo Toko</h1>
        
        <div class="balance-display">
            <p>Saldo Tersedia</p>
            <h2>Rp {{ number_format($balance, 0, ',', '.') }}</h2>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('seller.withdrawal.process') }}">
            @csrf
            
            <div class="form-group">
                <label for="amount">Jumlah Penarikan (Rp)</label>
                <input type="number" name="amount" id="amount" required min="50000" 
                       placeholder="Masukkan jumlah penarikan" value="{{ old('amount') }}">
                <small>Minimal penarikan: Rp 50.000</small>
            </div>

            <div class="form-group">
                <label for="bank_name">Pilih Bank</label>
                <select name="bank_name" id="bank_name" required>
                    <option value="">-- Pilih Bank --</option>
                    <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>BCA</option>
                    <option value="BRI" {{ old('bank_name') == 'BRI' ? 'selected' : '' }}>BRI</option>
                    <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>BNI</option>
                    <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    <option value="CIMB Niaga" {{ old('bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                    <option value="BSI" {{ old('bank_name') == 'BSI' ? 'selected' : '' }}>BSI</option>
                </select>
            </div>

            <div class="form-group">
                <label for="account_name">Nama Pemilik Rekening</label>
                <input type="text" name="account_name" id="account_name" required 
                       placeholder="Contoh: Andi Pratama" value="{{ old('account_name') }}">
            </div>

            <div class="form-group">
                <label for="account_number">Nomor Rekening</label>
                <input type="text" name="account_number" id="account_number" required 
                       placeholder="Masukkan nomor rekening" value="{{ old('account_number') }}">
            </div>

            <button type="submit" class="btn btn-primary">💸 Ajukan Penarikan</button>
        </form>
    </div>

    <div class="card">
        <h1>📜 Riwayat Penarikan</h1>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Bank</th>
                    <th>Rekening</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($w['created_at'])->format('d M Y H:i') }}</td>
                        <td style="font-weight: 700; color: var(--primary);">Rp {{ number_format($w['amount'], 0, ',', '.') }}</td>
                        <td>{{ $w['bank_name'] }}</td>
                        <td>
                            <div style="font-weight: 600; color: white;">{{ $w['bank_account_name'] }}</div>
                            <small>{{ $w['bank_account_number'] }}</small>
                        </td>
                        <td>
                            @if($w['status'] === 'pending')
                                <span class="badge badge-pending">⏳ Pending</span>
                            @elseif($w['status'] === 'approved')
                                <span class="badge badge-approved">✅ Disetujui</span>
                            @else
                                <span class="badge badge-rejected">❌ Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:2rem; color:var(--text-muted);">
                            Belum ada riwayat penarikan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validasi jumlah penarikan tidak melebihi saldo
    document.querySelector('form').addEventListener('submit', function(e) {
        const amount = parseInt(document.getElementById('amount').value);
        const balance = {{ $balance }};
        
        if (amount > balance) {
            e.preventDefault();
            alert('Jumlah penarikan tidak boleh melebihi saldo tersedia!');
            return false;
        }
        
        if (amount < 50000) {
            e.preventDefault();
            alert('Minimal penarikan adalah Rp 50.000!');
            return false;
        }
    });
</script>
@endpush