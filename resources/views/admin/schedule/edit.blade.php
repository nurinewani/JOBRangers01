<!-- resources/views/schedules/edit.blade.php -->

@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Edit Schedule</h1>

    <form action="{{ route('admin.schedule.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $schedule->title }}" required>
        </div>

        <div class="mb-3">
            <label for="start" class="form-label">Start Time</label>
            <input type="datetime-local" name="start" id="start" class="form-control" value="{{ $schedule->start }}" required>
        </div>

        <div class="mb-3">
            <label for="end" class="form-label">End Time</label>
            <input type="datetime-local" name="end" id="end" class="form-control" value="{{ $schedule->end }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $schedule->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Schedule</button>
        <a href="{{ route('admin.schedule.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
