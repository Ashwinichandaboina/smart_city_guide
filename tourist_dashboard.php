<?php
session_start();  // Start session to access session variables

// Check if the user is logged in and is a tourist
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tourist') {
    header('Location: login.php');  // Redirect to login if not a tourist
    exit;
}

// Get the user's email and role from the session
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'Unknown User';
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'tourist';  // Default to 'tourist'

// You can also retrieve user details from the database if needed
include 'db.php';  // Make sure to include the database connection file

// Example query to get user details (you can adjust as per your needs)
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Dashboard</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Ensure this path is correct -->
    <style>
        /* Set the background image for the full page */
        body {
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        /* Add an overlay to darken the background */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay */
        }

        /* Dashboard content container */
        .dashboard-container {
            z-index: 1;
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #fff;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
            color: #ddd;
        }

        /* Styling for the service list */
        .tourist-dashboard {
            padding: 20px;
            background-color: rgba(0, 128, 128, 0.9); /* New teal color */
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .tourist-dashboard ul {
            list-style-type: none;
            padding: 0;
        }

        .tourist-dashboard ul li {
            margin: 15px 0;
        }

        .tourist-dashboard ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        .tourist-dashboard ul li a:hover {
            text-decoration: underline;
            color: #ffe600;
        }

        /* Button Styles */
        .btn {
            background-color: #ff5722; /* A vibrant orange color */
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #e64a19;
            transform: scale(1.05);
        }

        .btn-logout {
            background-color: #7cafc4; /* New crimson red */
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #c2185b;
            transform: scale(1.05);
        }

    </style>
</head>
<body>

    <div class="overlay"></div>  <!-- Overlay for darkening the background -->

    <div class="dashboard-container">
        <h2>Welcome to Your Tourist Dashboard</h2>

        <div class="tourist-dashboard">
            <h3>Tourist Services</h3>
            <ul>
                <li><a href="view_place1.php">Explore Tourist Places</a></li>
                <li><a href="hotel.php">Hotel Booking</a></li>
                <li><a href="transportation1.php">Transportation</a></li>
                <li><a href="hospital.php">Hospital</a></li>
                <li><a href="restaurant1.php">Restaurant</a></li>
            </ul>
        </div>

        <!-- Logout button -->
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

</body>
</html>

