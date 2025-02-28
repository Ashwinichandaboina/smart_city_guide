<?php
session_start();
include 'db.php';
if ($_SESSION['category'] != 'Student') {
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Services</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Student Services</h2>
        <ul>
            <li><a href="universities.php">View Universities</a></li>
            <li><a href="colleges.php">View Colleges</a></li>
            <li><a href="transportation.php">Transportation</a></li>
            <li><a href="hospital.php">Hospitals</a></li>
            <li><a href="hotel.php">Hotel Booking</a></li>
        </ul>
    </div>
</body>
</html>
