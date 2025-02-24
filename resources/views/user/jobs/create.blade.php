@extends('layouts.user')
@section('content')
    <h1>Create Job Listing</h1>
    <br>
        
    <form action="{{ route('user.jobs.store') }}" method="POST">
        @csrf <!-- CSRF protection -->
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_from">Start Date:</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $job->date_from ?? '' }}" required>
            </div>
            <div class="form-group">
                <label for="date_to">End Date:</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $job->date_to ?? '' }}" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary (RM):</label>
                <input type="number" name="salary" id="salary" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration:</label>
                <input type="text" name="duration" id="duration" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="application_deadline">Application Deadline:</label>
                <input type="date" name="application_deadline" id="application_deadline" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Job</button>
    </form>
@endsection
