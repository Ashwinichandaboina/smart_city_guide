<?php
include 'db.php';
session_start();

// Check if the form is submitted
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['company_id'])) {
    $company_id = $_POST['company_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $resume = $_FILES['resume']['name'];

    $submitted = true;

    if ($_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $resume_tmp_name = $_FILES['resume']['tmp_name'];
        $resume_file_path = $upload_dir . basename($resume);
        move_uploaded_file($resume_tmp_name, $resume_file_path);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Company</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(120deg, #f0f4f8, #cfe1f2); /* Gradient background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        /* Main Container Styling */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff; /* White background for the container */
            border-radius: 15px; /* Smooth rounded corners */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* Subtle box shadow */
            border: 1px solid #e3e3e3; /* Light border for structure */
            transition: transform 0.3s ease-in-out;
        }

        /* Hover Effect for the Container */
        .container:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Page Title */
        h1 {
            text-align: center;
            color: #4caf50; /* Soft green title */
            margin-bottom: 20px;
        }

        /* Success Message Styling */
        .success-message {
            text-align: center;
            font-weight: bold;
            color: #43a047; /* Success green */
            padding: 10px 0;
        }

        /* Form Box Styling */
        form {
            background: #f9f9f9; /* Light form background */
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #ddd; /* Slight border */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        /* Label Styling */
        label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        /* Input Field Styling */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="file"]:focus {
            border-color: #4caf50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.4); /* Green glow effect */
        }

        /* Button Styling */
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #4caf50, #66bb6a); /* Gradient green button */
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(90deg, #43a047, #5eab53); /* Darker green gradient */
            transform: scale(1.05); /* Slight growth effect */
        }

        /* Notification Styling */
        p {
            text-align: center;
            color: #d32f2f; /* Error red */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                padding: 15px;
            }

            h1 {
                font-size: 1.8rem;
            }

            button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($submitted): ?>
            <p class="success-message">
                Dear <?php echo htmlspecialchars($name); ?>, your application has been submitted successfully. We will contact you soon!
            </p>
        <?php else: ?>
            <?php if (isset($_GET['id'])): ?>
                <?php
                $company_id = $_GET['id'];
                $query = "SELECT * FROM companies WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $company_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $company = $result->fetch_assoc();
                ?>

                <?php if ($company): ?>
                    <h1>Apply to <?php echo htmlspecialchars($company['name']); ?></h1>
                    <form action="apply_company.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">

                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" required>

                        <label for="resume">Resume (Upload):</label>
                        <input type="file" id="resume" name="resume" required>

                        <button type="submit">Submit Application</button>
                    </form>
                <?php else: ?>
                    <p>Company not found.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Invalid request.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>

