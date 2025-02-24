@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Notifications</h3>
    
    @if($notifications->isEmpty())
        <p>No notifications available.</p>
    @else
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $notification->message }}</strong>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>

                    @if(!$notification->is_read)
                        <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-primary">
                            Mark as Read
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
