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
            <h1>Job Application Details</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.jobapplication.index') }}">My Applications</a></li>
                <li class="breadcrumb-item active">Application Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Job Details Card -->
            <div class="card">
                <div class="card-header" style="background-color:rgb(90, 69, 0); color: white;">
                    <h3 class="card-title">Job Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4>{{ $application->job->title }}</h4>
                            <p class="text-muted">{{ $application->job->company }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Location:</strong>
                            <p>{{ $application->job->location }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Salary:</strong>
                            <p>{{ $application->job->salary }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Job Description:</strong>
                            <p >{{ $application->job->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Posted Date:</strong>
                            <p>{{ $application->job->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Timeline Card -->
            <div class="card">
                <div class="card-header" style="background-color:rgb(90, 69, 0); color: white;">
                    <h3 class="card-title">Application Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Application Submitted -->
                        <div class="time-label">
                            <span class="bg-green">{{ $application->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-paper-plane bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $application->created_at->format('h:i A') }}
                                </span>
                                <h3 class="timeline-header">Application Submitted</h3>
                                <div class="timeline-body">
                                    Your application was successfully submitted.
                                </div>
                            </div>
                        </div>

                        <!-- Application Status Updates -->
                        @if($application->status === 'rejected')
                        <div class="time-label">
                            <span class="bg-red">{{ $application->updated_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-times-circle bg-red"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $application->updated_at->format('h:i A') }}
                                </span>
                                <h3 class="timeline-header">Application Rejected</h3>
                                <div class="timeline-body">
                                    We regret to inform you that your application was not successful.
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(in_array($application->status, ['approved', 'accepted', 'declined']))
                        <div class="time-label">
                            <span class="bg-green">{{ $application->updated_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-check-circle bg-success"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $application->updated_at->format('h:i A') }}
                                </span>
                                <h3 class="timeline-header">Application Approved</h3>
                                <div class="timeline-body">
                                    <p>Congratulations! Your application has been approved.</p>
                                    @if($application->status === 'approved')
                                    <div class="mt-3">
                                        <p>You've been selected for this position. Would you like to accept this offer?</p>
                                        <form action="{{ route('user.jobapplication.respond', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="response" value="accept">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Accept Offer
                                            </button>
                                        </form>
                                        <form action="{{ route('user.jobapplication.respond', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="response" value="decline">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times"></i> Decline Offer
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(in_array($application->status, ['accepted', 'declined']))
                        <div class="time-label">
                            <span class="bg-{{ $application->status === 'accepted' ? 'success' : 'danger' }}">
                                {{ $application->updated_at->format('d M Y') }}
                            </span>
                        </div>
                        <div>
                            <i class="fas {{ $application->status === 'accepted' ? 'fa-handshake bg-success' : 'fa-times-circle bg-danger' }}"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $application->updated_at->format('h:i A') }}
                                </span>
                                <h3 class="timeline-header">Offer {{ ucfirst($application->status) }}</h3>
                                <div class="timeline-body">
                                    @if($application->status === 'accepted')
                                        You have accepted the job offer.
                                    @else
                                        You have declined the job offer.
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
        <!-- Application Status Card -->
        <div class="card">
            <div class="card-header" style="background-color:rgb(90, 69, 0); color: white;">
                <h3 class="card-title">Application Status</h3>
            </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @switch($application->status)
                            @case('applied')
                                <span class="badge bg-warning p-3" style="font-size: 1.2em;">Pending Review</span>
                                <!-- Delete application button -->
                                <form action="{{ route('user.jobapplication.delete', $application->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to withdraw this application?')">
                                        <i class="fas fa-trash"></i> Withdraw Application
                                    </button>
                                </form>
                                @break

                            @case('accepted')
                                <span class="badge bg-success p-3" style="font-size: 1.2em;">Approved</span>
                                @break

                            @case('rejected')
                                <span class="badge bg-danger p-3" style="font-size: 1.2em;">Not Selected</span>
                                @break
                        @endswitch
                    </div>
                    <div class="application-info">
                        <p><strong>Applied On:</strong> {{ $application->created_at->format('d M Y, h:i A') }}</p>
                        <p><strong>Last Updated:</strong> {{ $application->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
            <!-- Contact Information Card -->  
            <div class="card">
                <div class="card-header" style="background-color:rgb(90, 69, 0); color: white;">
                    <h3 class="card-title">Contact Information</h3>
                </div>
                <div class="card-body">
                    @if($application->job->creator)

                            <p><strong>Posted by:</strong> {{ $application->job->getCreatorWithRoleAttribute() }}</p>
                            <p><i class="fas fa-envelope mr-2"></i> {{ $application->job->creator->email }}</p>
                            @if($application->job->creator->phone_number)
                                <p><i class="fas fa-phone mr-2"></i> {{ $application->job->creator->phone_number }}</p>
                            @endif
                        @else
                            <p class="text-muted">Contact information not available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .application-timeline {
        position: relative;
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .timeline-item {
        position: relative;
        padding-left: 60px;
        margin-bottom: 40px;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: 25px;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(to bottom, #e0e0e0 50%, transparent 100%);
    }

    .timeline-item:last-child:before {
        height: 50%;
        background: linear-gradient(to bottom, #e0e0e0, transparent);
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #3490dc;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .timeline-marker i {
        color: #3490dc;
        font-size: 1.4rem;
        transition: all 0.3s ease;
    }

    .timeline-content {
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        margin-left: 15px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .timeline-content:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .timeline-content h4 {
        color: #1a202c;
        margin: 0 0 12px;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .timeline-content p {
        color: #4a5568;
        margin: 0;
        line-height: 1.6;
        font-size: 1rem;
    }

    .timeline-content small {
        display: block;
        color: #718096;
        margin-top: 15px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Status-specific styles */
    .timeline-item.submitted .timeline-marker {
        border-color: #3490dc;
        background: #ebf4ff;
    }

    .timeline-item.approved .timeline-marker {
        border-color: #38c172;
        background: #f0fff4;
    }

    .timeline-item.accepted .timeline-marker {
        border-color: #38a169;
        background: #f0fff4;
    }

    .timeline-item.declined .timeline-marker {
        border-color: #718096;
        background: #f7fafc;
    }

    .timeline-item.rejected .timeline-marker {
        border-color: #e53e3e;
        background: #fff5f5;
    }

    /* Response buttons styling */
    .response-buttons {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #edf2f7;
    }

    .response-buttons p {
        margin-bottom: 15px;
        font-weight: 500;
    }

    .response-buttons .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-success {
        background: linear-gradient(45deg, #38a169, #48bb78);
        border: none;
        box-shadow: 0 4px 12px rgba(56, 161, 105, 0.2);
    }

    .btn-danger {
        background: linear-gradient(45deg, #e53e3e, #f56565);
        border: none;
        box-shadow: 0 4px 12px rgba(229, 62, 62, 0.2);
    }

    .response-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: auto;
    }

    .status-badge i {
        margin-right: 6px;
        font-size: 0.75rem;
    }

    /* Timeline animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .timeline-item {
        animation: slideIn 0.5s ease forwards;
        opacity: 0;
    }

    .timeline-item:nth-child(1) { animation-delay: 0.2s; }
    .timeline-item:nth-child(2) { animation-delay: 0.4s; }
    .timeline-item:nth-child(3) { animation-delay: 0.6s; }
    .timeline-item:nth-child(4) { animation-delay: 0.8s; }

    /* Responsive design */
    @media (max-width: 768px) {
        .application-timeline {
            padding: 0 15px;
        }

        .timeline-item {
            padding-left: 45px;
        }

        .timeline-marker {
            width: 40px;
            height: 40px;
        }

        .timeline-content {
            padding: 20px;
        }
    }
</style>
@endpush
