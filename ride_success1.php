<?php
include 'db.php';  // Include config file to initialize database connection

// Check if the vehicle ID is passed via the URL
if (!isset($_GET['vehicle_id'])) {
    header("Location: transportation1.php");
    exit();
}

// Get the vehicle ID from the URL
$vehicle_id = $_GET['vehicle_id'];

// Query to get the vehicle details
$query = "SELECT * FROM transportation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Booked Successfully</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="/MyWebsite/smartcityguide/leaflet/leaflet.css" />

    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

    <!-- Inline CSS for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }

        /* Map container style */
        #map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }

        /* Button styles */
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Custom style for the label above markers */
        .label-text {
            background-color: white;  /* White box */
            padding: 5px 10px;         /* Padding inside the box */
            border-radius: 5px;        /* Rounded corners */
            border: 1px solid #ddd;    /* Light border */
            font-size: 14px;            /* Font size for text */
            font-weight: bold;         /* Bold font */
            color: #333;               /* Text color */
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1); /* Light shadow */
        }

        /* Style for the message when ride is cancelled */
        .cancel-message {
            background-color: #ffdddd;
            color: #d9534f;
            border: 1px solid #d9534f;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Ride has been Booked Successfully!</h2>
        <p>Thank you for booking your ride from <strong><?php echo $vehicle['source']; ?></strong> to <strong><?php echo $vehicle['destination']; ?></strong>.</p>
        <p>Vehicle: <strong><?php echo $vehicle['vehicle']; ?></strong></p>
        <p>Cost: <strong><?php echo $vehicle['cost']; ?></strong></p>

        <!-- Button to go back to services page -->
        <a href="student_dashboard.php"><button>Back to Home</button></a>

        <!-- Cancel Booking Button -->
        <button onclick="cancelBooking()">Cancel Booking</button>

        <!-- Cancel Message -->
        <div id="cancelMessage" class="cancel-message">The ride has been cancelled successfully!</div>

        <!-- Leaflet Map Container -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="/MyWebsite/smartcityguide/leaflet/leaflet.js"></script>

    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        // JavaScript function to show cancellation message
        function cancelBooking() {
            // Show cancellation message
            document.getElementById('cancelMessage').style.display = 'block';
        }

        window.onload = function() {
            if (typeof L !== 'undefined') {
                // Initialize Leaflet map with default view for the vehicle's source and destination
                var map = L.map('map').setView([17.385044, 78.486671], 12); // Default coordinates for Hyderabad

                // Add OpenStreetMap tile layer
                L.tileLayer('https://a.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap France | <a href="https://www.openstreetmap.org/copyright">OSM</a>'
                }).addTo(map);

                // Source and destination coordinates
                var sourceLat = 17.385044; // Source latitude (adjust based on your data)
                var sourceLng = 78.486671; // Source longitude (adjust based on your data)
                var destLat = 17.400000;   // Destination latitude (adjust based on your data)
                var destLng = 78.500000;   // Destination longitude (adjust based on your data)

                // Define custom icons for markers
                var iconOptions = {
                    iconUrl: '/MyWebsite/smartcityguide/leaflet/marker-icon.png', 
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                };

                var sourceIcon = L.icon(iconOptions);
                var destinationIcon = L.icon(iconOptions);

                // Create markers for source and destination
                var sourceMarker = L.marker([sourceLat, sourceLng], { icon: sourceIcon }).addTo(map);
                var destinationMarker = L.marker([destLat, destLng], { icon: destinationIcon }).addTo(map);

                // Create custom HTML for the label above the source marker
                var sourceLabel = L.divIcon({
                    className: 'custom-label',
                    html: "<div class='label-text'>" + "<?php echo $vehicle['source']; ?>" + " City</div>",
                    iconSize: [100, 30],
                    iconAnchor: [50, 0], // Position the label above the marker
                    popupAnchor: [0, -20] // Adjust the position above the marker
                });

                // Create custom HTML for the label above the destination marker
                var destLabel = L.divIcon({
                    className: 'custom-label',
                    html: "<div class='label-text'>" + "<?php echo $vehicle['destination']; ?>" + " City</div>",
                    iconSize: [100, 30],
                    iconAnchor: [50, 0], // Position the label above the marker
                    popupAnchor: [0, -20] // Adjust the position above the marker
                });

                // Add the labels above the markers
                L.marker([sourceLat, sourceLng], { icon: sourceLabel }).addTo(map);
                L.marker([destLat, destLng], { icon: destLabel }).addTo(map);

                // Add routing between source and destination using Leaflet Routing Machine
                L.Routing.control({
                    waypoints: [
                        L.latLng(sourceLat, sourceLng),  // Source point
                        L.latLng(destLat, destLng)       // Destination point
                    ],
                    routeWhileDragging: true  // Allow dragging the route to recalculate
                }).addTo(map);
            } else {
                console.error("Leaflet library not loaded.");
            }
        };
    </script>
</body>
</html>

