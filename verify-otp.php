<?php
session_start();

// Check if the OTP is submitted and verify
if (isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];
    
    // Check if OTP matches the one stored in the session
    if ($enteredOtp == $_SESSION['otp']) {
        // OTP verified successfully, redirect to the dashboard
        header("Location: dashboard.php?role=" . urlencode($_SESSION['role']));
        exit;
    } else {
        // Invalid OTP
        $message = "Incorrect OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
    <div>
        <h2>Verify OTP</h2>
        
        <!-- Display error message -->
        <?php if (isset($message)): ?>
            <p style="color: #f44336;"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- OTP Verification form -->
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>

