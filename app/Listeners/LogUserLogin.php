<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

class LogUserLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        // Store login log for the user
        UserLog::create([
            'user_id' => $event->user->id,  // Get the logged-in user's ID
            'name' => $event->user->name,    // Get the logged-in user's name
            'role' => $event->user->role,    // Get the logged-in user's role (ensure role is available)
            'action' => 'Logged In',         // Action description
        ]);
    }
}
