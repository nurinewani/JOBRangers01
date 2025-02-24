@extends('layouts.recruiter')

@section('content')
<div class="container">
<h2>Job Details</h2>
        <div class="job-details">
            <p><strong>Title:</strong> {{ $job ->title }}</p>
            <p><strong>Description:</strong> {{ $job ->description }}</p>
            <p><strong>Location:</strong> {{ $job->location }}</p>
            <p><strong>Status:</strong> <span class="fw-bold">{{ $job->getFormattedStatus() }}</span></p>
            <p><strong>Date From:</strong> {{ $job->date_from }}</p>
            <p><strong>Date To:</strong> {{ $job ->date_to }}</p>
            <p><strong>Salary (RM):</strong> {{ $job ->salary }}</p>
            <p><strong>Duration (Hours):</strong> {{ $job ->duration }}</p>
            <p><strong>Application Deadline:</strong> {{ $job ->application_deadline }}</p>

        </div>
        <!-- Back to job list link -->
        <a href="{{ route('recruiter.jobs.index') }}" class="btn btn-primary mt-3">Back to Job Listings</a>
</div>
@endsection
