<?php
// Database connection code
$servername = "localhost";
$username = "root"; // Update this based on your MySQL username
$password = ""; // Update this based on your MySQL password
$dbname = "smartcity";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You can use this $conn variable to interact with the database
?>

