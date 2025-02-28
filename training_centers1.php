<?php
include 'db.php'; // Database connection settings

// Fetch all training centers from the database
$query = "SELECT * FROM training_centers";
$result = $conn->query($query);

// Error handling for query
if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Centers</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            align-items: center;
            background-color: #F5F7FA; /* Very light ash gray background */
            color: #34495E; /* Dark gray text */
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 30px;
            background-color: #FFFFFF; /* White background for container */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #2C3E50; /* Navy blue border on top */
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #2C3E50; /* Navy blue header color */
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Light border for rows */
        }

        table th {
            background-color: #2C3E50; /* Navy blue background for table header */
            color: #ffffff; /* White text color */
            font-weight: bold;
        }

        table tr:hover {
            background-color: #D5DBDB; /* Light gray hover effect */
            cursor: pointer;
        }

        a {
            color: #2C3E50; /* Navy blue link color */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #1A242F; /* Darker navy blue on hover */
        }

        /* Button Style for "View Details" */
        a.view-details {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2C3E50; /* Navy blue button */
            color: #fff;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        a.view-details:hover {
            background-color: #1A242F; /* Darker navy blue on hover */
        }

        /* Button Style for "Back to Dashboard" (Same as View Details) */
        a.back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2C3E50; /* Navy blue button */
            color: #fff;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            width: 20%; /* Full width for center alignment */
        }

        a.back-btn:hover {
            background-color: #1A242F; /* Darker navy blue on hover */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            table th, table td {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Training Centers</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Center Name</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($center = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($center['name']); ?></td>
                            <td><?php echo htmlspecialchars($center['location']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($center['description'])); ?></td>
                            <td><a href="training_center_details1.php?id=<?php echo $center['id']; ?>" class="view-details">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No training centers found.</p>
        <?php endif; ?>
        
        <!-- Back Button -->
        <a href="jobseeker_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>

