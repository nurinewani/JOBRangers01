<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserActivityNotification;

class RecruiterJobController extends Controller
{
    public function index(Request $request)
    {
        $recruiterId = Auth::id();
        $query = Job::where('recruiter_id', $recruiterId);
   
        // Apply status filter if selected
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
   
        // Order by creation date (newest first)
        $query->orderBy('created_at', 'desc');
   
        $jobs = $query->paginate(10);
   
        return view('recruiter.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('recruiter.jobs.create');
    }

    public function store(Request $request)
    {
        try {
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
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            // Add default status if not set
            $validatedData['status'] = 'open';

            // Create a new job
            $job = new Job($validatedData);
            $job->recruiter_id = Auth::id();
            $job->save();

            return redirect()->route('recruiter.jobs.index')
                ->with('success', 'Job created successfully!');
        } catch (\Exception $e) {
            \Log::error('Job creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create job. Please try again.');
        }
    }

    public function show(Job $job)
    {
        // Ensure the job belongs to the recruiter
        if ($job->recruiter_id !== Auth::id()) {
            return redirect()->route('recruiter.jobs.index')
                ->with('error', 'Unauthorized access');
        }

        return view('recruiter.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        // Ensure the job belongs to the recruiter
        if ($job->recruiter_id !== Auth::id()) {
            return redirect()->route('recruiter.jobs.index')
                ->with('error', 'Unauthorized access');
        }

        return view('recruiter.jobs.edit', compact('job'));
    }

    // Update an existing job in the database
    public function update(Request $request, $id)
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
            'duration' => 'nullable|string'  // Added this as it's in your database
        ]);

        $job = Job::findOrFail($id);

        // Check if the recruiter owns this job
        if ($job->recruiter_id !== auth()->id()) {
            return redirect()->route('recruiter.jobs.index')
                ->with('error', 'Unauthorized action.');
        }

        try {
            // Update the job with validated data
            $job->update($validatedData);

            return redirect()->route('recruiter.jobs.index')
                ->with('success', 'Job updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating job. Please try again.');
        }
    }

    public function pendingJobs()
    {
        $recruiterId = Auth::id();
        $pendingJobs = Job::where('recruiter_id', $recruiterId)
            ->where('status', 'pending')
            ->get();
            
        return view('recruiter.jobs.pending', compact('pendingJobs'));
    }

    public function updateStatus(Request $request, Job $job)
    {
        // Ensure the job belongs to the recruiter
        if ($job->recruiter_id !== Auth::id()) {
            return redirect()->route('recruiter.jobs.index')
                ->with('error', 'Unauthorized access');
        }

        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $job->update(['status' => $validatedData['status']]);

        return redirect()->route('recruiter.jobs.pending')
            ->with('success', 'Job status updated successfully!');
    }

    public function destroy(Job $job)
    {
        // Ensure the job belongs to the recruiter
        if ($job->recruiter_id !== Auth::id()) {
            return redirect()->route('recruiter.jobs.index')
                ->with('error', 'Unauthorized access');
        }

        $job->delete();

        return redirect()->route('recruiter.jobs.index')
            ->with('success', 'Job deleted successfully');
    }

    public function approveApplication($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new UserActivityNotification('Your job application has been approved.'));
    }
}
