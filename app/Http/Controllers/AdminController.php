<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\AdminController;
use App\Models\Job;  // Import the Job model
use App\Models\JobApplication;  // Import the Application model
use App\Models\Notification;  // Correct import for the Notification model


class AdminController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalJobs = Job::count();
        $totalApplications = JobApplication::count();
        $totalVisitors = User::count();
        $jobApplyRate = $totalJobs > 0 ? ($totalApplications / $totalJobs) * 100 : 0;
        $totalApplications = JobApplication::count();


        // Get recent applications
        $recentApplications = JobApplication::with(['user', 'job'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent jobs
        $recentJobs = Job::with('creator')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Application status counts
        $pendingApplications = JobApplication::whereIn('status', ['pending', 'applied'])->count();
        $approvedApplications = JobApplication::where('status', 'approved')->count();
        $rejectedApplications = JobApplication::where('status', 'rejected')->count();

        // Other statistics
        $totalRecruiters = User::where('role', 1)->count();
        $activeJobs = Job::where('status', 'active')->count();
        $completedJobs = Job::where('status', 'completed')->count();

        // Fetch notifications for the authenticated user
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        // Get new users
        $newUsers = User::latest()
            ->take(5)
            ->get();

        // User activity data for chart (last 7 days)
        $activityDates = [];
        $activityCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $activityDates[] = now()->subDays($i)->format('M d');
            $activityCounts[] = JobApplication::whereDate('created_at', $date)->count();
        }

        // System status (example values - customize as needed)
        $serverLoad = 45;
        $storageUsage = 60;
        $lastBackup = now()->subHours(6)->format('Y-m-d H:i:s');
        $systemVersion = '1.0.0';

        return view('admin.dashboardA', compact(
            'totalJobs',
            'totalApplications',
            'totalVisitors',
            'jobApplyRate',
            'recentApplications',
            'recentJobs',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'totalRecruiters',
            'activeJobs',
            'completedJobs',
            'newUsers',
            'activityDates',
            'activityCounts',
            'serverLoad',
            'storageUsage',
            'lastBackup',
            'systemVersion',
            'notifications'
        ));
    }

    public function dashboard()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
    
        // Get the count of pending jobs
        $pendingJobsCount = Job::where('status', 'pending')->count();
    
        // Get the top recruiters (based on the number of jobs they have posted)
        $topRecruiters = User::whereHas('jobs')
            ->withCount('jobs')
            ->orderBy('jobs_count', 'desc')
            ->take(5)
            ->get();
    
        // Debugging: Check if variables are set
        \Log::debug('Pending Jobs Count: ' . $pendingJobsCount);
        \Log::debug('Top Recruiters: ' . $topRecruiters->toJson());
    
        // Check user role and return appropriate view
        if ($user->role === 'admin') {
            return view('admin.dashboardA', compact('pendingJobsCount', 'topRecruiters'));
        } elseif ($user->role === 'recruiter') {
            return view('recruiter.dashboardR');
        } elseif ($user->role === 'staff') {
            return view('user.dashboardU');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    // Show the form for creating a new job
    public function create()
    {
        return view('admin.create'); // Return the create job view
    }



    // Store a newly created job in the database
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'duration' => 'required|string|max:255',
            'application_deadline' => 'required|date',
        ]);

        // Create a new job listing
        Job::create($validatedData);

        return redirect()->route('admin.jobs.index')->with('success', 'Job listing created successfully');
    }

    // Delete a job from the database
    public function destroy(Job $job)
    {
        $job->delete(); 
   
        return redirect()->route('admin.jobs.index') 
                        ->with('success','Job deleted successfully'); 
    }
    
}
