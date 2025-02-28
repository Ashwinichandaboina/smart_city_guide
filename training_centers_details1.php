<?php
include 'db.php'; // Database connection settings

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $center_id = $_GET['id'];

    // Fetch the training center details from the database
    $query = "SELECT * FROM training_centers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $center_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $center = $result->fetch_assoc();

    if (!$center) {
        echo "<p>Training center not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid request.</p>";
    exit;
}

// Check if the form is submitted
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Set the success flag to true
    $submitted = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($center['name']); ?> - Training Center Details</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f8fb; /* Soft pastel background */
            color: #4a4a4a; /* Dark gray text for readability */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* Container for content */
        .container {
            background-color: #ffffff; /* Clean white container */
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            margin-top: 50px;
            transition: all 0.3s ease-in-out;
        }

        .container:hover {
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.15);
        }

        h1 {
            text-align: center;
            color: #2d3e50; /* Deep blue color for headings */
            font-size: 3.2rem;
            margin-bottom: 25px;
            font-weight: 600;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            text-align: center;
            color: #5f6a78; /* Soft gray text */
        }

        .success-message {
            text-align: center;
            font-weight: bold;
            font-style: italic;
            margin-top: 20px;
            color: #1abc9c; /* Success green */
            font-size: 1.4em;
        }

        h2 {
            text-align: center;
            color: #34495e;
            font-size: 2.4rem;
            margin-top: 40px;
        }

        /* Basic styling for the form */
        form {
            margin-top: 30px;
            max-width: 550px;
            margin-left: auto;
            margin-right: auto;
            padding: 30px;
            border-radius: 12px;
            background-color: #fafafa;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        label {
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: block;
            color: #34495e;
        }

        input[type="text"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            font-size: 1.1rem;
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            background-color: #f7f9fc;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus {
            border-color: #3498db;
            background-color: #ffffff;
            outline: none;
        }

        button {
            padding: 14px 20px;
            background-color: #3498db; /* Fresh blue color */
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #2980b9;
            transform: scale(1.05); /* Slight hover zoom effect */
        }

        /* Back Button Styles (Same as View Details) */
        .back-btn {
            display: block; /* Make it a block element to control width */
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
            width: 100%; /* Full width for mobile */
            max-width: 200px; /* Optional: limit max width for desktop view */
            margin-left: auto;
            margin-right: auto; /* Center horizontally */
        }

        .back-btn:hover {
            background: linear-gradient(90deg, #0056a4, #0078d7);
            transform: scale(1.05);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.4rem;
            }

            .container {
                padding: 30px;
            }

            form {
                padding: 25px;
            }

            .back-btn {
                max-width: 100%; /* Ensure full width on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($center['name']); ?></h1>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($center['location']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($center['description'])); ?></p>
        
        <!-- Application Form -->
        <?php if ($submitted): ?>
            <p class="success-message">You have successfully applied for this training center. Thank you!</p>
        <?php else: ?>
            <h2>Apply for this Training Center</h2>
            <form action="training_center_details1.php?id=<?php echo $center['id']; ?>" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>

                <button type="submit">Submit Application</button>
            </form>
        <?php endif; ?>

        <!-- Back Button -->
        <a href="jobseeker_dashboard.php" class="back-btn">Back to Dashboard</a>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Training Center Details | Designed with care by <a href="#">YourCompany</a>
        </div>
    </div>

</body>
</html>

