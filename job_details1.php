<?php
include 'db.php'; // Database connection settings

// Check if job_id is passed in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch the job details from the database
    $query = "SELECT * FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the job exists, display its details
    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
    } else {
        die("Job not found.");
    }
} else {
    die("Job ID not provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Details</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #eef2f3, #8e9eab); /* Professional gradient background */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            width: 90%;
            background-color: #f4f7f6; /* Soft gray background */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        h1 {
            font-size: 2.5rem;
            color: #2C3E50; /* Deep blue for the title */
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 1rem;
            margin: 10px 0;
            line-height: 1.6;
        }

        p strong {
            color: #34495E; /* Slightly darker blue for labels */
        }

        a {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(90deg, #0078d7, #0056a4); /* Button gradient */
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        a:hover {
            background: linear-gradient(90deg, #0056a4, #0078d7);
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            p {
                font-size: 0.9rem;
            }

            a {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($job['title']); ?></h1>
        
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
        <p><strong>Posted At:</strong> <?php echo date('F j, Y', strtotime($job['posted_at'])); ?></p>

        <a href="job_listing1.php">Back to Listings</a>
    </div>
</body>
</html>

