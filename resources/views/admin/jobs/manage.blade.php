@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Manage Jobs</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($jobs->isEmpty())
        <p>No jobs available for management.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->description }}</td>
                        <td>
                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

