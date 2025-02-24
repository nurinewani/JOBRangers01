<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AdminJobController;

class AdminDiscoverController extends Controller
{
    public function index()
    {
        $jobs = \App\Models\Job::all();
        return view('admin.discover', compact('jobs'));
    }
}
