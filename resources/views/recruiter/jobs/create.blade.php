@extends('layouts.recruiter')
@section('content')
    <h1>Create Job Listing</h1>
    <br>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        
    <form action="{{ route('recruiter.jobs.store') }}" method="POST">
        @csrf <!-- CSRF protection -->
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <div id="geocoder" class="geocoder"></div>
                <input type="text" name="location" id="location" class="form-control" readonly required>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </div>
            <!-- Preview map -->
            <div id="preview-map" style="width: 100%; height: 300px; margin-bottom: 20px; border-radius: 8px;"></div>
            <div class="form-group">
                <label for="date_from">Start Date:</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from') }}" required>
            </div>
            <div class="form-group">
                <label for="date_to">End Date:</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ old('date_to') }}" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary (RM):</label>
                <input type="number" name="salary" id="salary" class="form-control" required>
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
                       value="{{ old('start_time', '09:00') }}">
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
                       value="{{ old('end_time', '17:00') }}">
                @error('end_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="application_deadline">Application Deadline:</label>
                <input type="date" name="application_deadline" id="application_deadline" class="form-control" required>
            </div>

            <!-- Preview map -->
            <div id="preview-map" style="width: 100%; height: 300px; margin-bottom: 20px; border-radius: 8px;"></div>

            <button type="submit" class="btn btn-primary">Create Job</button>
    </form>
@endsection

@section('styles')
    <!-- Add Mapbox CSS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css" rel="stylesheet">
    
    <style>
    .geocoder {
        margin-bottom: 10px;
    }
    .mapboxgl-ctrl-geocoder {
        width: 100%;
        max-width: 100%;
        font-family: inherit;
    }
    </style>
@endsection

@section('scripts')
    <!-- Add Mapbox JS -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>

    <!-- Your existing duration calculation script -->
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
        document.addEventListener('DOMContentLoaded', calculateDuration);
    </script>

    <!-- Mapbox initialization script -->
    <script>
        mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        
        // Initialize map
        const map = new mapboxgl.Map({
            container: 'preview-map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [102.5571, 3.0738], // Malaysia default
            zoom: 12
        });

        // Add navigation controls
        map.addControl(new mapboxgl.NavigationControl());

        // Initialize marker
        let marker = new mapboxgl.Marker({
            draggable: true
        });

        // Initialize geocoder
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            placeholder: 'Search for location',
            countries: 'my', // Limit to Malaysia
            marker: false
        });

        // Add geocoder to the custom container
        document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

        // When a location is selected via search
        geocoder.on('result', function(e) {
            updateLocation(e.result.geometry.coordinates, e.result.place_name);
        });

        // Handle marker drag
        function onDragEnd() {
            const lngLat = marker.getLngLat();
            
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        updateLocation([lngLat.lng, lngLat.lat], data.features[0].place_name);
                    }
                });
        }

        // Update location helper function
        function updateLocation(coordinates, placeName) {
            // Update marker
            marker.setLngLat(coordinates).addTo(map);
            
            // Update map
            map.flyTo({
                center: coordinates,
                zoom: 15
            });
            
            // Update form fields
            document.getElementById('location').value = placeName;
            document.getElementById('latitude').value = coordinates[1];
            document.getElementById('longitude').value = coordinates[0];
        }

        // Initialize marker drag functionality
        marker.on('dragend', onDragEnd);

        // Allow clicking on map to set location
        map.on('click', function(e) {
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${e.lngLat.lng},${e.lngLat.lat}.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        updateLocation([e.lngLat.lng, e.lngLat.lat], data.features[0].place_name);
                    }
                });
        });
    </script>
@endsection
