<?php
include 'db.php';
session_start();

// Check if booking ID is set for cancellation
if (!isset($_POST['booking_id'])) {
    echo "Error: Booking ID is missing.";
    exit();
}

$booking_id = $_POST['booking_id'];

// Fetch the hotel ID associated with the booking
$query = "SELECT hotel_id FROM bookings WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Error: Booking not found.";
    exit();
}

$hotel_id = $booking['hotel_id'];

// Begin a transaction to ensure that both the cancellation and availability update happen atomically
$conn->begin_transaction();

try {
    // Cancel the booking by deleting from the bookings table
    $cancel_query = "DELETE FROM bookings WHERE id = ?";
    $cancel_stmt = $conn->prepare($cancel_query);
    $cancel_stmt->bind_param("i", $booking_id);
    $cancel_stmt->execute();

    // Increase the hotel's availability by 1
    $update_query = "UPDATE hotels SET availability = availability + 1 WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $hotel_id);
    $update_stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Set the cancellation message
    $cancel_message = "Your booking has been successfully canceled and availability has been updated.";
} catch (Exception $e) {
    // If something goes wrong, rollback the transaction
    $conn->rollback();
    $cancel_message = "Error: Unable to cancel booking. Please try again later.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Cancellation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            color: #333;
        }

        h2 {
            color: #4CAF50;
            font-size: 32px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
        }

        .cancel-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($cancel_message)): ?>
            <div class="cancel-message">
                <p><?php echo $cancel_message; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

