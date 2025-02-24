<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use Carbon\Carbon;

class RecruiterCalendarController extends Controller
{
    public function index()
    {
        // Add debugging
        \Log::info('Accessing recruiter calendar index method');
        try {
            return view('recruiter.calendar');
        } catch (\Exception $e) {
            \Log::error('Error loading calendar view: ' . $e->getMessage());
            return redirect()->route('recruiter.dashboardR')->with('error', 'Unable to load calendar');
        }
    }


    public function getEvents()
    {
        $jobs = Job::with('creator')->get();

        $events = $jobs->map(function ($job) {
            // Format the dates and times correctly
            $startDate = Carbon::parse($job->date_from)->format('Y-m-d');
            $endDate = Carbon::parse($job->date_to)->format('Y-m-d');
            $startTime = $job->start_time ? Carbon::parse($job->start_time)->format('H:i:s') : '00:00:00';
            $endTime = $job->end_time ? Carbon::parse($job->end_time)->format('H:i:s') : '23:59:59';

            return [
                'id' => $job->id,
                'title' => $job->title,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'recruiter_name' => $job->creator ? $job->creator->name : 'N/A',
                'location' => $job->location,
                'salary' => $job->salary,
                'duration' => $job->duration,
                'status' => $job->status ?? 'pending',
                'description' => $job->description,
                'backgroundColor' => $this->getStatusColor($job->status),
                'borderColor' => $this->getStatusColor($job->status),
                'textColor' => '#ffffff',
                'className' => 'job-status-' . strtolower($job->status ?? 'pending')
            ];
        });

        return response()->json($events);
    }

    private function getStatusColor($status)
    {
        return match (strtolower($status)) {
            'active' => '#4CAF50',    // Green
            'pending' => '#FFC107',   // Yellow
            'completed' => '#9E9E9E', // Grey
            default => '#FF9800'      // Orange (default)
        };
    }
}

