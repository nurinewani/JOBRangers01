<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Notification;
use App\Models\Job;
use Illuminate\Http\Request;

class UserJobApplicationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch job applications for the current user with job details
        $applications = JobApplication::with('job')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.jobapplication.index', compact('applications'));
    }

    public function showApplications($id)
    {
        // Fetch the specific job application for the authenticated user
        $application = JobApplication::with(['job', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.jobapplication.application', compact('application'));
    }

    public function application($jobId)
    {
        // Fetch the specific job application for the authenticated user
        $application = JobApplication::with(['job', 'user'])
            ->where('user_id', auth()->id())
            ->where('job_id', $jobId)
            ->firstOrFail();

        return view('user.jobapplication.application', compact('application'));
    }

    public function apply($jobId)
    {
        $user = auth()->user();
        $job = Job::findOrFail($jobId);

        // Check if user has already applied
        $existingApplication = JobApplication::where('user_id', $user->id)
            ->where('job_id', $jobId)
            ->first();

        if ($existingApplication) {
            return redirect()->back()
                ->with('error', 'You have already applied for this job.');
        }

        // Create new application
        $application = new JobApplication();
        $application->user_id = $user->id;
        $application->job_id = $jobId;
        $application->status = JobApplication::STATUS_APPLIED;
        $application->save();

        // Create notification (make sure these column names match your notifications table)
        try {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Job Application Submitted',
                'description' => "You have successfully applied for {$job->title}", // Change 'message' to 'description' if that's your column name
                'type' => 'application'
            ]);
        } catch (\Exception $e) {
            // Application is saved but notification failed - still consider it a success
            return redirect()->route('user.jobapplication.index')
                ->with('success', 'Your job application has been submitted successfully!');
        }

        return redirect()->route('user.jobapplication.index')
            ->with('success', 'Your job application has been submitted successfully!');
    }

    public function delete($id)
    {
        $application = JobApplication::findOrFail($id);
        
        // Check if the application belongs to the authenticated user
        if ($application->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Only allow deletion if status is 'applied'
        if ($application->status !== JobApplication::STATUS_APPLIED) {
            return redirect()->back()->with('error', 'Cannot withdraw application at this stage.');
        }

        $application->delete();

        return redirect()->route('user.jobapplication.index')
            ->with('success', 'Application withdrawn successfully.');
    }

    public function respond(Request $request, $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            
            // Verify this application belongs to the authenticated user
            if ($application->user_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
            
            // Can only respond if application is in 'approved' status
            if ($application->status !== 'approved') {
                return redirect()->back()
                    ->with('error', 'You can only respond to approved applications.');
            }

            // Update status based on user's response
            $application->status = $request->response === 'accept' ? 'accepted' : 'declined';
            $application->save();

            $message = $request->response === 'accept' 
                ? 'You have accepted the job offer.' 
                : 'You have declined the job offer.';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error processing your response: ' . $e->getMessage());
        }
    }
}
