<?php
// Include database connection
include 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables for errors and results
$no_result_message = "";
$vehicles = [];

// Fetch available sources and destinations for dropdowns
$source_query = "SELECT DISTINCT source FROM transportation";
$source_result = $conn->query($source_query);

$destination_query = "SELECT DISTINCT destination FROM transportation";
$destination_result = $conn->query($destination_query);

// Check if the form is submitted to filter vehicles
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form input
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    // Query to fetch available vehicles based on user input
    $query = "SELECT * FROM transportation WHERE source = ? AND destination = ? AND cost BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdd", $source, $destination, $min_price, $max_price);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any results were returned
    if ($result->num_rows == 0) {
        $no_result_message = "No vehicles found matching your criteria. Please adjust your search.";
    } else {
        // Fetch results into an array
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation Services</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ffe4e1, #e3f2fd, #f3e5f5); /* Vibrant light gradient background */
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        h2 {
            text-align: center;
            color: #0078d7; /* Vibrant blue */
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        select, input, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
        }

        select, input {
            background-color: #f4f7fb; /* Light background for inputs */
        }

        button {
            background-color: #4CAF50; /* Soft green for buttons */
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            font-weight: bold;
        }

        button:hover {
            background-color: #45a049; /* Slightly darker green on hover */
        }

        /* Error message style */
        .error {
            color: #d9534f;
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #f1f1f1; /* Light grey header */
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tr:hover {
            background-color: #e9f6f1; /* Light greenish hover */
        }

        a {
            text-decoration: none;
            color: #4CAF50; /* Green links */
            font-weight: bold;
        }

        a:hover {
            color: #388e3c; /* Darker green on hover */
        }

        .back-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 30px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #45a049;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Transportation Services</h2>

    <!-- Form to search for transportation -->
    <form method="POST">
        <label for="source">Source:</label>
        <select id="source" name="source" required>
            <option value="">Select Source</option>
            <?php while ($row = $source_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['source']; ?>"><?php echo $row['source']; ?></option>
            <?php } ?>
        </select>

        <label for="destination">Destination:</label>
        <select id="destination" name="destination" required>
            <option value="">Select Destination</option>
            <?php while ($row = $destination_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['destination']; ?>"><?php echo $row['destination']; ?></option>
            <?php } ?>
        </select>

        <label for="min_price">Min Price:</label>
        <input type="number" id="min_price" name="min_price" placeholder="Enter minimum price" required>

        <label for="max_price">Max Price:</label>
        <input type="number" id="max_price" name="max_price" placeholder="Enter maximum price" required>

        <button type="submit">Search</button>
    </form>

    <!-- Display message if no results are found -->
    <?php if (!empty($no_result_message)) { ?>
        <p class="error"><?php echo $no_result_message; ?></p>
    <?php } ?>

    <!-- Display transportation options only after form submission and when results are available -->
    <?php if (!empty($vehicles)) { ?>
        <h3>Available Vehicles</h3>
        <table>
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Vehicle</th>
                    <th>Cost</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle) { ?>
                    <tr>
                        <td><?php echo $vehicle['source']; ?></td>
                        <td><?php echo $vehicle['destination']; ?></td>
                        <td><?php echo $vehicle['vehicle']; ?></td>
                        <td><?php echo $vehicle['cost']; ?></td>
                        <td>
                            <a href="ride_success1.php?vehicle_id=<?php echo $vehicle['id']; ?>">Book Ride</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <a href="student_dashboard.php" class="back-btn">Back to Services</a>
</div>

</body>
</html>

