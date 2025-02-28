<?php
include 'db.php'; // Include the database connection

$message = "";
$message_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $hotel_id = intval($_POST['hotel_id']);
    $customer_name = htmlspecialchars($_POST['customer_name'], ENT_QUOTES, 'UTF-8');
    $customer_contact = htmlspecialchars($_POST['customer_contact'], ENT_QUOTES, 'UTF-8');
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $room_type = $_POST['room_type'];
    $num_rooms = intval($_POST['num_rooms']);

    // Validate input
    if (empty($customer_name) || empty($customer_contact) || $num_rooms < 1) {
        $message = "Invalid input. Please fill out all fields correctly.";
        $message_class = "error";
    } else {
        // Calculate total days
        $check_in = strtotime($check_in_date);
        $check_out = strtotime($check_out_date);
        $total_days = ($check_out - $check_in) / (60 * 60 * 24);

        if ($total_days <= 0) {
            $message = "Invalid date range. Check-out date must be after check-in date.";
            $message_class = "error";
        } else {
            // Fetch room availability
            $room_column = ($room_type === 'Single') ? 'single_room_availability' : 'double_room_availability';
            $room_price_column = ($room_type === 'Single') ? 'single_room_price' : 'double_room_price';

            $query = "SELECT $room_column, $room_price_column FROM hotels WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $hotel_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $hotel = $result->fetch_assoc();
                $available_rooms = $hotel[$room_column];
                $room_price = $hotel[$room_price_column];

                if ($num_rooms <= $available_rooms) {
                    // Update room availability
                    $new_room_count = $available_rooms - $num_rooms;
                    $update_query = "UPDATE hotels SET $room_column = ? WHERE id = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("ii", $new_room_count, $hotel_id);
                    $update_stmt->execute();

                    // Calculate total price
                    $total_price = $room_price * $total_days * $num_rooms;

                    // Insert booking details into the database
                    $insert_query = "INSERT INTO bookings (hotel_id, customer_name, customer_contact, check_in_date, check_out_date, room_type, num_rooms, total_price)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $insert_stmt = $conn->prepare($insert_query);
                    $insert_stmt->bind_param(
                        "isssssii",
                        $hotel_id,
                        $customer_name,
                        $customer_contact,
                        $check_in_date,
                        $check_out_date,
                        $room_type,
                        $num_rooms,
                        $total_price
                    );

                    if ($insert_stmt->execute()) {
                        $message = "Booking confirmed successfully for $num_rooms $room_type room(s)! Check-in: $check_in_date, Check-out: $check_out_date. Total Price: ₹" . number_format($total_price, 2);
                        $message_class = "success";
                    } else {
                        $message = "Error: Unable to complete the booking. Please try again later.";
                        $message_class = "error";
                    }
                } else {
                    $message = "Booking failed: Not enough $room_type rooms are available.";
                    $message_class = "error";
                }
            } else {
                $message = "Invalid hotel ID.";
                $message_class = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="container">
        <h1>Booking Status</h1>
        <div class="message <?php echo $message_class; ?>">
            <?php echo $message; ?>
        </div>
        <a href="tourist_dashboard.php">Go Back to Home</a>
    </div>
</body>
</html>

