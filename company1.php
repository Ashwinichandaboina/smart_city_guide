<?php
include 'db.php';
session_start();

// Fetch companies from the database
$query = "SELECT * FROM companies";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(135deg, #ffe4e1, #e3f2fd, #f3e5f5); /* Vibrant light gradient background */
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items:center;
            min-height: 100vh;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff; /* Neutral white background */
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.8s ease-in-out;
        }

        h1 {
            text-align: center;
            color: #0078d7; /* Vibrant blue */
            font-size: 3rem;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .company-item {
            background: linear-gradient(90deg, #f1f8e9, #e3f2fd); /* Light pastel gradient */
            color: #333;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .company-item:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .company-item h2 {
            font-size: 2rem;
            color: #ff7043; /* Vibrant orange for company name */
            margin-bottom: 10px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .company-item h2:hover {
            color: #d84315; /* Darker orange on hover */
        }

        .company-item p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 12px;
        }

        .company-item a {
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(90deg, #8e24aa, #d81b60); /* Vibrant purple-pink gradient */
            padding: 12px 20px;
            border-radius: 50px; /* Rounded button style */
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }

        .company-item a:hover {
            background: linear-gradient(90deg, #6a1b9a, #ad1457); /* Darker gradient on hover */
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        }

        /* Button Style for "Back" (Same as Apply Now) */
        .back-btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #8e24aa; /* Same gradient as Apply Now */
            color: #fff;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 20%; /* Width 30% */
            margin: 30px auto; /* Center horizontally */
        }

        .back-btn:hover {
            background: linear-gradient(90deg, #6a1b9a, #ad1457); /* Darker gradient on hover */
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.4rem;
            }

            .container {
                padding: 20px;
            }

            .company-item {
                padding: 18px;
            }
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
        <h1>Available Companies</h1>
        
        <?php
        if ($result->num_rows > 0) {
            while ($company = $result->fetch_assoc()) {
                echo "<div class='company-item'>
                        <h2>" . htmlspecialchars($company['name']) . "</h2>
                        <p><strong>Field:</strong> " . htmlspecialchars($company['field']) . "</p>
                        <p><strong>Location:</strong> " . htmlspecialchars($company['location']) . "</p>
                        <a href='apply_company1.php?id=" . $company['id'] . "' class='apply-button'>Apply Now</a>
                      </div>";
            }
        } else {
            echo "<p>No companies available.</p>";
        }
        ?>
        
        <!-- Back Button -->
        <a href="dashboard.php" class="back-btn">Back</a>
    </div>
</body>
</html>

