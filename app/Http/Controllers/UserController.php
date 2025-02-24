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
use App\Notifications\UserActivityNotification;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Retrieve authenticated user
        $today = now()->format('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format
    
        // Fetch the user's schedule for today
        $todaysSchedule = $user->schedules()->whereDate('schedule_date', $today)->get();
    
        // Dashboard statistics
        $totalJobs = Job::count();
        $jobApplyRate = $totalJobs ? (JobApplication::count() / $totalJobs) * 100 : 0;
        $totalApplications = JobApplication::count();
        $totalVisitors = 1234; // Example static value
    
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
    
        return view('user.home', [
            'todaysSchedule' => $todaysSchedule,
            'recentApplications' => $recentApplications,
            'notifications' => $notifications,
            'totalJobs' => $totalJobs,
            'jobApplyRate' => $jobApplyRate,
            'totalApplications' => $totalApplications,
            'totalVisitors' => $totalVisitors,
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
            return view('user.dashboardU');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    
    public function create()
    {

    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
            
        DB::table('users')->insert([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');

    }

    public function approveApplication($userId)
    {
        $user = User::findOrFail($userId);
        $user->notify(new UserActivityNotification('Your job application has been approved.'));
    }

    // Show the job details
    public function show(User $users)
    {
        return view('users.show', compact('user'));
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
        $users->delete();
        return redirect()->route('user.index')
            ->with('success', 'User deleted successfully');

    }
}
