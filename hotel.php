<?php
include 'db.php';  // Include config file to initialize database connection

// Handle search form submission
$hotels = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_hotels'])) {
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    // Fetch hotels within the price range for either single or double rooms
    $query = "SELECT * FROM hotels WHERE (single_room_price BETWEEN ? AND ?) OR (double_room_price BETWEEN ? AND ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $min_price, $max_price, $min_price, $max_price);
    $stmt->execute();
    $result = $stmt->get_result();
    $hotels = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Fetch all hotels if no search is done
    $query = "SELECT * FROM hotels";
    $result = $conn->query($query);
    $hotels = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Hotels</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="/MyWebsite/smartcityguide/leaflet/leaflet.css" />

    <!-- Inline CSS for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;  /* Light vibrant background color */
            margin: 0;
            padding: 0;
            color: #333;
        }
        

        .container {
            width: 100%;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .left-section {
            width: 45%;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-right: 20px;
        }

        h2 {
            color: #4CAF50;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            font-size: 14px;
        }

        button[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a.btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        a.btn:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }

        /* Map container style */
        #map {
            width: 50%;
            height: 800px;
            margin-top: 20px;
        }
         .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h2>Search for Hotels</h2>
            <form method="POST">
                <label for="min_price">Minimum Price (₹):</label>
                <input type="number" name="min_price" id="min_price" required>
                <label for="max_price">Maximum Price (₹):</label>
                <input type="number" name="max_price" id="max_price" required>
                <button type="submit" name="search_hotels">Search Hotels</button>
            </form>

            <?php if (!empty($hotels)): ?>
                <h3>Available Hotels</h3>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Single Room Price (₹)</th>
                        <th>Double Room Price (₹)</th>
                        <th>Single Room Availability</th>
                        <th>Double Room Availability</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($hotels as $hotel): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hotel['name']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['location']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['single_room_price']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['double_room_price']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['single_room_availability']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['double_room_availability']); ?></td>
                            <td>
                                <a href="book_hotel1.php?hotel_id=<?php echo $hotel['id']; ?>" class="btn">Book Now</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                 <a href="student_dashboard.php" class="back-btn">Go Back</a>

            <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <p>No hotels found in this price range.</p>
            <?php endif; ?>
        </div>

        <!-- Leaflet Map Container -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="/MyWebsite/smartcityguide/leaflet/leaflet.js"></script>

    <script>
        // Wait for the page to load and then initialize the map
        window.onload = function() {
            if (typeof L !== 'undefined') {
                // Initialize Leaflet map with default view for Hyderabad
                var map = L.map('map').setView([17.385044, 78.486671], 12); // Coordinates for Hyderabad

                // Add OpenStreetMap tile layer
                L.tileLayer('https://a.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap France | <a href="https://www.openstreetmap.org/copyright">OSM</a>'
                }).addTo(map);

                // Define the custom icon for the markers
                var locationIcon = L.icon({
                    iconUrl: '/MyWebsite/smartcityguide/leaflet/marker-icon.png', // Path to your custom icon
                    iconSize: [40, 40],  // Size of the icon
                    iconAnchor: [20, 40], // Anchor point (bottom-center of the icon)
                    popupAnchor: [0, -40] // Popup position above the icon
                });

                // Add markers for each hotel if latitude and longitude are available
                <?php foreach ($hotels as $hotel): ?>
                    <?php if (!empty($hotel['latitude']) && !empty($hotel['longitude'])): ?>
                        var lat = <?php echo $hotel['latitude']; ?>;
                        var lng = <?php echo $hotel['longitude']; ?>;
                        var name = "<?php echo htmlspecialchars($hotel['name']); ?>";
                        var location = "<?php echo htmlspecialchars($hotel['location']); ?>";

                        // Create a marker for this hotel
                        L.marker([lat, lng], { icon: locationIcon }).addTo(map)
                            .bindPopup("<strong>" + name + "</strong><br>" + location)
                            .openPopup();
                    <?php endif; ?>
                <?php endforeach; ?>

                // Get current user location and add a marker
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;

                        // Add a marker for user's current location
                        var userIcon = L.icon({
                            iconUrl: '/MyWebsite/smartcityguide/leaflet/marker-icon.png', // Path to a custom user icon
                            iconSize: [40, 40],
                            iconAnchor: [20, 40],
                            popupAnchor: [0, -40]
                        });

                        L.marker([userLat, userLng], { icon: userIcon }).addTo(map)
                            .bindPopup("<strong>Your Location</strong><br>Lat: " + userLat + "<br>Lng: " + userLng)
                            .openPopup();

                        // Center the map on the user's location
                        map.setView([userLat, userLng], 12);
                    }, function(error) {
                        console.error("Geolocation error: " + error.message);
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }

            } else {
                console.error("Leaflet library not loaded.");
            }
        };
    </script>

</body>
</html>

