<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Leaflet Map</title>
    <link rel="stylesheet" href="leaflet/leaflet.css" />
</head>
<body>

    <div id="map" style="width: 100%; height: 400px;"></div>

    <script src="leaflet/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([17.385044, 78.486671], 12); // Coordinates for Hyderabad

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    </script>

</body>
</html>

