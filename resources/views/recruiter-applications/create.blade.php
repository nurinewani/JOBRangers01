@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: rgb(47, 83, 189); color: white; font-size: 30px;">
                    <strong>{{ __('Recruiter Application') }}</strong>
                </div>
                <div class="card-body" style="font-size: 15px;">
                    @if(auth()->user()->recruiter_request_status === null || auth()->user()->recruiter_request_status === 'rejected')
                        <h5 class="mb-4">Would you like to apply to become a recruiter?</h5>
                        
                        <div class="mb-4">
                            <h6 class="text-primary"><strong>As a recruiter, you'll be able to:</strong></h6>
                            <ul>
                                <li>Post job opportunities</li>
                                <li>Manage job applications</li>
                                <li>Connect with potential candidates</li>
                                <li>Access recruiter-specific features</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary"><strong>Recruiter Responsibilities:</strong></h6>
                            <ul>
                                <li>Maintain accurate and up-to-date job postings</li>
                                <li>Respond to applicant inquiries in a timely manner</li>
                                <li>Follow our community guidelines and ethical recruitment practices</li>
                                <li>Provide clear and honest job descriptions</li>
                                <li>Protect applicant data and maintain confidentiality</li>
                                <li>Regular activity and engagement with the platform</li>
                            </ul>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            By submitting this application, you agree to fulfill these responsibilities and follow our platform's guidelines.
                        </div>

                        @if(auth()->user()->recruiter_request_status === 'rejected')
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Your previous application was not approved. Before reapplying, please ensure you understand and can commit to the responsibilities listed above.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('recruiter-applications.store') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="font-size: 18px;">
                                {{ auth()->user()->recruiter_request_status === 'rejected' ? 'Submit New Application' : 'Send Application Request' }}
                            </button>
                        </form>

                    @elseif(auth()->user()->recruiter_request_status === 'pending')
                        <div class="alert alert-info">
                            <i class="fas fa-clock mr-2"></i>
                            Your application to become a recruiter is currently under review. 
                            We'll notify you once the admin has processed your request.
                        </div>
                    @elseif(auth()->user()->recruiter_request_status === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i>
                            Congratulations! Your application has been approved. 
                            You now have recruiter privileges.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 