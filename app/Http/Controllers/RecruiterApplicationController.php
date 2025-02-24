<?php

namespace App\Http\Controllers;

use App\Models\RecruiterApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\NewRecruiterRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecruiterApplicationController extends Controller
{
    public function create()
    {
        return view('recruiter-applications.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Allow if user hasn't requested, or was rejected before
        if ($user->recruiter_request_status != 'pending' && $user->recruiter_request_status != 'approved') {
            $user->update([
                'recruiter_request_status' => 'pending'
            ]);

            // Notify admins about the new request
            $admins = User::where('role', User::ROLE_ADMIN)->get();
            
            foreach ($admins as $admin) {
                // Create notification with explicit user_id
                DB::table('notifications')->insert([
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,  // The requesting user's ID
                    'type' => NewRecruiterRequest::class,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $admin->id,
                    'data' => json_encode([
                        'user_id' => $user->id,
                        'message' => "New recruiter application request from {$user->name}",
                        'type' => 'recruiter_request'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return redirect()->route('recruiter-applications.create')
                ->with('success', 'Your application request has been sent successfully!');
        }

        return redirect()->route('recruiter-applications.create')
            ->with('error', 'You already have a pending application or are already a recruiter.');
    }
}
