@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User Management</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header" style="background-color:rgb(0, 37, 68); color: white;">
                    <h3 class="card-title">Users List</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.user.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-user-plus"></i> Add New User
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Success!</h5>
                            {{ $message }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="220px">Email</th>
                                    <th width="180px">Phone Number</th>
                                    <th width="180px">Role</th>
                                    <th width="220px">Recruiter Request Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>
                                            @if ($user->role == 0)
                                                <span class="badge badge-warning" style="color: #000; font-size: 12px;">User</span>
                                            @elseif ($user->role == 1)
                                                <span class="badge badge-success" style="font-size: 12px;">Recruiter</span>
                                            @else
                                                <span class="badge badge-primary" style="font-size: 12px;">Admin</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->recruiter_request_status === 'pending')
                                                <span class="badge badge-warning">Pending Recruiter</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" style="gap: 10px;">
                                                <a href="{{ route('admin.user.edit', $user) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.user.destroy', $user) }}" 
                                                      method="POST" 
                                                      style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    .role-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
        text-align: center;
    }
    
    .role-pending {
        background-color: #ffc107;  /* Warning Yellow */
        color: #000;
    }

    .btn-group {
        gap: 10px;
    }
    
    .btn-sm {
        margin-right: 5px;
    }
</style>
@endpush
@endsection
