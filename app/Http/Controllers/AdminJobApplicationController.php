<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Notification; // Add the Notification model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserActivityNotification;
use Illuminate\Support\Facades\Log;

class AdminJobApplicationController extends Controller
{
    // Display available jobs
    public function index()
    {
        // Get total applications (all time)
        $totalApplications = JobApplication::count();
        
        // Get active applications (those with status 'pending' or 'applied')
        $pendingApplications = JobApplication::whereIn('status', ['pending', 'applied'])->count();

        // Fetch all job applications with related job and user information
        $applications = JobApplication::with(['job', 'user'])
            ->latest()
            ->paginate(10); // Paginate results, 10 per page

        return view('admin.jobapplication.index', compact('applications', 'totalApplications', 'pendingApplications'));
    }

    // Apply for a job
    public function apply($jobId)
    {
        $user = Auth::user(); // Get the currently authenticated user
        
        // Check if the user has already applied for this job
        $existingApplication = JobApplication::where('user_id', $user->id)
                                              ->where('job_id', $jobId)
                                              ->first();
        
        if ($existingApplication) {
            return redirect()->route('admin.jobapplication.index')->with('error', 'You have already applied for this job.');
        }

        // Create the job application
        $application = JobApplication::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'status' => 'applied',
        ]);

        // Notify the recruiter/admin
        $job = Job::findOrFail($jobId);
        $recruiter = $job->recruiter; // Assuming a `recruiter` relationship exists on the Job model
        if ($recruiter) {
            Notification::create([
                'type' => 'job_application',
                'message' => "User {$user->name} applied for the job '{$job->title}'.",
                'user_id' => $recruiter->id,
            ]);
        }

        return redirect()->route('admin.jobapplication.index')->with('success', 'Your application has been submitted!');
    }

    // Show single application details
    public function show($id)
    {
        $application = JobApplication::with(['job', 'user'])->findOrFail($id);
        $job = $application->job;
        
        // Get all applications for this job
        $applications = JobApplication::with('user')
            ->where('job_id', $job->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.jobapplication.application', compact('applications', 'job'));
    }

    // Display the list of applications for recruiters/admins (if you still need this)
    public function showApplications($job)
    {
        $job = Job::findOrFail($job);
        $applications = JobApplication::with('user')
            ->where('job_id', $job->id)
            ->get();
            
        return view('admin.jobapplication.application', compact('job', 'applications'));
    }

    // Accept or reject a job application
    public function updateApplicationStatus(Request $request, $applicationId)
    {
        try {
            $application = JobApplication::findOrFail($applicationId);
            $job = Job::findOrFail($application->job_id);
            
            if ($request->status === 'approved') {
                // Update application status
                $application->status = 'accepted';
                $application->save();
                
                // Update the job status to 'scheduled'
                $job->status = 'scheduled';
                $job->save();
                
                // Reject all other applications
                JobApplication::where('job_id', $application->job_id)
                    ->where('id', '!=', $applicationId)
                    ->update(['status' => 'rejected']);
                    
                $message = 'Application has been approved. Job status updated to scheduled.';
                
            } elseif ($request->status === 'rejected') {
                // If rejecting a previously accepted application
                if ($application->status === 'accepted') {
                    // Update job status back to open
                    $job->status = 'open';
                    $job->save();
                    
                    // Update the application status to rejected
                    $application->status = 'rejected';
                    $application->save();
                    
                    $message = 'Application has been rejected. Job has been reopened for new applications.';
                } else {
                    // Regular rejection for non-accepted applications
                    $application->status = 'rejected';
                    $application->save();
                    $message = 'Application has been rejected.';
                }
            }
            
            return redirect()->route('admin.jobapplication.application', ['job' => $application->job_id])
                            ->with('success', $message);
                            
        } catch (\Exception $e) {
            Log::error('Error in updateApplicationStatus', [
                'error' => $e->getMessage(),
                'applicationId' => $applicationId
            ]);
            
            return redirect()->back()
                            ->with('error', 'Failed to update application status: ' . $e->getMessage());
        }
    }

    public function approveApplication($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new UserActivityNotification('Your job application has been approved.'));
    }

    public function edit($id)
    {
        $application = JobApplication::with(['job', 'user'])->findOrFail($id);
        return view('admin.jobapplication.edit', compact('application'));
    }

    public function update(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        
        // Validate the request data
        $validated = $request->validate([
            'status' => 'required|in:pending,applied,approved,rejected',
            'notes' => 'nullable|string|max:500',
            // Add any other fields you want to be editable
        ]);

        // Update the application
        $application->update($validated);

        // Create notification for the applicant
        Notification::create([
            'type' => 'application_update',
            'message' => "Your application for '{$application->job->title}' has been updated.",
            'user_id' => $application->user_id,
        ]);

        return redirect()->route('admin.jobapplications.index')
            ->with('success', 'Application updated successfully');
    }
}
