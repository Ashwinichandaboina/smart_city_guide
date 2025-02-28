<?php
include 'db.php'; // Include your database connection file

// Get the current date
$current_date = date('Y-m-d');

// Query to check for bookings where checkout date has passed
$query = "
    SELECT hotel_id, room_type, num_rooms, check_out_date
    FROM bookings
    WHERE check_out_date < ? AND status != 'checked_out'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $current_date);
$stmt->execute();
$result = $stmt->get_result();

while ($booking = $result->fetch_assoc()) {
    $hotel_id = $booking['hotel_id'];
    $room_type = $booking['room_type'];
    $num_rooms = $booking['num_rooms'];

    // Update the availability in the hotels table
    if ($room_type == 'Single') {
        $update_query = "UPDATE hotels SET single_room_availability = single_room_availability + ? WHERE id = ?";
    } else {
        $update_query = "UPDATE hotels SET double_room_availability = double_room_availability + ? WHERE id = ?";
    }

    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $num_rooms, $hotel_id);
    $update_stmt->execute();

    // Optionally mark the booking as 'checked_out' to avoid updating it again
    $update_booking_query = "UPDATE bookings SET status = 'checked_out' WHERE hotel_id = ? AND check_out_date < ?";
    $update_booking_stmt = $conn->prepare($update_booking_query);
    $update_booking_stmt->bind_param("is", $hotel_id, $current_date);
    $update_booking_stmt->execute();
}

echo "Room availability updated successfully.";
?>

