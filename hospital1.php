<?php
include 'db.php';
session_start();

// Fetch available hospitals
$query = "SELECT id, name, specialty FROM hospitals";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Hospital</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background: url('hos.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #ffd700; /* Golden color */
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
            color: #fff;
        }

        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            -webkit-appearance: none; /* For consistent dropdown in webkit browsers */
            -moz-appearance: none;    /* For consistent dropdown in Firefox */
            appearance: none;         /* Standard appearance for other browsers */
        }

        /* Add a custom dropdown arrow */
        select::-ms-expand {
            display: none;
        }

        button {
            width: 100%;
            background-color: #28a745; /* Green */
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        option {
            color: #333;
            background-color: #f4f4f4;
        }

        /* Styling for the select dropdown on mobile */
        select:focus {
            outline: none;
            border-color: #28a745; /* Green focus border */
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 30px;
                width: 90%;
            }

            h2 {
                font-size: 24px;
            }

            select, button {
                font-size: 16px;
            }
        }
         .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select a Hospital</h2>
        <form method="GET" action="appointment1.php">
            <label>Select Hospital:</label>
            <select name="hospital_id" required>
                <option value="">--Select a Hospital--</option>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?> - Specialty: <?= $row['specialty'] ?></option>
                <?php } ?>
            </select>
            <button type="submit">Select Hospital</button>
        </form>
        <a href="student_dashboard.php" class="back-btn">Go Back</a>
    </div>
</body>
</html>

