<?php
// Start session
session_start();
include 'db.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $role = $_SESSION['role']; // Use the role stored in the session

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into the database
    $insertQuery = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $insertQuery->bind_param("sssss", $name, $email, $phone, $password, $role);

    if ($insertQuery->execute()) {
        $_SESSION['success'] = "Successfully registered as $role.";
        header("Location: login.php?role=$role");
    } else {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: register.php");
    }

    $insertQuery->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: register.php");
}
?>

