<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
            $notification->update(['is_read' => true]);
            return redirect()->route('notifications.index')->with('success', 'Notification marked as read.');
        } catch (\Exception $e) {
            \Log::error("Error marking notification as read: " . $e->getMessage());
            return back()->with('error', 'Failed to mark notification as read.');
        }
    }
}

