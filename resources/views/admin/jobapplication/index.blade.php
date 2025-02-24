@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">
            <i class="fas fa-briefcase mr-2"></i>Job Applications
        </h2>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Job ID</th>
                                <th>Job Title</th>
                                <th>Recruiter</th>
                                <th>Total Applications</th>
                                <th>Last Application Date</th>
                                <th width="300px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications->groupBy('job_id')->filter(function($jobApplications) {
                                return $jobApplications->count() > 0;
                            }) as $jobApplications)
                                <tr>
                                    <td>{{ $jobApplications->first()->job_id }}</td>
                                    <td>{{ $jobApplications->first()->job->title }}</td>
                                    <td>
                                            @if($jobApplications->first()->job->creator)

                                                {{ $jobApplications->first()->job->creator->name }} 

                                            @else

                                                <span class="text-muted">Unknown Recruiter</span>
                                            @endif
                                        </td>
                                    <td>{{ $jobApplications->count() }}</td>
                                    <td>{{ $jobApplications->max('created_at')->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('admin.jobapplication.application', $jobApplications->first()->job_id) }}" 
                                               class="btn btn-info rounded mr-2">
                                                <i class="fas fa-eye mr-1"></i> View Applications
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open mr-2"></i>No applications found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $applications->links('pagination::simple-bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Remove the entire updateStatus function as it's no longer needed --}}
    @endpush
@endsection