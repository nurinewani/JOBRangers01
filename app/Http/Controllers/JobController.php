<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // ... your existing validation rules ...
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $job = Job::create($validatedData);
        // ... rest of your store logic ...
    }

    public function update(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            // ... your existing validation rules ...
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $job->update($validatedData);
        // ... rest of your update logic ...
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        return response()->json([
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'location' => $job->location,
                'salary' => $job->salary,
                'duration' => $job->duration,
                'start_time' => $job->start_time,
                'end_time' => $job->end_time,
                'latitude' => $job->latitude,
                'longitude' => $job->longitude,
            ]
        ]);
    }
} 