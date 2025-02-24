@extends('layouts.user')

@section('content')
<div class="container">

    <div class="row mb-3">
        <div class="col">
            <h2>Job Details</h2>
        </div>
    </div>

    <div class="row">
        <!-- Main Job Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color:rgb(90, 69, 0);">
                    <h4>Job Information</h4>
                </div>
                <div class="card-body">
                    <div class="job-details">
                        <p><strong>Title:</strong> {{ $job->title }}</p>
                        <p><strong>Description:</strong> {{ $job->description }}</p>
                        <p><strong>Location:</strong> {{ $job->location }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-{{ $statusClass ?? 'primary' }}">{{ $job->getFormattedStatus() }}</span></p>
                        <p><strong>Date From:</strong> {{ \Carbon\Carbon::parse($job->date_from)->format('d M Y') }}</p>
                        <p><strong>Date To:</strong> {{ \Carbon\Carbon::parse($job->date_to)->format('d M Y') }}</p>
                        <p><strong>Salary (RM):</strong> {{ number_format($job->salary, 2) }}</p>
                        <p><strong>Duration (Hours):</strong> {{ $job->duration }}</p>
                        <p><strong>Application Deadline:</strong> {{ $job->application_deadline }}</p>
                    </div>
                    <h5 class="mt-3"><strong>My Application Status</strong></h5>
                    <div class="applicant-details">
                        <p><strong>Application Status:</strong> 
                            <span class="badge bg-success">Accepted</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recruiter Information -->
        <div class="col-md-4">
            <!-- Creator Information -->
            <div class="card">
                <div class="card-header text-white" style="background-color:rgb(90, 69, 0);">
                    <h4>{{ $job->creator && $job->creator->hasRole('admin') ? 'Admin' : 'Recruiter' }} Information</h4>
                </div>
                <div class="card-body">


                    @if($job->creator)
                        <p><strong>Name:</strong> {{ $job->creator->name }}</p>
                        <p><strong>Email:</strong> {{ $job->creator->email }}</p>
                        <p><strong>Phone:</strong> {{ $job->creator->phone_number ?? 'Not provided' }}</p>
                        <p><strong>Created On:</strong> {{ $job->created_at->format('d M Y, h:i A') }}</p>
                    @else
                        <p>No creator information available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-3">
        <div class="col">
            <a href="{{ route('user.jobs.index') }}" class="btn btn-secondary">Back to Job Listings</a>
            <!-- Delete Job Button -->
            <form action="{{ route('user.jobs.destroy', $job->id) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Are you sure you want to delete this job?');">
                @csrf

                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Job</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
    }
    .badge {
        padding: 0.5em 1em;
        font-size: 85%;
    }
</style>
@endpush
