<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart City</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            text-align: center;
            padding: 20px;
            color: white;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            font-size: 3em;
            margin-top: 100px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        /* Container to align buttons vertically */
        .button-container {
            display: flex;
            flex-direction: column; /* Align buttons vertically */
            justify-content: center;
            align-items: center;
            gap: 20px; /* Space between buttons */
        }

        /* Styling for the buttons */
        .role-button {
            padding: 15px 30px;
            color: white;
            border: none;
            border-radius: 50px; /* Rounded edges for a modern look */
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 200px;
            text-align: center;
            text-transform: uppercase; /* Add uppercase text */
        }

        /* Custom colors for each button */
        .role-button.admin {
            background-color: #28a745; /* Green for Admin */
        }
        
        .role-button.student {
            background-color: #007BFF; /* Blue for Student */
        }
        
        .role-button.jobseeker {
            background-color: #ffc107; /* Yellow for Job Seeker */
        }

        .role-button.tourist {
            background-color: #dc3545; /* Red for Tourist */
        }

        /* Hover effect: lighter color when mouse hovers over the button */
        .role-button:hover {
            transform: translateY(-5px); /* Slight lift effect */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Stronger shadow */
        }

        /* Active effect: button appears slightly pressed */
        .role-button:active {
            transform: translateY(2px); /* Slight down effect when clicked */
            opacity: 0.8; /* Slightly transparent when clicked */
        }

        /* Focus effect: outline when focused */
        .role-button:focus {
            outline: none;
            border: 2px solid #0056b3;
        }

        /* Custom colors when hovered (interactive color change) */
        .role-button.admin:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .role-button.student:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .role-button.jobseeker:hover {
            background-color: #e0a800; /* Darker yellow on hover */
        }

        .role-button.tourist:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        /* Optional: Adding a dark overlay to improve button visibility */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        /* Center content in the middle of the page */
        .content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        /* Additional text-shadow for the header to improve readability */
        h1 {
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.6);
        }

    </style>
</head>
<body>
    <div class="overlay"></div> <!-- Overlay for background image darkening -->
    <div class="content">
        <h1>Welcome to Smart City</h1>
        <!-- Container for vertically aligned buttons -->
        <!-- Category buttons with role parameter passed -->
<div class="button-container">
    <a href="login.php?role=admin" class="role-button admin"> Admin</a>
    <a href="login.php?role=student" class="role-button student"> Student</a>
    <a href="login.php?role=jobseeker" class="role-button jobseeker"> Job Seeker</a>
    <a href="login.php?role=tourist" class="role-button tourist"> Tourist</a>
</div>

    </div>
</body>
</html>

