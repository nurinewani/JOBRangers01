<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Http\Controllers\JobController;
use Carbon\Carbon;

class UserDiscoverController extends Controller
{
    public function index()
    {
        $currentDate = now()->format('Y-m-d');
        
        $jobs = Job::where(function($query) use ($currentDate) {
                $query->where('status', 'open')  // Only show open jobs
                      ->where('application_deadline', '>=', $currentDate)  // Only show jobs that haven't expired
                      ->where('date_to', '>=', $currentDate);  // Only show jobs that haven't ended
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Add schedule conflict check for each job
        foreach ($jobs as $job) {
            $job->hasScheduleConflict = $this->checkScheduleConflict($user, $job);
            if ($job->hasScheduleConflict) {
                $job->conflictingSchedules = $this->getConflictingSchedules($user, $job);
        }
        
        // Format dates for display
        $job->start_date = Carbon::parse($job->date_from)->format('Y-m-d');
        $job->end_date = Carbon::parse($job->date_to)->format('Y-m-d');
    }

        return view('user.discover', compact('jobs'));
    }
}