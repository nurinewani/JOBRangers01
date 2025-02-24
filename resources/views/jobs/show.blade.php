@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>{{ $job->title }}</h1>
    <p>{{ $job->description }}</p>
    <h3>Applicants</h3>

    @if($job->applications->isEmpty())
        <p>No applicants yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($job->applications as $application)
                <tr>
                    <td>{{ $application->user->name }}</td>
                    <td>{{ $application->user->email }}</td>
                    <td>{{ ucfirst($application->status) }}</td>
                    <td>
                        @if($application->status == 'pending')
                            <form method="POST" action="{{ route('applications.approve', $application->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('applications.reject', $application->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        @else
                            <span>{{ ucfirst($application->status) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
