@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>Recruiter Applications</h1>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">List of Recruiter Applications</h3>
        </div>

        <div class="card-body">
            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->email }}</td>
                                <td>{{ $request->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($request->recruiter_request_status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($request->recruiter_request_status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($request->recruiter_request_status === 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->recruiter_request_status === 'pending')
                                        <div class="btn-group">
                                            <form action="{{ route('admin.recruiter-requests.update', $request) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm mr-1">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.recruiter-requests.update', $request) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No recruiter applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 