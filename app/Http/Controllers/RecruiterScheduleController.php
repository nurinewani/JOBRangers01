<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecruiterScheduleController extends Controller
{
    public function index()
    {
        $recruiterId = Auth::id();
        
        // Get only active and upcoming jobs created by this recruiter
        $activeJobs = Job::where('recruiter_id', $recruiterId)
            ->where('status', 'active')
            ->where('date_to', '>=', now())
            ->withCount('applications')
            ->orderBy('date_from')
            ->get();
            
        $upcomingJobs = Job::where('recruiter_id', $recruiterId)
            ->where('date_from', '>', now())
            ->withCount('applications')
            ->orderBy('date_from')
            ->get();
            
        // Get only completed jobs created by this recruiter
        $completedJobs = Job::where('recruiter_id', $recruiterId)
            ->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere('date_to', '<', now());
            })
            ->withCount('applications')
            ->orderBy('date_to', 'desc')
            ->get();

        return view('recruiter.schedule', compact('activeJobs', 'upcomingJobs', 'completedJobs'));
    }
}
