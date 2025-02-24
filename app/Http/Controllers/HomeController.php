<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\UserLog;
use App\Models\Job;  // Import the Job model
use App\Models\JobApplication;  // Import the Application model
use App\Models\Notification;  // Correct import for the Notification model
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $today = now()->format('d-m-Y');
        $user = auth()->user();
        
        // Initialize all application counters
        $totalApplications = JobApplication::count();
        $appliedApplications = JobApplication::where('status', 'applied')->count();
        $activeApplications = JobApplication::whereIn('status', ['approved', 'accepted'])->count();
        $rejectedApplications = JobApplication::where('status', 'rejected')->count();

        // Only calculate if user is logged in and is a regular user
        if ($user->role === 0) { // Regular user
            $totalApplications = JobApplication::where('user_id', $user->id)->count();
            $appliedApplications = JobApplication::where('user_id', $user->id)
                ->where('status', 'applied')
                ->count();
            $activeApplications = JobApplication::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'accepted'])
                ->count();
            $rejectedApplications = JobApplication::where('user_id', $user->id)
                ->where('status', 'rejected')
                ->count();
        }

        $today = now()->format('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format
    
        // Fetch user's application statistics
        $totalJobs = Job::count();
        $jobApplyRate = $totalJobs ? (JobApplication::count() / $totalJobs) * 100 : 0;
        $totalVisitors = User::count();
    
        // Fetch recent applications for the current user
        $recentUserApplications = JobApplication::with(['job'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
    
        // Fetch today's schedule from the user's calendar
        $todaysSchedule = Schedule::where('user_id', $user->id)
            ->where(function($query) use ($today) {
                $query->whereNull('schedule_date')  // For schedules without specific dates
                      ->orWhereDate('schedule_date', $today);
            })
            ->orderBy('start', 'asc')
            ->get()
            ->map(function ($schedule) {
                return (object)[
                    'start_time' => Carbon::parse($schedule->start)->format('H:i'),
                    'end_time' => Carbon::parse($schedule->end)->format('H:i'),
                    'title' => $schedule->title,
                    'description' => $schedule->description ?? 'No description'
                ];
            });
    
        // Debug the results
        \Log::info('Schedule query results:', [
            'user_id' => $user->id,
            'today' => $today,
            'schedules' => $todaysSchedule->toArray()
        ]);
    
        // Fetch recent job applications
        $recentApplications = JobApplication::with(['user', 'job'])
            ->latest()
            ->take(5)
            ->get();
    
        // Fetch notifications for the authenticated user
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    
        return view('/home', [
            'todaysSchedule' => $todaysSchedule,
            'recentApplications' => $recentApplications,
            'notifications' => $notifications,
            'totalJobs' => $totalJobs,
            'jobApplyRate' => $jobApplyRate,
            'totalApplications' => $totalApplications,
            'totalVisitors' => $totalVisitors,
            'appliedApplications' => $appliedApplications,
            'activeApplications' => $activeApplications,
            'rejectedApplications' => $rejectedApplications,
            'recentUserApplications' => $recentUserApplications,
        ]);
    }
    

    public function dashboard()
    {
        $user = Auth::user(); // Retrieve authenticated user
    
        // Check user role and return appropriate view
        if ($user->role === 'admin') {
            return view('admin.dashboardA');
        } elseif ($user->role === 'recruiter') {
            return view('recruiter.dashboardR');
        } elseif ($user->role === 'staff') {
            return view('/home');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    
    public function create()
    {

    }

    public function store(Request $request)
    {
        //

    }

    // Show the job details
    public function show(User $users)
    {
        //
    }

    // Show the form for editing an existing job
    public function edit(User $users)
    {

    }

    // Update an existing job in the database
    public function update(Request $request, User $users)
    {
        
    }

    // Delete a job from the database
    public function destroy(User $users)
    {
        //
    }
}
