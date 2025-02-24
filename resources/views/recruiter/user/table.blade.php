@extends('layouts.recruiter')

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
                <div class="card-header">
                    <h3 class="card-title">Applicants List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Total Applications</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->jobApplications->count() }}</td>
                                    <td>
                                        <a href="{{ route('recruiter.user.show', $user->id) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye mr-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links() }}
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
