<?php
include 'db.php';
session_start();

echo "hello";  // Test the script reaches this point

// Query to fetch all universities from the database
$query = "SELECT * FROM universities";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);  // Output query error if any
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Universities</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('background.jpg'); /* Set your background image path */
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);  /* Increased opacity */
            backdrop-filter: blur(10px);  /* Optional blur effect */
            overflow: hidden;
        }

        h2 {
            text-align: center;
            color: #007BFF;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 20px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f0f8ff;
        }

        tr:nth-child(odd) {
            background-color: #e6f7ff;
        }

        tr:hover {
            background-color: #00bfff;
            color: white;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Button Styles */
        .back-button-container {
            display: flex;
            justify-content: center; /* Horizontally center */
            align-items: center;     /* Vertically center */
            margin-top: 40px;
        }

        .back-button {
            padding: 14px 35px;
            background-color: #ff6f61; /* Vibrant Coral color */
            color: white;
            border: none;
            border-radius: 8px; /* Slightly rounder corners for a modern look */
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase; /* All caps for a professional look */
            letter-spacing: 1px; /* Slightly increased letter-spacing for better readability */
            transition: background-color 0.3s, transform 0.2s; /* Smooth background color change and scaling effect */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a soft shadow for a 3D effect */
        }

        /* Hover effect for button */
        .back-button:hover {
            background-color: #d85c4a; /* Darker coral on hover */
            transform: scale(1.05); /* Slight zoom effect when hovering */
        }

        /* Focus effect for accessibility */
        .back-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5); /* Focus ring for accessibility */
        }

        /* Active effect when button is clicked */
        .back-button:active {
            background-color: #c24f42; /* Even darker coral when button is pressed */
            transform: scale(0.98); /* Slight shrink effect on click */
        }

        /* Table Styling */
        .table-container {
            margin-top: 40px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Universities in Hyderabad</h2>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['type']; ?></td>
                            <td><a href="application.php?university_id=<?php echo $row['id']; ?>">Apply</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="back-button-container">
            <a href="student_dashboard.php">
                <button class="back-button">Back to Services</button>
            </a>
        </div>
    </div>
</body>
</html>

