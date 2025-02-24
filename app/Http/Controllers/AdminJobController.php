<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AdminJobController;
use App\Notifications\UserActivityNotification;

class AdminJobController extends Controller
{
   // Display all jobs
   public function index(Request $request)
   {
       $query = Job::query();
   
       // Apply status filter if selected
       if ($request->has('status') && $request->status !== '') {
           $query->where('status', $request->status);
       }
   
       // Order by creation date (newest first)
       $query->orderBy('created_at', 'desc');
   
       $jobs = $query->paginate(10);
   
       return view('admin.jobs.index', compact('jobs'));
   }

   // Show the form for creating a new job
   public function create()
   {
       return view('admin.jobs.create'); // Return the create job view
   }

   public function store(Request $request)
   {
       // Validate the input data
       $validatedData = $request->validate([
           'title' => 'required|string|max:255',
           'description' => 'required|string',
           'location' => 'required|string|max:255',
           'salary' => 'required|numeric',
           'duration' => 'nullable|string|max:255',
           'application_deadline' => 'required|date',
           'date_from' => 'required|date',
           'date_to' => 'required|date|after_or_equal:date_from',
           'start_time' => 'required|date_format:H:i',
           'end_time' => 'required|date_format:H:i|after:start_time',
       ]);

       // Create a new job
       $job = new Job($validatedData);
       
       // Set the creator's ID (could be admin or recruiter)
       $job->recruiter_id = auth()->id();
       
       // Save the job
       $job->save();

       // Redirect with success message
       return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully!');
   }

    // Show the job details
    public function show(Job $job)
    {
        // Get total applications count
        $totalApplications = $job->applications()->count();
        
        // Get the selected/accepted applicant with detailed user information
        $selectedApplicant = $job->applications()
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'created_at');
            }])
            ->where('status', 'accepted')
            ->first();
        
        // Get applications by status
        $applicationStats = [
            'total' => $totalApplications,
            'applied' => $job->applications()->where('status', 'applied')->count(),
            'accepted' => $job->applications()->where('status', 'accepted')->count(),
            'rejected' => $job->applications()->where('status', 'rejected')->count(),
        ];

        return view('admin.jobs.show', compact('job', 'totalApplications', 'selectedApplicant', 'applicationStats'));
    }

    // Show the form for editing an existing job
    public function edit(Job $job)
    {
        if (!$job) {
            return redirect()->route('admin.jobs.index')->with('error', 'Job not found');
        }
        return view('admin.jobs.edit', compact('job'));
    }

    // Update an existing job in the database
    public function update(Request $request, Job $job)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'salary' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'application_deadline' => 'required|date',
            'status' => 'required|in:open,scheduled,active,completed,closed',
            'duration' => 'required',
        ]);

        try {
            // For debugging
            \Log::info('Updating job status:', [
                'job_id' => $job->id,
                'old_status' => $job->status,
                'new_status' => $request->status
            ]);

            // Update the job with validated data
            $job->update($validatedData);

            // Double-check the status update
            $job->refresh();
            \Log::info('Job status after update:', ['status' => $job->status]);

            return redirect()->route('admin.jobs.index')
                ->with('success', 'Job updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating job:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating job: ' . $e->getMessage());
        }
    }

    public function apply($jobId)
    {
    $user = auth()->user();

    // Check if the user has already applied for the job
    $existingApplication = JobApplication::where('user_id', $user->id)
        ->where('job_id', $jobId)
        ->first();

    if ($existingApplication) {
        // Redirect back with an error message
        return redirect()->back()->with('error', 'You have already applied for this job.');
    }

    // Create a new job application
    JobApplication::create([
        'user_id' => $user->id,
        'job_id' => $jobId,
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Your application has been submitted successfully!');
}


    public function pendingJobs()
    {
        $pendingJobs = Job::where('status', 'pending')->get();
        return view('admin.jobs.pending', compact('pendingJobs'));
    }

    public function approveApplication($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new UserActivityNotification('Your job application has been approved.'));
    }

    public function updateStatus(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $job->update(['status' => $validatedData['status']]);

        return redirect()->route('admin.jobs.pending')->with('success', 'Job status updated successfully!');
    }

    public function topRecruiters()
    {
        $topRecruiters = User::whereHas('jobs')
            ->withCount('jobs')
            ->orderBy('jobs_count', 'desc')
            ->take(5) // Top 5 recruiters
            ->get();
    
        return view('admin.recruiters.top', compact('topRecruiters'));
    }
    

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully');
    }

    public function close(Job $job)
    {
        $job->update(['status' => Job::STATUS_CLOSED]);
        return redirect()->route('admin.jobs.index')->with('success', 'Job has been closed successfully.');
    }

}
