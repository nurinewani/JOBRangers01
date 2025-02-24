<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruiterUserController extends Controller
{
    public function index()
    {
        $recruiterId = Auth::id();
        
        // Get users who have applied to this recruiter's jobs
        $users = User::whereHas('jobApplications', function($query) use ($recruiterId) {
            $query->whereHas('job', function($q) use ($recruiterId) {
                $q->where('recruiter_id', $recruiterId);
            });
        })->paginate(10);

        return view('recruiter.user.index', compact('users'));
    }

    public function show($id)
    {
        $recruiterId = Auth::id();
        
        // Find the user
        $user = User::findOrFail($id);
        
        // Get the user's job applications for this recruiter's jobs
        $applications = JobApplication::where('user_id', $user->id)
            ->whereHas('job', function($query) use ($recruiterId) {
                $query->where('recruiter_id', $recruiterId);
            })
            ->get();

        return view('recruiter.user.show', compact('user', 'applications'));
    }
}
