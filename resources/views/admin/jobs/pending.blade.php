@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Pending Jobs</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendingJobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->location }}</td>
                    <td>{{ $job->salary }}</td>
                    <td>
                        <!-- Approve Button -->
                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button class="btn btn-success" type="submit">Approve</button>
                        </form>
                        
                        <!-- Reject Button -->
                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-danger" type="submit">Reject</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
