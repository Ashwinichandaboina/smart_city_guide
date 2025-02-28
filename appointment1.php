<?php
include 'db.php'; // Include database connection
session_start();

if (!isset($_GET['hospital_id']) || empty($_GET['hospital_id'])) {
    echo "<script>alert('Invalid access. Please select a hospital first.'); window.location.href = 'hospital1.php';</script>";
    exit;
}

$hospital_id = $_GET['hospital_id'];

// Fetch hospital details
$query = "SELECT name FROM hospitals WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$hospital_result = $stmt->get_result();
$hospital = $hospital_result->fetch_assoc();

if (!$hospital) {
    echo "<script>alert('Hospital not found.'); window.location.href = 'hospital1.php';</script>";
    exit;
}

$hospital_name = $hospital['name'];

// Fetch doctors for the selected hospital
$query = "SELECT id, name, specialty, availability FROM doctors WHERE specialty = (SELECT specialty FROM hospitals WHERE id = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$doctors = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = $_POST['patient_name'];
    $patient_contact = $_POST['patient_contact'];
    $doctor_id = $_POST['doctor_id'];
    $disease = $_POST['disease'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_day = $_POST['appointment_day'];

    // Fetch doctor availability
    $query = "SELECT availability FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();

    if ($doctor['availability'] == 'Available') {
        echo "<script>alert('Your appointment has been confirmed at $hospital_name.'); window.location.href = 'student_dashboard.php';</script>";
    } else {
        echo "<script>alert('Doctor is unavailable. Please select another doctor or try again later.'); window.location.href = 'appointment1.php?hospital_id=$hospital_id';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background: url('hos.jpg') no-repeat center center fixed; /* Background image */
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        /* Container styling for the form */
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #ffd700; /* Golden color */
        }

        /* Label styling */
        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
            color: #fff;
        }

        /* Input and select styling */
        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: #333;
            color: #fff;
        }

        /* Button styling */
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

        /* Option styling */
        option {
            color: #333;
            background-color: #f4f4f4;
        }

        /* Focus style for input/select fields */
        input:focus, select:focus {
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

            input, select, button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to <?= htmlspecialchars($hospital_name) ?></h2>
        <form method="POST">
            <label>Patient Name:</label>
            <input type="text" name="patient_name" required>

            <label>Contact Number:</label>
            <input type="text" name="patient_contact" required>

            <label>Select Disease:</label>
            <select name="disease" required>
                <option value="">--Select Disease--</option>
                <option value="Cardiology">Cardiology</option>
                <option value="Orthopedics">Orthopedics</option>
                <option value="Neurology">Neurology</option>
                <option value="Dermatology">Dermatology</option>
                <option value="Pediatrics">Pediatrics</option>
                <option value="Oncology">Oncology</option>
                <option value="Gynecology">Gynecology</option>
            </select>

            <label>Select Doctor:</label>
            <select name="doctor_id" required>
                <option value="">--Select Doctor--</option>
                <?php while ($row = $doctors->fetch_assoc()) { ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?> - <?= $row['specialty'] ?> (<?= $row['availability'] ?>)</option>
                <?php } ?>
            </select>

            <label>Appointment Day:</label>
            <input type="text" name="appointment_day" placeholder="e.g., Monday" required>

            <label>Appointment Date:</label>
            <input type="date" name="appointment_date" required>

            <button type="submit">Book Appointment</button>
        </form>
        <a href="hospital1.php">
            <button>Back to Services</button> 
    </div>
</body>
</html>

