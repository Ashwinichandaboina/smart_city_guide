<?php
session_start();

// Mock function to check user credentials (replace with actual database check)
function check_credentials($email, $password) {
    // Replace with actual user validation logic
    return ($email === "user@example.com" && $password === "password123");
}

// Get user credentials from form
$email = $_POST['email'];
$password = $_POST['password'];

// Check if credentials are valid
if (check_credentials($email, $password)) {
    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Save OTP to session for verification
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300; // OTP valid for 5 minutes
    $_SESSION['email'] = $email;

    // Send OTP to the user's email (mocked here, replace with actual email sending logic)
    // mail($email, "Your OTP Code", "Your OTP code is: $otp");

    // Redirect to OTP verification page
    header('Location: otp_verification.php');
    exit();
} else {
    // If credentials are invalid, redirect back to login page with an error
    $_SESSION['error'] = "Invalid email or password.";
    header('Location: login.php');
    exit();
}

