<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function getSchedules()
    {
        $schedules = Schedule::where('user_id', Auth::id())
            ->with('user')
            ->get();
        $events = [];

        foreach ($schedules as $schedule) {
            switch ($schedule->repeat) {
                case 'none':
                    $events[] = $this->createEventArray($schedule, $schedule->schedule_date);
                    break;

                case 'weekly':
                    if ($schedule->repeat_days) {
                        $repeatDays = is_array($schedule->repeat_days) 
                            ? $schedule->repeat_days 
                            : json_decode($schedule->repeat_days, true);

                        if (is_array($repeatDays)) {
                            $startDate = Carbon::now()->startOfDay();
                            $endDate = Carbon::now()->addWeeks(12)->endOfDay();

                            while ($startDate <= $endDate) {
                                $currentDayName = strtolower($startDate->format('l'));
                                if (in_array($currentDayName, $repeatDays)) {
                                    $events[] = $this->createEventArray($schedule, $startDate->format('Y-m-d'));
                                }
                                $startDate->addDay();
                            }
                        }
                    }
                    break;

                case 'daily':
                    $startDate = Carbon::now()->startOfDay();
                    $endDate = Carbon::now()->addDays(30)->endOfDay();
                    
                    while ($startDate <= $endDate) {
                        $events[] = $this->createEventArray($schedule, $startDate->format('Y-m-d'));
                        $startDate->addDay();
                    }
                    break;

                case 'yearly':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->addYears(5);
                    
                    while ($startDate <= $endDate) {
                        $events[] = $this->createEventArray($schedule, $startDate->format('Y-m-d'));
                        $startDate->addYear();
                    }
                    break;
            }
        }

        return response()->json($events);
    }

    public function getJobApplications()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with(['job', 'job.recruiter'])
            ->get()
            ->map(function ($application) {
                return [
                    'id' => 'job_' . $application->job->id,
                    'title' => '[Job] ' . $application->job->title,
                    'start' => $application->job->start_date,
                    'end' => $application->job->end_date,
                    'className' => 'job-event',
                    'allDay' => false,
                    'extendedProps' => [
                        'type' => 'job',
                        'description' => $application->job->description,
                        'recruiter' => $application->job->recruiter->name,
                        'location' => $application->job->location,
                        'salary' => $application->job->salary,
                        'duration' => $application->job->duration,
                        'status' => $application->status
                    ]
                ];
            });
        
        return response()->json($applications);
    }

    public function getUserJobs()
    {
        $userJobs = JobApplication::where('user_id', auth()->id())
            ->whereIn('status', ['approved', 'accepted'])
            ->with('job')
            ->get()
            ->map(function ($application) {
                $job = $application->job;
                
                $startDate = Carbon::parse($job->date_from);
                $endDate = Carbon::parse($job->date_to);
                $events = [];
                
                for($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    $events[] = [
                        'id' => 'job_' . $job->id . '_' . $date->format('Y-m-d'),
                        'title' => '[Job] ' . $job->title,
                        'start' => $date->format('Y-m-d') . 'T' . $job->start_time,
                        'end' => $date->format('Y-m-d') . 'T' . $job->end_time,
                        'className' => 'user-job-event',
                        'extendedProps' => [
                            'type' => 'user-job',
                            'location' => $job->location,
                            'salary' => $job->salary,
                            'duration' => $job->duration,
                            'status' => $application->status,
                            'description' => $job->description
                        ]
                    ];
                }
                
                return $events;
            })
            ->flatten(1);

        \Log::info('Processed User Jobs:', [
            'events' => $userJobs->toArray()
        ]);

        return response()->json($userJobs);
    }

    private function createEventArray($schedule, $date)
    {
        return [
            'id' => 'schedule_' . $schedule->id,
            'title' => '[Schedule] ' . $schedule->title,
            'start' => $date . 'T' . $schedule->start,
            'end' => $date . 'T' . $schedule->end,
            'className' => 'schedule-event-user',
            'allDay' => false,
            'extendedProps' => [
                'type' => 'schedule',
                'description' => $schedule->description ?? '',
                'user' => Auth::user()->name,
                'role' => Auth::user()->role
            ]
        ];
    }

    private function formatDateTime($date, $time)
    {
        return $date . 'T' . $time;
    }
}

