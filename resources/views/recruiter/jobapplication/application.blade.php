@extends('layouts.recruiter')

@section('content')
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h2>
                    <i class="fas fa-file-alt mr-2"></i>Applications for: {{ $job->title }}
                </h2>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($applications->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open mr-2"></i>No applications found for this job
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Applicant Name</th>
                                    <th>Email Address</th>
                                    <th>Application Date</th>
                                    <th>Status</th>
                                    <th width="400px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $index => $application)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $application->user->name }}</td>
                                        <td>{{ $application->user->email }}</td>
                                        <td>{{ $application->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'applied' => 'bg-warning text-dark',
                                                    'approved' => 'bg-primary text-white',
                                                    'accepted' => 'bg-success text-white',
                                                    'rejected' => 'bg-danger text-white'
                                                ][$application->status] ?? 'bg-secondary text-white';
                                            @endphp

                                            <span class="badge {{ $statusClass }}" style="font-size: 14px;">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('recruiter.user.show', $application->user_id) }}" 
                                                   class="btn btn-info mr-1">
                                                    <i class="fas fa-user"></i> View Profile
                                                </a>

                                                <form action="{{ route('recruiter.jobApplications.updateStatus', ['applicationId' => $application->id]) }}" 
                                                    method="POST" 
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('PUT')  {{-- This line is crucial for PUT requests --}}
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-success mr-1"
                                                    onclick="return confirm('Are you sure you want to approve this application?')">
                                                    <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>


                                                <form action="{{ route('recruiter.jobApplications.updateStatus', ['applicationId' => $application->id]) }}" 
                                                    method="POST" 
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('PUT')  {{-- This line is crucial for PUT requests --}}
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" 
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to reject this application?')">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col">
                        <a href="{{ route('recruiter.jobapplication.index') }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Applications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
