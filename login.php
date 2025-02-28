<?php
session_start();
include 'db.php';

$message = ''; // Initialize the message variable

// Retrieve the role dynamically from GET parameter or session
if (isset($_GET['role'])) {
    $role = ucfirst($_GET['role']);
    $_SESSION['role'] = strtolower($role); // Store the role in session for subsequent use
} elseif (isset($_SESSION['role'])) {
    $role = ucfirst($_SESSION['role']);
} else {
    $role = 'Student'; // Default role
}

// OTP generation logic (adds OTP functionality when form is submitted)
function generateOTP() {
    return rand(100000, 999999); // Generates a 6-digit OTP
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'student'; // Retrieve the role from session

    // Prepare SQL statement to retrieve user details based on email and role
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch the user data

        // Directly compare the entered password with the stored plain text password
        if ($password == $user['password']) {
            // Password matches, generate OTP
            $otp = generateOTP();
            $_SESSION['otp'] = $otp; // Store OTP in session

            // Display OTP in an alert message (for demonstration purposes)
            echo "<script>
                alert('Your OTP is: $otp');
                window.location.href = 'otp_verify.php'; // Redirect to OTP verification page after the alert
            </script>";
            exit; // Ensure script execution stops after redirect
        } else {
            // Incorrect password
            $message = "Incorrect password. Please try again.";
        }
    } else {
        // No user found
        $message = "No account found with this email and role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .role-text {
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #ffd700;
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
    </style>
</head>
<body>
    <div class="form-container">
        <div class="role-text">Login as <?php echo $role; ?></div>

        <!-- Display error or success messages -->
        <?php if ($message): ?>
            <p style="color: <?php echo strpos($message, 'Incorrect') !== false ? '#f44336' : '#4CAF50'; ?>;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit" class="btn">Login</button>
        </form>

        <!-- Back button -->
        <button onclick="window.location.href='index.php'" class="btn-back">Back</button>

        <!-- Register link (shown for non-admin roles) -->
        <?php if ($role !== 'Admin'): ?>
            <div class="register-text">
                No account? <a href="register.php?role=<?php echo strtolower($role); ?>">Register</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

