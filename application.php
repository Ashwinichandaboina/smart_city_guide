<?php
session_start();  // Start the session

// Check if the university name is set in the session
$university_name = isset($_SESSION['university_name']) ? $_SESSION['university_name'] : '';

// Handle form submission (no database insertion)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $university_name = $_SESSION['university_name'];  // University name from the session
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $contact_number = $_POST['contact_number'];

    // Success message and redirect after successful form submission
    echo "<script>
            alert('Successfully Enrolled!');
            window.location.href = 'university.php'; // Redirect to the university page (adjust as needed)
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Application Form</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('university.webp'); /* Background image */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.6); /* Transparent background */
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #ffcc00;
            margin-bottom: 30px;
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
        input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
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
        a button {
            width: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>University Application Form</h2>

        <!-- Application form -->
        <form method="POST" action="">
            <!-- University name input - Readonly -->
            <!--<label for="university_name">University Name:</label>
            <input type="text" id="university_name" name="university_name" 
                   value="<?php echo htmlspecialchars($university_name); ?>"
                   readonly>-->

            <label for="student_name">Your Name:</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="course">Course Interested:</label>
            <input type="text" id="course" name="course" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>

            <button type="submit">Submit</button>
        </form>

        <!-- Back button -->
        <a href="student_dashboard.php"><button>Back to Services</button></a>
    </div>
</body>
</html>

