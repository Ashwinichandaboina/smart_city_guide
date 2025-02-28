<?php
session_start();  // Start session to access session variables

// Check if user is logged in and OTP is verified
if (!isset($_SESSION['otp']) || !isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    // If OTP is not verified, redirect to the login page
    header('Location: login.php');
    exit;
}

// Get the user's email and role from the session
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'Unknown User';
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'student';  // Default to 'student'

// You can also retrieve user details from the database, like:
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
    <title>Dashboard</title>
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
            background-color: #dc143c; /* New crimson red */
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

        /* Custom styles for admin dashboard */
        .admin-dashboard {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
        }

        /* Custom styles for student dashboard */
        .student-dashboard {
            background-color: #008080;
            color: white;
            padding: 20px;
            border-radius: 5px;
        }

        /* Custom styles for tourist dashboard */
        .tourist-dashboard {
            background-color: #ff5722;
            color: white;
            padding: 20px;
            border-radius: 5px;
        }

        /* Custom styles for job seeker dashboard */
        .jobseeker-dashboard {
            background-color: #ffc107;
            color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="overlay"></div>  <!-- Overlay for darkening the background -->

    <div class="dashboard-container">
        <h2>Welcome to Your Dashboard</h2>

        <!-- Show different content based on role -->
        <?php if ($userRole === 'admin'): ?>
            <!-- Admin Dashboard -->
            <div class="admin-dashboard">
                <h3>Admin Dashboard</h3>
                <p>As an admin, you have full control over the system.</p>
                <a href="admin_dashboard.php" class="btn">Go to Admin Panel</a>
            </div>
        <?php elseif ($userRole === 'student'): ?>
            <!-- Student Dashboard -->
            <div class="student-dashboard">
                <h3>Student Dashboard</h3>
                <p>As a student, you can view your courses and progress.</p>
                <a href="student_dashboard.php" class="btn">Go to Student Panel</a>
            </div>
        <?php elseif ($userRole === 'tourist'): ?>
            <!-- Tourist Dashboard -->
            <div class="tourist-dashboard">
                <h3>Tourist Dashboard</h3>
                <p>As a tourist, you can explore our services and plan your trip.</p>
                <a href="tourist_dashboard.php" class="btn">Go to Tourist Panel</a>
            </div>
        <?php elseif ($userRole === 'jobseeker'): ?>
            <!-- Job Seeker Dashboard -->
            <div class="jobseeker-dashboard">
                <h3>Job Seeker Dashboard</h3>
                <p>As a job seeker, you can explore job listings and apply for positions.</p>
                <a href="jobseeker_dashboard.php" class="btn">Go to Job Seeker Panel</a>
            </div>
           
        <?php else: ?>
            <p>Invalid role. Please contact the system administrator.</p>
        <?php endif; ?>

        <!-- Logout button -->
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

</body>
</html>

