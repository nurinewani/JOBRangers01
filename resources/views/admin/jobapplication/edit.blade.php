@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Job Application</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.jobapplication.update', $application->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" class="form-control" value="{{ $application->job->title }}" disabled>
                </div>

                <div class="form-group">
                    <label>Applicant Name</label>
                    <input type="text" class="form-control" value="{{ $application->user->name }}" disabled>
                </div>

                <div class="form-group">
                    <label for="status">Application Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                        <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $application->notes) }}</textarea>
                    @error('notes')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Application</button>
                    <a href="{{ route('admin.jobapplication.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
