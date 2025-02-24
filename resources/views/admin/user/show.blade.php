@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>User Details</h2>
            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Name:</strong>
                </div>
                <div class="col-md-9">
                    {{ $user->name }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Email:</strong>
                </div>
                <div class="col-md-9">
                    {{ $user->email }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Phone Number:</strong>
                </div>
                <div class="col-md-9">
                    {{ $user->phone_number }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Role:</strong>
                </div>
                <div class="col-md-9">
                    @if ($user->role == 0)
                        User
                    @elseif ($user->role == 1)
                        Recruiter
                    @else
                        Admin
                    @endif
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group">
                <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary">Edit User</a>
                <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger ms-2" onclick="return confirm('Are you sure?')">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
