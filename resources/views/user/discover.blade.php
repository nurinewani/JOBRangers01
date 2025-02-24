@extends('layouts.user')
@section('content')
    <!-- Debug information -->
    <div style="display: none;">
        <h3>Debug Info:</h3>
        <pre>
            Mapbox Token: {{ env('MAPBOX_ACCESS_TOKEN') }}
            @foreach($jobs as $job)
                Job {{ $job->id }}: 
                - Lat: {{ $job->latitude }}
                - Lng: {{ $job->longitude }}
            @endforeach
        </pre>
    </div>

    @if ($message = Session::get('success'))  
        <div class="alert alert-success">         
            <p><strong>{{ $message }}</strong></p>
        </div> 
    @endif 

    @if ($message = Session::get('error'))  
        <div class="alert alert-danger">         
            <p><strong>{{ $message }}</strong></p>    
        </div> 
    @endif

    <!-- Job List in Card Layout -->
    <div class="row">
        <h1 class="m-0">Discover New Jobs Here!</h1>
        <br>
        @forelse ($jobs as $job)
            @if($job->status === 'open')  {{-- Only display if job status is open --}}
            <br>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title" style="font-size: 1.3rem;"><strong>{{ $job->title }}</strong></h5>
                            @if($job->hasScheduleConflict)
                                <span class="badge bg-warning" style="margin-left: 10px; padding: 5px; font-size: 13px;">Schedule Conflict</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <p><strong>Location:</strong> {{ $job->location }}</p>
                            <p><strong>Salary:</strong> RM {{ $job->salary }}</p>
                            <p><strong>Job Period:</strong> {{ \Carbon\Carbon::parse($job->date_from)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($job->date_to)->format('d-m-Y')}}</p>
                            <p><strong>Working Hours:</strong> {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($job->end_time)->format('H:i') }}</p>
                            <p><strong>Application Deadline:</strong> {{ \Carbon\Carbon::parse($job->application_deadline)->format('d-m-Y') }}</p>
                            @if($job->hasScheduleConflict)
                                <div class="alert alert-warning">
                                    <small>This job conflicts with your existing schedule:</small>
                                    <ul class="mb-0">
                                        @foreach($job->conflictingSchedules as $schedule)
                                            <li>{{ $schedule->title }} ({{ $schedule->start }} - {{ $schedule->end }})</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#jobModal{{ $job->id }}">
                                View Details
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="showLocationMap({{ $job->latitude }}, {{ $job->longitude }}, '{{ $job->title }}', '{{ $job->location }}')">
                                <i class="fas fa-map-marker-alt"></i> View Map
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No open jobs available at the moment. Please check back later!
                </div>
            </div>
        @endforelse
    </div>

    <!-- Job Detail Modals -->
    @foreach ($jobs as $job)
        @if($job->status === 'open')  {{-- Only create modals for open jobs --}}
            <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $job->title }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Job Details</h6>
                                    <p><strong>Location:</strong> {{ $job->location }}</p>
                                    <p><strong>Description:</strong> {{ $job->description }}</p>
                                    <p><strong>Salary:</strong> RM {{ number_format($job->salary, 2) }}</p>
                                    <p><strong>Duration:</strong> {{ $job->duration }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Dates</h6>
                                    <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($job->date_from)->format('d M Y') }}</p>
                                    <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($job->date_to)->format('d M Y') }}</p>
                                    <p><strong>Application Deadline:</strong> 
                                        <span class="{{ $job->application_deadline < now() ? 'text-danger' : '' }}">
                                            {{ \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') }}
                                        </span>
                                    </p>
                                    <p><strong>Posted On:</strong> {{ $job->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            @if($job->status === 'closed')
                                <button type="button" class="btn btn-danger" disabled>Job Closed</button>
                            @elseif($job->hasScheduleConflict)
                                <button type="button" class="btn btn-warning" disabled>Schedule Conflict</button>
                            @else
                                <form action="{{ route('user.jobapplication.apply', ['jobId' => $job->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Apply</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Job Application Progress Bar -->
    <div class="progress" style="display:none;" id="application-progress">
        <div class="progress-bar" style="width: 0%;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Step 1/2</div>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="alert alert-success" style="display: none;">Your application was submitted successfully!</div>

    <!-- Dedicated Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Job Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="location-map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet">
<style>
    .card-header .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
        margin-left: 8px;
    }
    
    .card-title {
        margin-bottom: 0;
        margin-right: 8px;
        display: inline-block;
    }
    
    .archive-banner {
        position: absolute;
        top: 40px;
        right: -35px;
        transform: rotate(45deg);
        width: 150px;
        text-align: center;
        background-color: rgba(108, 117, 125, 0.9);
        color: white;
        padding: 5px;
    }
    
    .job-card {
        position: relative;
        overflow: hidden;
    }
    
    /* Ensure badges and text align properly */
    .d-flex.align-items-center.gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Make status badges consistent */
    .badge {
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }
    
    #job-map {
        margin: 15px 0;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .location-info {
        margin: 10px 0;
        padding: 8px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    .modal-dialog.modal-lg {
        max-width: 800px;
    }
</style>
@endpush

@push('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
<script>
let map = null;
let currentMarker = null;

function showLocationMap(lat, lng, title, address) {
    // Show the map modal
    $('#mapModal').modal('show');

    // Initialize map when modal is shown
    $('#mapModal').on('shown.bs.modal', function () {
        mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        
        // Remove existing map if any
        if (map) {
            map.remove();
            map = null;
        }

        // Create new map
        map = new mapboxgl.Map({
            container: 'location-map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [lng, lat],
            zoom: 15
        });

        // Wait for map to load before adding marker
        map.on('load', function() {
            // Remove existing marker if any
            if (currentMarker) {
                currentMarker.remove();
            }

            // Create new marker
            currentMarker = new mapboxgl.Marker({
                color: '#FF0000',
                draggable: false
            })
            .setLngLat([lng, lat])
            .setPopup(
                new mapboxgl.Popup({ offset: 25 })
                    .setHTML(`
                        <div style="padding: 10px;">
                            <h6 style="margin-bottom: 5px;">${title}</h6>
                            <p style="margin-bottom: 10px;">${address}</p>
                            <button onclick="getDirections(${lat}, ${lng})" 
                                    class="btn btn-sm btn-primary">
                                Get Directions
                            </button>
                        </div>
                    `)
            )
            .addTo(map);

            // Add navigation controls
            map.addControl(new mapboxgl.NavigationControl());
        });
    });
}

function getDirections(lat, lng) {
    const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(googleMapsUrl, '_blank');
}

// Clean up when modal is closed
$('#mapModal').on('hidden.bs.modal', function () {
    if (currentMarker) {
        currentMarker.remove();
        currentMarker = null;
    }
    if (map) {
        map.remove();
        map = null;
    }
});

// Debug logging
function logMapInfo(lat, lng) {
    console.log('Map coordinates:', { lat, lng });
    console.log('Mapbox token:', mapboxgl.accessToken);
}
</script>
@endpush
