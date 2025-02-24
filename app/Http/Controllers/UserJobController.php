<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\JobController;
use App\Notifications\UserActivityNotification;
use App\Models\JobApplication;

class UserJobController extends Controller
{
   // Display all jobs
   public function index()
   {
       $currentDate = now()->format('Y-m-d');
       
       // Get the current user's ID
       $userId = auth()->id();

       // Get jobs through JobApplications where status is 'accepted'
       $jobs = Job::whereHas('applications', function($query) use ($userId) {
           $query->where('user_id', $userId)
                 ->where('status', JobApplication::STATUS_ACCEPTED);
       })
       ->with(['applications' => function($query) use ($userId) {
           $query->where('user_id', $userId)
                 ->where('status', JobApplication::STATUS_ACCEPTED);
       }])
       ->orderBy('created_at', 'desc')
       ->paginate(10);

       return view('user.jobs.index', compact('jobs'));
   }

   // Show the form for creating a new job
   public function create()
   {
       return view('user.jobs.create'); // Return the create job view
   }

   public function store(Request $request)
   {
       // Validate the input data
       $validatedData = $request->validate([
           'title' => 'required|string|max:255',
           'description' => 'required|string',
           'location' => 'required|string|max:255',
           'salary' => 'required|numeric',
           'duration' => 'required|string|max:255',
           'application_deadline' => 'required|date',
           'date_from' => 'required|date',
           'date_to' => 'required|date|after_or_equal:date_from',
           'latitude' => 'required|numeric',  // Ensure latitude is numeric
           'longitude' => 'required|numeric', // Ensure longitude is numeric
       ]);
   
       // Create a new job and assign validated data
       $job = new Job();
       $job->title = $validatedData['title'];
       $job->description = $validatedData['description'];
       $job->location = $validatedData['location'];
       $job->salary = $validatedData['salary'];
       $job->duration = $validatedData['duration'];
       $job->application_deadline = $validatedData['application_deadline'];
       $job->date_from = $validatedData['date_from'];
       $job->date_to = $validatedData['date_to'];
   
       // Add latitude and longitude to the job
       $job->latitude = $validatedData['latitude'];
       $job->longitude = $validatedData['longitude'];
   
       // Save the job record
       $job->save();
   
       // Redirect with success message
       return redirect()->route('user.jobs.index')->with('success', 'Job created successfully!');
   }
   

    // Show the job details
    public function show($id)
    {
        $currentDate = now()->format('Y-m-d');
        
        $job = Job::findOrFail($id);
        
        // Check if job is expired or not open
        if ($job->application_deadline < $currentDate || $job->status !== 'open') {
            return redirect()->back()
                ->with('error', 'This job posting is no longer available for applications.');
        }
        
        return view('user.jobs.show', compact('job'));
    }

    // Show the form for editing an existing job
    public function edit(Job $job)
    {
        return view('user.jobs.edit', compact('job')); // Make sure the job is being passed correctly
    }

