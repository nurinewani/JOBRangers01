<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class CalendarController extends Controller
{
    public function events()
    {
        $jobs = Job::select(
            'id',
            'title',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'recruiter_name',
            'location',
            'salary',
            'duration',
            'status',
            'description'
        )->get();

        return response()->json($jobs);
    }
}