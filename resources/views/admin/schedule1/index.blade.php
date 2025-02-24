<!-- resources/views/admin/schedules/index.blade.php -->

@extends('layouts.admin')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>

<div id="calendar"></div>

<script>
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridWeek',
        events: @json($schedules),
    });
    calendar.render();
</script>

<div class="container">
    <h1>User Schedule</h1>

    <!-- Add Schedule Button -->
    <a href="{{ route('schedules.create') }}" class="btn btn-primary mb-3">Add Schedule</a>

    <!-- Schedule Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->title }}</td>
                    <td>{{ $schedule->start }}</td>
                    <td>{{ $schedule->end }}</td>
                    <td>
                        <!-- View Button -->
                        <a href="{{ route('schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">View</a>

                        <!-- Edit Button -->
                        <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
