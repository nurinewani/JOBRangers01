<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserActivityNotification;
use Illuminate\Support\Facades\Log;

class RecruiterJobApplicationController extends Controller
{
    public function index()
    {
        $recruiterId = Auth::id();
        
        // Get applications for jobs posted by this recruiter
        $applications = JobApplication::whereHas('job', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })->with(['job', 'user'])
        ->latest()
        ->paginate(10);

        $totalApplications = JobApplication::whereHas('job', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })->count();

        $pendingApplications = JobApplication::whereHas('job', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })->whereIn('status', ['pending', 'applied'])->count();

        return view('recruiter.jobapplication.index', compact('applications', 'totalApplications', 'pendingApplications'));
    }

    public function show($id)
    {
        $recruiterId = Auth::id();
        
        // Find the application and ensure it belongs to one of this recruiter's jobs
        $application = JobApplication::whereHas('job', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })
        ->with(['job', 'user'])
        ->findOrFail($id);

        $job = $application->job;
        
        // Get all applications for this job
        $applications = JobApplication::with('user')
            ->where('job_id', $job->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('recruiter.jobapplication.application', compact('applications', 'job'));
    }

    public function showApplications($job)
    {
        $recruiterId = Auth::id();
        $job = Job::where('recruiter_id', $recruiterId)->findOrFail($job);
        
        $applications = JobApplication::with('user')
            ->where('job_id', $job->id)
            ->get();
            
        return view('recruiter.jobapplication.application', compact('job', 'applications'));
    }

    public function updateApplicationStatus(Request $request, $applicationId)
    {
        try {
            $application = JobApplication::findOrFail($applicationId);
            
            // Verify that this job belongs to the authenticated recruiter
            if ($application->job->recruiter_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            if ($request->status === 'approved') {
                // Change this line - use 'approved' instead of 'accepted'
                $application->status = 'approved';  // NOT 'accepted'
                $application->save();
                
                $message = 'Application has been approved and is waiting for user response.';
            } elseif ($request->status === 'rejected') {
                $application->status = 'rejected';
                $application->save();
                $message = 'Application has been rejected.';
            }
            
            return redirect()->back()->with('success', $message);
                            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update application status: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $recruiterId = Auth::id();
        
        // Find the application and ensure it belongs to one of this recruiter's jobs
        $application = JobApplication::whereHas('job', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })
        ->with(['job', 'user'])
        ->findOrFail($id);

        return view('recruiter.jobapplication.edit', compact('application'));
    }

    public function update(Request $request, $id)
    {
        $recruiterId = Auth::id();
        
        // Find the application and ensure it belongs to one of this recruiter's jobs
        $application = JobApplication::whereHas('job', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })->findOrFail($id);
        
        // Validate the request data
        $validated = $request->validate([
            'status' => 'required|in:pending,applied,approved,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        // Update the application
        $application->update($validated);

        // Create notification for the applicant
        Notification::create([
            'type' => 'application_update',
            'message' => "Your application for '{$application->job->title}' has been updated.",
            'user_id' => $application->user_id,
        ]);

        return redirect()->route('recruiter.jobapplications.index')
            ->with('success', 'Application updated successfully');
    }

    public function approveApplication($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new UserActivityNotification('Your job application has been approved.'));
    }

    public function application(Job $job)
    {
        // Make sure the job belongs to the current recruiter
        if ($job->recruiter_id !== auth()->id()) {
            return redirect()->route('recruiter.jobs.index')
                ->with('error', 'Unauthorized access');
        }

        $applications = $job->jobApplications()->with('user')->get();
        return view('recruiter.jobapplication.application', compact('job', 'applications'));
    }
}
