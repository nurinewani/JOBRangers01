@extends('layouts.user')
@section('content')
<div class="container">
    <!-- Job Search and Filter 
    <div class="mb-3">
        <input type="text" id="search-job" class="form-control" placeholder="Search for a job...">
        <select id="filter-location" class="form-control mt-2">
            <option value="">Select Location</option>
            <option value="Shah Alam">Shah Alam</option>
            <option value="Petaling Jaya">Petaling Jaya</option>
             //Add other locations dynamically
        </select>
    </div>-->

    @if ($message = Session::get('success')) 
        <div class="alert alert-success"> 
            <p>{{ $message }}</p> 
        </div> 
    @endif 

    <!-- Job List in Card Layout -->
    <div class="row">
        @foreach ($jobs as $job)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ $job->title }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Location:</strong> {{ $job->location }}</p>
                        <p><strong>Salary:</strong> RM {{ $job->salary }}</p>
                        <p><strong>Duration:</strong> {{ $job->duration }}</p>
                        <p><strong>Application Deadline:</strong> {{ $job->application_deadline }}</p>
                        <!-- Apply Button  //hanya utk user
                        <form action="{{ route('admin.jobapplication.apply', ['jobId' => $job->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Apply</button>
                        </form>-->
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#jobModal{{ $job->id }}">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Job Detail Modals -->
    @foreach ($jobs as $job)
        <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel{{ $job->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="jobModalLabel{{ $job->id }}">Job Details: {{ $job->title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Location:</strong> {{ $job->location }}</p>
                        <p><strong>Description:</strong> {{ $job->description }}</p>
                        <p><strong>Salary:</strong> RM {{ $job->salary }}</p>
                        <p><strong>Duration:</strong> {{ $job->duration }}</p>
                        <p><strong>Application Deadline:</strong> {{ $job->application_deadline }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('admin.jobapplication.apply', ['jobId' => $job->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Job Application Progress Bar -->
    <div class="progress" style="display:none;" id="application-progress">
        <div class="progress-bar" style="width: 0%;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Step 1/2</div>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="alert alert-success" style="display: none;">Your application was submitted successfully!</div>
</div>
@endsection

@section('scripts')
<script>
    // Real-time search and filter functionality
    document.getElementById('search-job').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.job-item').forEach(function(item) {
            let title = item.querySelector('h3').innerText.toLowerCase();
            if (title.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    document.getElementById('filter-location').addEventListener('change', function() {
        let selectedLocation = this.value.toLowerCase();
        document.querySelectorAll('.job-item').forEach(function(item) {
            let location = item.querySelector('p').innerText.toLowerCase();
            if (location.includes(selectedLocation) || !selectedLocation) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Job Application Progress Bar
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            let progressBar = document.getElementById('application-progress');
            let progress = progressBar.querySelector('.progress-bar');
            progressBar.style.display = 'block';
            progress.style.width = '50%';

            // Simulate submission delay
            setTimeout(function() {
                progress.style.width = '100%';
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'block';
                }, 500);
            }, 1000);
        });
    });
</script>
@endsection