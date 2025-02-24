<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Http\Controllers\JobController;

class RecruiterDiscoverController extends Controller
{
    public function index()
    {
        $jobs = \App\Models\Job::all();
        return view('recruiter.discover', compact('jobs'));
    }
}
