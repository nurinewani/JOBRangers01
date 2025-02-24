@extends('layouts.user')
@section('content')

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional: link to your CSS -->
    </head>

    <body>
    <div class="container">
        <h1>Edit Job Details</h1>
        <br>
        <form action="{{ route('jobs.update', $job) }}" method="POST">
        @csrf
        @method('PUT') <!-- CSRF protection -->
            <div class="form-group">
                <label for="title">Title :</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $job->title }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea name="description" id="description" class="form-control" required>{{ $job->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="location">Location :</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ $job->location }}" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary (RM) :</label>
                <input type="number" name="salary" id="salary" class="form-control" value="{{ $job->salary }}" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration :</label>
                <input type="text" name="duration" id="duration" class="form-control" value="{{ $job->duration }}" required>
            </div>
            <div class="form-group">
                <label for="application_deadline">Application Deadline :</label>
                <input type="date" name="application_deadline" id="application_deadline" class="form-control" value="{{ $job->application_deadline }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Job</button>
        </form>
    </div>
</body>
</html>
@endsection
