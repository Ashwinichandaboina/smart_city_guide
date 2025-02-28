<?php
include 'db.php'; // Database connection settings

// Fetch job listings from the database
$query = "SELECT * FROM jobs ORDER BY posted_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>

    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333333;
            background: linear-gradient(135deg, #eef2f3, #8e9eab); /* Professional gray-blue gradient */
            background-size: 400% 400%; /* Smooth gradient animation */
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white background */
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            font-size: 2.8rem;
            color: #333333;
            margin-bottom: 30px;
            font-weight: 700;
        }

        /* Job Listing Styles */
        .job-listing {
            background: linear-gradient(135deg, #ffffff, #dfe4ea); /* Subtle gray gradient */
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .job-listing:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .job-listing h2 {
            margin: 0;
            font-size: 1.8rem;
            color: #2C3E50; /* Deep blue for job titles */
            font-weight: 700;
        }

        .job-listing p {
            margin: 10px 0;
            font-size: 1rem;
            color: #555555;
        }

        .job-listing a {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(90deg, #0078d7, #0056a4); 
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .job-listing a:hover {
            background: linear-gradient(90deg, #0056a4, #0078d7);
            transform: scale(1.05);
        }

        /* Back Button Styles (Same as View Details) */
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(90deg, #0078d7, #0056a4); 
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 30px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 20%;
        }

        .back-btn:hover {
            background: linear-gradient(90deg, #0056a4, #0078d7);
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent footer */
            border-radius: 0 0 12px 12px;
            font-size: 0.9rem;
            color: #555555;
        }

        .footer a {
            color: #0078d7;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 15px;
            }

            h1 {
                font-size: 2rem;
            }

            .job-listing h2 {
                font-size: 1.5rem;
            }

            .job-listing p {
                font-size: 0.9rem;
            }

            .job-listing a {
                font-size: 0.9rem;
                padding: 8px 15px;
            }

            .back-btn {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Job Listings</h1>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-listing">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <p>Posted on: <?php echo date('F j, Y', strtotime($row['posted_at'])); ?></p>
                <p><a href="job_details1.php?job_id=<?php echo $row['id']; ?>" class="btn">View Details</a></p>
            </div>
        <?php endwhile; ?>

        <a href="jobseeker_dashboard.php" class="back-btn">Back to Dashboard</a>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Job Listings | Designed with care by <a href="#">YourCompany</a>
        </div>
    </div>

</body>
</html>

