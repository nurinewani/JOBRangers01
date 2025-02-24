@extends('layouts.admin')

@php
    use App\Models\Job;
@endphp

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="fas fa-briefcase mr-2"></i>All Jobs
    </h2>

    @if ($message = Session::get('success'))  
        <div class="alert alert-success">         
            <p class="mb-0">{{ $message }}</p>    
        </div> 
    @endif 

    @if ($message = Session::get('error'))  
        <div class="alert alert-danger">         
            <p class="mb-0">{{ $message }}</p>    
        </div> 
    @endif

    <!-- Modify Status Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.jobs.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="status" class="mr-2">Filter by Status:</label>
                    <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open for Applications</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active/Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed/Finished</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                @if(request('status'))
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Clear Filter</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th width="150px">Salary</th>
                            <th width="250px">Status</th>
                            <th width="400px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->title }}</td>
                                <td>{{ Str::limit($job->description, 50) }}</td>
                                <td>{{ $job->location }}</td>
                                <td>RM {{ number_format($job->salary, 2) }}</td>
                                <td>
                                    @php
                                        $statusClass = match($job->status) {
                                            Job::STATUS_OPEN => 'primary',
                                            Job::STATUS_SCHEDULED => 'info',
                                            Job::STATUS_ACTIVE => 'success',
                                            Job::STATUS_COMPLETED => 'secondary',
                                            Job::STATUS_CLOSED => 'danger',
                                            default => 'primary'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ $job->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-info rounded mr-2">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-primary rounded mr-2">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        @if($job->status !== 'closed')
                                            <form action="{{ route('admin.jobs.close', $job->id) }}" method="POST" class="mr-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning rounded" onclick="return confirm('Are you sure you want to close this job?')">
                                                    <i class="fas fa-lock mr-1"></i> Close
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this job?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger rounded">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-folder-open mr-2"></i>No jobs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($jobs, 'links'))
                <div class="mt-3">
                    {{ $jobs->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
