@extends('layouts.user')

@section('content')
    <div id="map" 
         style="width: 100%; height: 500px; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);">
    </div>

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet" />

    <script>
        mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}'; // Replace with your Mapbox access token

        // Default center if geolocation is not available
        var defaultCenter = [102.5571, 3.0738];  // Example: Shah Alam coordinates

        // Create the map
        var map = new mapboxgl.Map({
            container: 'map', // The ID of the container element
            style: 'mapbox://styles/mapbox/streets-v11', // The style of the map
            center: defaultCenter, // Default center
            zoom: 12 // Set the initial zoom level
        });

        // Add navigation controls to the map
        map.addControl(new mapboxgl.NavigationControl());

        // Check if Geolocation is available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLatitude = position.coords.latitude;
                var userLongitude = position.coords.longitude;

                // Set the map center to the user's current location
                map.setCenter([userLongitude, userLatitude]);

                // Add a marker for the user's current location
                new mapboxgl.Marker()
                    .setLngLat([userLongitude, userLatitude])
                    .setPopup(new mapboxgl.Popup().setHTML('<h3>Your Current Location</h3>'))
                    .addTo(map);
            }, function(error) {
                // Handle error if geolocation fails
                console.error("Error getting geolocation: " + error.message);
                alert("Could not retrieve your location. Displaying default map center.");
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    </script>
@endsection
