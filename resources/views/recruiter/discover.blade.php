@extends('layouts.recruiter')
@section('content')
    
    @if ($message = Session::get('success'))  
        <div class="alert alert-success">         
            <p>{{ $message }}</p>    
        </div> 
    @endif 
    
    @if ($message = Session::get('error'))  
        <div class="alert alert-danger">         
            <p>{{ $message }}</p>    
        </div> 
    @endif
    
    <!-- Job List in Card Layout -->
    <div class="row">
        <h1 class="m-0">Discover Jobs & Archives</h1>
        <div class="col-12 mb-3">
        </div>


        @foreach ($jobs as $job)
            @php
                $isArchived = $job->status === 'completed' || 
                             $job->status === 'closed' || 
                             $job->application_deadline < now();
                
                $statusClass = match($job->status) {
                    'open' => 'primary',
                    'scheduled' => 'info',
                    'active' => 'success',
                    'completed' => 'secondary',
                    'closed' => 'danger',
                    default => 'secondary'
                };
            @endphp

            <div class="col-md-4 mb-2 job-card" data-status="{{ $isArchived ? 'archived' : 'active' }}">
                <div class="card {{ $isArchived ? 'bg-light' : '' }}">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="card-title mb-0" style="font-size: 1.3rem;">
                                    <strong>{{ $job->title }}</strong>
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $statusClass }} ms-2" style="font-size: 0.7rem;">{{ ucfirst($job->status) }}</span>                        
                                @if($isArchived)
                                    <span class="badge bg-secondary ms-2" style="font-size: 0.7rem;">Archived</span>
                                @endif
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="card-body">
                        <p><strong>Location:</strong> {{ $job->location }}</p>
                        <p><strong>Salary:</strong> RM {{ number_format($job->salary, 2) }}</p>
                        <p><strong>Duration:</strong> {{ $job->duration }}</p>
                        <p><strong>Application Deadline:</strong> 
                            <span class="{{ $job->application_deadline < now() ? 'text-danger' : '' }}">
                                <strong>{{ \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') }}</strong>
                            </span>
                        </p>
                    </div>

                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#jobModal{{ $job->id }}">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Job Detail Modals -->
    @foreach ($jobs as $job)
        <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $job->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Job Details</h6>
                                <p><strong>Location:</strong> {{ $job->location }}</p>
                                <p><strong>Description:</strong> {{ $job->description }}</p>
                                <p><strong>Salary:</strong> RM {{ number_format($job->salary, 2) }}</p>
                                <p><strong>Duration:</strong> {{ $job->duration }} Hours</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Dates</h6>
                                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($job->date_from)->format('d M Y') }}</p>
                                <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($job->date_to)->format('d M Y') }}</p>
                                <p><strong>Application Deadline:</strong> 
                                    <span class="{{ $job->application_deadline < now() ? 'text-danger' : '' }}">
                                        {{ \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') }}
                                    </span>
                                </p>
                                <p><strong>Posted On:</strong> {{ $job->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('styles')
<style>
    .card-header .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
        margin-left: 8px;
    }
    
    .card-title {
        margin-bottom: 0;
        margin-right: 8px;
        display: inline-block;
    }
    
    .archive-banner {
        position: absolute;
        top: 40px;
        right: -35px;
        transform: rotate(45deg);
        width: 150px;
        text-align: center;
        background-color: rgba(108, 117, 125, 0.9);
        color: white;
        padding: 5px;
    }
    
    .job-card {
        position: relative;
        overflow: hidden;
    }
    
    /* Ensure badges and text align properly */
    .d-flex.align-items-center.gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Make status badges consistent */
    .badge {
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter functionality
        $('.btn-group .btn').on('click', function() {
            // Remove active class from all buttons
            $('.btn-group .btn').removeClass('active');
            // Add active class to clicked button
            $(this).addClass('active');
            
            // Get the filter value
            var filter = $(this).data('filter');
            console.log('Filter clicked:', filter); // Debug line
            
            // Show/hide cards based on filter
            if (filter === 'all') {
                $('.job-card').show();
            } else {
                $('.job-card').hide();
                $('.job-card[data-status="' + filter + '"]').show();
            }
        });
    });
</script>
@endpush
