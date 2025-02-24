<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RecruiterRequestController extends Controller
{
    public function index()
    {
        $requests = User::whereNotNull('recruiter_request_status')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.recruiter-requests.index', compact('requests'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $user->update([
            'recruiter_request_status' => $validated['status'],
            'role' => $validated['status'] === 'approved' ? User::ROLE_RECRUITER : $user->role
        ]);

        return redirect()->back()->with('success', 
            'Recruiter application ' . $validated['status'] . ' successfully.');
    }
}
