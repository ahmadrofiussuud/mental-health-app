@extends('layouts.admin')

@section('title', 'Manage Users - FlexSport Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('content')
<div class="content">
    <div class="container">
        <div class="page-header">
            <h1>MANAGE USERS</h1>
            <p>Access control for all registered accounts.</p>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge badge-admin">ADMIN</span>
                            @elseif($user->role == 'member')
                                <span class="badge badge-member">MEMBER</span>
                            @else
                                <span class="badge">{{ strtoupper($user->role) }}</span>
                            @endif
                        </td>
                        <td>{{ date('d M Y', strtotime($user->created_at)) }}</td>
                        <td>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                            </form>
                            @else
                            <span class="text-muted" style="color: #666; font-size: 0.8rem;">(You)</span>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection