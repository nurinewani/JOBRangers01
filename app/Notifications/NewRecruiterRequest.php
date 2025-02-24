<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NewRecruiterRequest extends Notification
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,  // The user who requested to be a recruiter
            'message' => "New recruiter application request from {$this->user->name}",
            'type' => 'recruiter_request',
            'sender_id' => $this->user->id, // Adding this to match your table structure
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
} 