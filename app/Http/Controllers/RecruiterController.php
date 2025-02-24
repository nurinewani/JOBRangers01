<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Models\Job;  // Import the Job model
use App\Models\JobApplication;  // Import the Application model
use App\Models\Notification;  // Correct import for the Notification model
use Carbon\Carbon;

class RecruiterController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalJobs = Job::count();
        $totalApplications = JobApplication::count();
        $totalVisitors = User::count();
        $jobApplyRate = $totalJobs > 0 ? ($totalApplications / $totalJobs) * 100 : 0;


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

        return view('recruiter.dashboardR', compact(
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
        $user = auth()->user();
        $now = Carbon::now();

        // Job Statistics
        $jobs = Job::where('recruiter_id', $user->id);
        $totalJobs = $jobs->count();

        // Job Applications
        $jobIds = $jobs->pluck('id');
        $applications = JobApplication::whereIn('job_id', $jobIds);
        
        // Calculate statistics
        $stats = [
            'total_jobs' => $totalJobs,
            'active_jobs' => Job::where('recruiter_id', $user->id)
                               ->where('status', 'active')
                               ->count(),
            'completed_jobs' => Job::where('recruiter_id', $user->id)
                                  ->where('status', 'completed')
                                  ->count(),
            'total_applications' => $applications->count(),
            'active_applications' => $applications->where('status', 'pending')->count(),
            'total_recruiters' => User::where('role', 2)->count(),
            'job_apply_rate' => $totalJobs > 0 
                ? round(($applications->count() / $totalJobs) * 100, 2)
                : 0,
            'total_visitors' => \DB::table('visitors')->count() // If you're tracking visitors
        ];

        // Recent Job Applications
        $recent_applications = JobApplication::whereIn('job_id', $jobIds)
            ->with(['user', 'job'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Job Listings
        $recent_jobs = Job::where('recruiter_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('recruiter.dashboard', compact('stats', 'recent_applications', 'recent_jobs'));
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

        return redirect()->route('recruiter.jobs.index')->with('success', 'Job listing created successfully');
    }


    // Delete a job from the database
    public function destroy(Job $job)
    {
        $job->delete(); 
   
        return redirect()->route('recruiter.jobs.index') 
                        ->with('success','Job deleted successfully'); 

    }
    
}
