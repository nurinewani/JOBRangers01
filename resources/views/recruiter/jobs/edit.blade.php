@extends('layouts.recruiter')
@section('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet">
<link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css" rel="stylesheet">
@endsection
@section('content')
    <h1>Edit Job Listing</h1>
    <br>
        
    <form action="{{ route('recruiter.jobs.update', $job->id) }}" method="POST">
        @csrf <!-- CSRF protection -->
        @method('PUT')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $job->title) }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $job->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $job->location) }}" readonly required>
                <!-- Hidden inputs for coordinates -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $job->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $job->longitude) }}">
            </div>
                <!-- Map container -->
            <div id="map" style="height: 400px; margin-bottom: 20px; border-radius: 8px;"></div>
            <div class="form-group">
                <label for="date_from">Start Date:</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', $job->date_from) }}" required>
            </div>
            <div class="form-group">
                <label for="date_to">End Date:</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to', $job->date_to) }}" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary (RM):</label>
                <input type="number" name="salary" id="salary" class="form-control" value="{{ old('salary', $job->salary) }}" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration:</label>
                <input type="text" name="duration" id="duration" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" 
                       class="form-control @error('start_time') is-invalid @enderror" 
                       id="start_time" 
                       name="start_time" 
                       onchange="calculateDuration()"
                       value="{{ old('start_time', $job->start_time) }}">
                @error('start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" 
                       class="form-control @error('end_time') is-invalid @enderror" 
                       id="end_time" 
                       name="end_time" 
                       onchange="calculateDuration()"
                       value="{{ old('end_time', $job->end_time) }}">
                @error('end_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="application_deadline">Application Deadline:</label>
                <input type="date" name="application_deadline" id="application_deadline" class="form-control" value="{{ old('application_deadline', $job->application_deadline) }}" required>
            </div>
            <div class="form-group">
                <label for="status">Job Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="open" {{ $job->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="scheduled" {{ $job->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="active" {{ $job->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $job->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="alert alert-info mt-2">
                <strong>Status Meanings:</strong>
                <ul class="mb-0">
                    <li><strong>Open:</strong> Job is available for applications</li>
                    <li><strong>Scheduled:</strong> Applicant has been selected and job is scheduled</li>
                    <li><strong>Active:</strong> Job is currently ongoing</li>
                    <li><strong>Completed:</strong> Job has been finished</li>
                    <li><strong>Closed:</strong> Job is no longer available</li>
                </ul>
            </div>
            <button type="submit" class="btn btn-primary">Update Job</button>
    </form>
@endsection

@section('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
<script>
function calculateDuration() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (startTime && endTime) {
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        
        let diff = (end - start) / (1000 * 60 * 60); // Convert to hours
        
        if (diff < 0) {
            // If end time is on the next day
            diff = 24 + diff;
        }
        
        const hours = Math.floor(diff);
        const minutes = Math.round((diff - hours) * 60);
        
        const duration = `${hours} hour${hours !== 1 ? 's' : ''} ${minutes > 0 ? `${minutes} minute${minutes !== 1 ? 's' : ''}` : ''}`;
        document.getElementById('duration').value = duration;
    }
}

// Initialize Mapbox
mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';

// Initialize map with existing coordinates or default to a central location
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [{{ $job->longitude ?? 102.5571 }}, {{ $job->latitude ?? 3.0738 }}],
    zoom: 15
});

// Add navigation controls
map.addControl(new mapboxgl.NavigationControl());

// Add geocoder (search box)
const geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl,
    marker: false
});
map.addControl(geocoder);

// Initialize marker at current location
let marker = new mapboxgl.Marker({
    draggable: true
})
.setLngLat([{{ $job->longitude ?? 102.5571 }}, {{ $job->latitude ?? 3.0738 }}])
.addTo(map);

// Update coordinates when marker is dragged
marker.on('dragend', function() {
    const lngLat = marker.getLngLat();
    updateLocation([lngLat.lng, lngLat.lat]);
    
    // Get address from coordinates
    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                document.getElementById('location').value = data.features[0].place_name;
            }
        });
});

// Update location when searching
geocoder.on('result', function(e) {
    const coordinates = e.result.geometry.coordinates;
    marker.setLngLat(coordinates);
    updateLocation(coordinates, e.result.place_name);
});

// Function to update location fields
function updateLocation(coordinates, placeName = null) {
    document.getElementById('longitude').value = coordinates[0];
    document.getElementById('latitude').value = coordinates[1];
    if (placeName) {
        document.getElementById('location').value = placeName;
    }
}

// Click on map to set marker
map.on('click', function(e) {
    marker.setLngLat(e.lngLat);
    updateLocation([e.lngLat.lng, e.lngLat.lat]);
    
    // Get address from coordinates
    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${e.lngLat.lng},${e.lngLat.lat}.json?access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                updateLocation([e.lngLat.lng, e.lngLat.lat], data.features[0].place_name);
            }
        });
});

// Calculate duration on page load if times are pre-filled
document.addEventListener('DOMContentLoaded', calculateDuration);

//for debugging
document.querySelector('form').addEventListener('submit', function(e) {
    console.log('Status value being submitted:', document.getElementById('status').value);
});
</script>
@endsection
