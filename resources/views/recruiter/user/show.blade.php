@extends('layouts.recruiter')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>User Details</h2>

        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Name:</strong>
                </div>
                <div class="col-md-9">
                    {{ $user->name }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Email:</strong>
                </div>
                <div class="col-md-9">
                    {{ $user->email }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Phone Number:</strong>
                </div>
                <div class="col-md-9">
                    {{ $user->phone_number }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <strong>Role:</strong>
                </div>
                <div class="col-md-9">
                    @if ($user->role == 0)
                        User
                    @elseif ($user->role == 1)
                        Recruiter
                    @else
                        Admin
                    @endif
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back to Job Applications</a>
            </div>
        </div>
    </div>
</div>
@endsection
