@extends('layouts.user')
@section('content')
<div class="container-fluid">
    <div class="row mb-2">

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

        <div class="col-sm-6">
            <h1>My Job Applications</h1>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Job Applications</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Application History</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Job ID</th>
                                    <th>Application ID</th>
                                    <th>Job Title</th>
                                    <th>Recruiter</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td width="80px">{{ $application->job->id }}</td>
                                        <td width="80px">{{ $application->id }}</td>
                                        <td>{{ $application->job->title }}</td>
                                        <td>
                                            @if($application->job->creator)

                                                {{ $application->job->creator->name }}

                                            @else
                                                <span class="text-muted">Unknown Recruiter</span>
                                            @endif
                                        </td>
                                        <td>{{ $application->created_at->format('d M Y') }}</td>
                                        <td>
                                            @switch($application->status)
                                                @case('applied')
                                                    <span class="badge bg-warning">Applied</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-primary">Approved</span>
                                                    @break
                                                @case('accepted')
                                                    <span class="badge bg-success">Accepted</span>
                                                    @break
                                                @case('declined')
                                                    <span class="badge bg-secondary">Declined</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                    @break
                                                @case('withdrawn')
                                                    <span class="badge bg-dark">Withdrawn</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ ucfirst($application->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('user.jobapplication.application', $application->job->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Job
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            You haven't applied for any jobs yet.
                                            <br>
                                            <a href="{{ route('user.jobs.index') }}" class="btn btn-primary mt-3">
                                                Browse Available Jobs
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($applications->count() > 0)
                    <div class="card-footer">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection