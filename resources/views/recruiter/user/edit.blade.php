@extends('layouts.recruiter')
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
    <h1>Edit User Details</h1>
    <form action="{{ route('admin.user.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ $user->address }}">
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $user->phone_number }}">
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>User</option>
                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Recruiter</option>
                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

</body>

@endsection
