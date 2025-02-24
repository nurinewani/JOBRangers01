@extends('layouts.recruiter')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-clock mr-2"></i>Job Schedules</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#active" data-toggle="tab">Active & Upcoming Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#completed" data-toggle="tab">Completed</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Active & Upcoming Jobs Tab -->
                        <div class="tab-pane fade show active" id="active">
                            @if($activeJobs->isEmpty() && $upcomingJobs->isEmpty())
                                <p class="text-muted text-center py-3">No active or upcoming jobs found.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Application Deadline</th>
                                                <th>Status</th>
                                                <th>Applications</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activeJobs->merge($upcomingJobs)->sortBy('date_from') as $job)
                                                <tr>
                                                    <td>{{ $job->title }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->date_from)->format('d M Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->date_to)->format('d M Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') }}</td>
                                                    <td>
                                                        @if(\Carbon\Carbon::parse($job->date_from)->isFuture())
                                                            <span class="badge badge-info" style="font-size: 12px;">Upcoming</span>
                                                        @else
                                                            <span class="badge badge-success" style="font-size: 12px;">Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('recruiter.jobapplication.application', $job->id) }}" 
                                                           class="btn btn-info btn-sm" style="font-size: 12px;">
                                                            <strong>View ({{ $job->applications_count }})</strong>
                                                        </a>
                                                    </td>
                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Completed Jobs Tab -->
                        <div class="tab-pane fade" id="completed">
                            @if($completedJobs->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="h5 text-muted">No completed jobs found yet.</p>
                                    <p class="text-muted">Completed jobs will appear here once they are finished.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Total Applications</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($completedJobs as $job)
                                                <tr>
                                                    <td>{{ $job->title }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->date_from)->format('d M Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->date_to)->format('d M Y') }}</td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $job->applications_count }} Applications
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">Completed</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('recruiter.jobapplication.application', $job->id) }}" 
                                                           class="btn btn-secondary btn-sm">
                                                            View History
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        padding: 0.5em 1em;
    }
    .table td {
        vertical-align: middle;
    }
    .text-danger {
        font-weight: bold;
    }
</style>
@endpush
@endsection
