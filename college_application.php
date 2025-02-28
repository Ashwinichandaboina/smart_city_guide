<?php
include 'db.php';
session_start();

$college_id = $_GET['college_id'];

// Fetch college details from the database
$query = "SELECT * FROM colleges WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();
$college = $result->fetch_assoc();

// Start output buffering
ob_start(); 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course_applied = $_POST['course_applied'];
    
    // Insert the application details into the database
   // $insert_query = "INSERT INTO college_applications (college_id, student_name, email, phone, course_applied) VALUES (?, ?, ?, ?, ?)";
    //$stmt = $conn->prepare($insert_query);
   // $stmt->bind_param("issss", $college_id, $student_name, $email, $phone, $course_applied);
   // $stmt->execute();
    
    // Output JavaScript to show the alert and redirect
    echo "<script>
            alert('Successfully Enrolled!');
            window.location.href = 'college1.php'; // Redirect to the college1.php page
          </script>";
    // End the script here, so no more output is sent
    ob_end_flush();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Application</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('university.webp'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 40px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7); /* Transparent background */
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
        h2 {
            text-align: center;
            color: #ffcc00;
            margin-bottom: 30px;
            font-size: 32px;
        }
        label {
            font-size: 16px;
            margin: 10px 0;
            display: block;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #ffcc00;
            border: none;
            color: #000;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #e6b800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Application Form for <?php echo $college['name']; ?></h2>

        <!-- Application Form -->
        <form method="POST">
            <label for="student_name">Name:</label>
            <input type="text" id="student_name" name="student_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="course_applied">Course Applied:</label>
            <input type="text" id="course_applied" name="course_applied" required>

            <button type="submit">Submit Application</button>
           
        </form>
        <a href="student_dashboard.php"><button>Back to Services</button></a>
    </div>
</body>
</html>

