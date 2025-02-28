<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if hotel_id is provided
if (!isset($_GET['hotel_id'])) {
    header("Location: hotel.php");
    exit();
}

$hotel_id = $_GET['hotel_id'];

// Fetch hotel details from the database
$query = "SELECT * FROM hotels WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();

if (!$hotel) {
    echo "Hotel not found.";
    exit();
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_booking'])) {
    // Collect user inputs
    $customer_name = $_POST['customer_name'];
    $customer_contact = $_POST['customer_contact'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $room_type = $_POST['room_type'];
    $total_price = $hotel['price']; // Use hotel's price for total

    // Insert booking into database
    $query = "INSERT INTO bookings (hotel_id, customer_name, customer_contact, check_in_date, check_out_date, room_type, total_price)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssi", $hotel_id, $customer_name, $customer_contact, $check_in_date, $check_out_date, $room_type, $total_price);

    if ($stmt->execute()) {
        $booking_success = true;
    } else {
        $booking_error = "Error: Could not process your booking. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Book Your Room at <?php echo htmlspecialchars($hotel['name']); ?></h2>

        <?php if (isset($booking_success) && $booking_success): ?>
            <h3>Booking Confirmed!</h3>
            <p><strong>Hotel Name:</strong> <?php echo htmlspecialchars($hotel['name']); ?></p>
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($_POST['customer_name']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($_POST['customer_contact']); ?></p>
            <p><strong>Room Type:</strong> <?php echo htmlspecialchars($_POST['room_type']); ?></p>
            <p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($_POST['check_in_date']); ?></p>
            <p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($_POST['check_out_date']); ?></p>
            <p><strong>Total Price:</strong> ₹<?php echo htmlspecialchars($hotel['price']); ?></p>
        <?php else: ?>
            <h3>Please Enter Your Details</h3>
            <?php if (isset($booking_error)): ?>
                <p class="error"><?php echo $booking_error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <label for="customer_name">Your Name:</label>
                <input type="text" name="customer_name" id="customer_name" required><br><br>

                <label for="customer_contact">Mobile Number:</label>
                <input type="text" name="customer_contact" id="customer_contact" required><br><br>

                <label for="check_in_date">Check-in Date:</label>
                <input type="date" name="check_in_date" id="check_in_date" required><br><br>

                <label for="check_out_date">Check-out Date:</label>
                <input type="date" name="check_out_date" id="check_out_date" required><br><br>

                <label for="room_type">Room Type:</label>
                <input type="text" name="room_type" id="room_type" required><br><br>

                <button type="submit" name="confirm_booking">Confirm Booking</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
