<?php
session_start(); // Start the session

// Check if the OTP session is set, if not, redirect to login page
if (!isset($_SESSION['otp'])) {
    header('Location: login.php'); // If OTP session is not set, redirect to login
    exit;
}

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the OTP entered by the user
    $enteredOtp = trim($_POST['otp']);

    // Compare entered OTP with the OTP stored in session
    if ($enteredOtp == $_SESSION['otp']) {
        // OTP matches, mark as verified
        $_SESSION['verified'] = true;
        
        // Optionally, you can store user email and other details in session for dashboard
        $_SESSION['email'] = $_POST['email']; // Assuming you have email from login page

        // Redirect to the dashboard page
        header('Location: dashboard.php');
        exit;
    } else {
        // OTP does not match
        $message = "Invalid OTP. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        /* General body styles */
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
            color: white;
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        input {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-back {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .register-text {
            font-size: 16px;
            margin-top: 15px;
        }

        a {
            text-decoration: none;
            color: #28a745;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            font-size: 16px;
        }

        /* Style for error message */
        .error-message {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Enter OTP to Verify</h2>

        <!-- Display message if OTP is invalid -->
        <?php if (isset($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- OTP verification form -->
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" class="btn">Verify OTP</button>
        </form>

        <!-- Back button to return to the login page -->
        <button onclick="window.location.href='index.php'" class="btn-back">Back to Home</button>
    </div>
</body>
</html>