    // Update an existing job in the database
    public function update(Request $request, Job $job)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'duration' => 'required|string|max:255',
            'application_deadline' => 'required|date',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from', // Ensures 'date_to' is not before 'date_from'
        ]);
        

        // Update the job
        $job->update($validatedData);

        // Redirect with success message
        return redirect()->route('user.jobs.index')->with('success', 'Job updated successfully!');
    }

    // Add this new method for the discover page
    public function discover()
    {
        $user = auth()->user();
        $currentDate = now()->format('Y-m-d');

        $jobs = Job::where('status', '!=', 'closed')
                   ->where('application_deadline', '>=', $currentDate)
                   ->get();

        foreach ($jobs as $job) {
            // Check for schedule conflicts
            $conflictingSchedules = Schedule::where('user_id', $user->id)
                ->where(function ($query) use ($job) {
                    $query->where(function ($q) use ($job) {
                        // Check if schedule overlaps with job time
                        $q->whereTime('start', '<=', $job->end_time)
                          ->whereTime('end', '>=', $job->start_time);
                    })
                    ->where(function ($q) use ($job) {
                        // For one-time events
                        $q->where('repeat', 'none')
                          ->whereBetween('schedule_date', [$job->date_from, $job->date_to]);
                    })
                    ->orWhere(function ($q) use ($job) {
                        // For daily events
                        $q->where('repeat', 'daily');
                    })
                    ->orWhere(function ($q) use ($job) {
                        // For weekly events
                        $q->where('repeat', 'weekly')
                          ->where(function($wq) use ($job) {
                            $startDate = Carbon::parse($job->date_from);
                            $endDate = Carbon::parse($job->date_to);
                            $days = [];
                            
                            // Get all days between job start and end date
                            for($date = $startDate; $date->lte($endDate); $date->addDay()) {
                                $days[] = strtolower($date->format('l'));
                            }
                            
                            // Check if any schedule's repeat_days intersect with job days
                            $wq->whereJsonContains('repeat_days', $days);
                        });
                    });
                })
                ->get();

            $job->hasScheduleConflict = $conflictingSchedules->isNotEmpty();
            $job->conflictingSchedules = $conflictingSchedules;
        }

        return view('user.discover', compact('jobs'));
    }

    // Modify the existing apply method to check for conflicts
    public function apply($jobId)
    {
        $user = auth()->user();
        $job = Job::findOrFail($jobId);
    
        // Check if the user has already applied for the job
        $existingApplication = JobApplication::where('user_id', $user->id)
            ->where('job_id', $jobId)
            ->first();
    
        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        // Check for schedule conflicts
        $conflictingSchedules = Schedule::where('user_id', $user->id)
            ->where(function ($query) use ($job) {
                $query->where(function ($q) use ($job) {
                    $q->whereTime('start', '<=', $job->end_time)
                      ->whereTime('end', '>=', $job->start_time);
                })
                ->where(function ($q) use ($job) {
                    $q->where('repeat', 'none')
                      ->whereBetween('schedule_date', [$job->date_from, $job->date_to]);
                })
                ->orWhere('repeat', 'daily')
                ->orWhere(function ($q) use ($job) {
                    $q->where('repeat', 'weekly')
                      ->where(function($wq) use ($job) {
                        $startDate = Carbon::parse($job->date_from);
                        $endDate = Carbon::parse($job->date_to);
                        $days = [];
                        
                        for($date = $startDate; $date->lte($endDate); $date->addDay()) {
                            $days[] = strtolower($date->format('l'));
                        }
                        
                        $wq->whereJsonContains('repeat_days', $days);
                    });
                });
            })
            ->exists();

        if ($conflictingSchedules) {
            return redirect()->back()->with('error', 'You have schedules that conflicts with this job.');
        }
    
        // Create a new job application with initial status as 'applied'
        JobApplication::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'status' => JobApplication::STATUS_APPLIED
        ]);
    
        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    }
    

    public function approveApplication($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new UserActivityNotification('Your job application has been approved.'));
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('user.jobs.index')->with('success', 'Job deleted successfully');
    }

    public function showMap()
    {
        return view('user.jobs.jobmap');
    }

    // Add method to accept an approved application
    public function acceptOffer($applicationId)
    {
        $application = JobApplication::where('id', $applicationId)
            ->where('user_id', auth()->id())
            ->where('status', JobApplication::STATUS_APPROVED)
            ->firstOrFail();

        $application->update([
            'status' => JobApplication::STATUS_ACCEPTED
        ]);

        return redirect()->back()->with('success', 'Job offer accepted successfully!');
    }

    // Modify the complete method to check for accepted status
    public function complete(JobApplication $application)
    {
        if ($application->user_id !== auth()->id() || $application->status !== JobApplication::STATUS_ACCEPTED) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $application->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('user.jobs.index')->with('success', 'Job marked as completed successfully!');
    }

    // If you have a search function
    public function search(Request $request)
    {
        $query = Job::query();
        
        if ($request->has('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }
        
        // Always exclude closed jobs from search results
        $query->where(function($q) {
            $q->where('status', 'open')
              ->orWhere('status', 'active');
        });
        
        $jobs = $query->latest()->paginate(10);
        return view('user.jobs.index', compact('jobs'));
    }
}
