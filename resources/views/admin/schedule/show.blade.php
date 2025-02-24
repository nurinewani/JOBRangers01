@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Schedule Details
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <p><strong>Title:</strong> {{ $schedule->title }}</p>
                <p><strong>Description:</strong> {{ $schedule->description }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d') }}</p>
                <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-primary" href="{{ route('admin.schedule.edit', $schedule->id) }}">
                    Edit
                </a>
                <form action="{{ route('admin.schedule.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
