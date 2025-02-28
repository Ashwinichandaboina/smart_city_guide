<?php
// Start session
session_start();

// Get the role from URL or session
$role = isset($_GET['role']) ? $_GET['role'] : (isset($_SESSION['role']) ? $_SESSION['role'] : 'student');

// Store the role in the session
$_SESSION['role'] = $role;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* General styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg'); /* Replace with your image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        /* Overlay for better contrast */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.1); /* Slightly transparent background */
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            text-align: left;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f8f9fa;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #f8f9fa;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            color: #333;
        }

        input:focus {
            outline: none;
            border: 2px solid #007bff;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            color: white;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* Submit button */
        button[type="submit"] {
            background-color: #28a745; /* Green */
        }

        button[type="submit"]:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        /* Back button */
        .back-btn {
            background-color: #6c757d; /* Gray */
            width: 50%;
            margin: 0 auto;
        }

        .back-btn:hover {
            background-color: #5a6268;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        .error, .success {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background: rgba(220, 53, 69, 0.1); /* Light red background */
            color: #dc3545; /* Red text */
            border: 1px solid #dc3545;
        }

        .success {
            background: rgba(40, 167, 69, 0.1); /* Light green background */
            color: #28a745; /* Green text */
            border: 1px solid #28a745;
        }

    </style>
</head>
<body>
    <div class="overlay"></div> <!-- Overlay for background dimming -->
    <div class="container">
        <h2>Register as <?php echo ucfirst($role); ?></h2>

        <!-- Display error or success messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="register_process.php" method="post">
            <!-- Name Field -->
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>

            <!-- Email Field -->
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <!-- Phone Field -->
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" placeholder="Enter your phone number" required>

            <!-- Password Field -->
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>

            <!-- Role Field (read-only) -->
            <label for="role">Role:</label>
            <input type="text" value="<?php echo htmlspecialchars($role); ?>" readonly>

            <!-- Submit button -->
            <button type="submit">Register</button>
        </form>

        <!-- Back button -->
        <button class="back-btn" onclick="window.location.href='index.php'">Back</button>
    </div>
</body>
</html>

