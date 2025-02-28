<?php
include 'db.php';  // Include the database connection

// Initialize a variable to store hotel details
$hotel = null;

// Check if the hotel_id is passed in the URL
if (isset($_GET['hotel_id']) && is_numeric($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];

    // Query the database to fetch the hotel details
    $query = "SELECT * FROM hotels WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a hotel is found
    if ($result->num_rows > 0) {
        // Fetch the hotel details
        $hotel = $result->fetch_assoc();
    } else {
        // No hotel found for the given hotel_id
        echo "Hotel not found.";
        exit;
    }
} else {
    // Missing or invalid hotel_id
    echo "Invalid request. Hotel ID is missing or invalid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>

    <!-- Inline CSS for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin: 20px 0;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .hotel-details p {
            margin: 5px 0;
            font-size: 16px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, select {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book Hotel</h2>
        <?php if ($hotel): ?>
            <div class="hotel-details">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($hotel['name']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($hotel['location']); ?></p>
                <p><strong>Single Room Price:</strong> ₹<?php echo htmlspecialchars($hotel['single_room_price']); ?> per night</p>
                <p><strong>Double Room Price:</strong> ₹<?php echo htmlspecialchars($hotel['double_room_price']); ?> per night</p>
                <p><strong>Single Room Availability:</strong> <?php echo htmlspecialchars($hotel['single_room_availability']); ?></p>
                <p><strong>Double Room Availability:</strong> <?php echo htmlspecialchars($hotel['double_room_availability']); ?></p>
            </div>

            <form method="POST" action="confirm_booking1.php" onsubmit="return validateForm()">
                <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">

                <label for="customer_name">Your Name:</label>
                <input type="text" name="customer_name" required>

                <label for="customer_contact">Your Contact:</label>
                <input type="text" name="customer_contact" required>

                <label for="check_in_date">Check-in Date:</label>
                <input type="date" id="check_in_date" name="check_in_date" required min="<?php echo date('Y-m-d'); ?>" onchange="updateCheckOutDate()">

                <label for="check_out_date">Check-out Date:</label>
                <input type="date" id="check_out_date" name="check_out_date" required disabled onchange="updateTotalPrice()">

                <label for="room_type">Room Type:</label>
                <select id="room_type" name="room_type" required onchange="updateTotalPrice()">
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                </select>

                <label for="num_rooms">Number of Rooms:</label>
                <input type="number" id="num_rooms" name="num_rooms" required min="1" value="1" onchange="updateTotalPrice()">

                <label for="total_price">Total Price (₹):</label>
                <input type="number" name="total_price" id="total_price" readonly>

                <button type="submit">Confirm Booking</button>
            </form>
        <?php else: ?>
            <p>No hotel information available.</p>
        <?php endif; ?>
    </div>

    <script>
        function validateForm() {
            const checkInDate = new Date(document.getElementById('check_in_date').value);
            const checkOutDate = new Date(document.getElementById('check_out_date').value);

            if (checkOutDate <= checkInDate) {
                alert("Check-out date must be after check-in date.");
                return false;
            }
            return true;
        }

        function updateCheckOutDate() {
            const checkInDate = document.getElementById('check_in_date').value;
            const checkOutDate = document.getElementById('check_out_date');

            checkOutDate.disabled = false;
            checkOutDate.min = checkInDate;
            checkOutDate.value = ''; // Clear previous value
        }

        function updateTotalPrice() {
            const checkInDate = new Date(document.getElementById('check_in_date').value);
            const checkOutDate = new Date(document.getElementById('check_out_date').value);
            const roomType = document.getElementById('room_type').value;
            const numRooms = parseInt(document.getElementById('num_rooms').value) || 1;

            if (checkInDate && checkOutDate && numRooms > 0) {
                const dayDifference = (checkOutDate - checkInDate) / (1000 * 3600 * 24);

                if (dayDifference > 0) {
                    const singleRoomPrice = <?php echo $hotel['single_room_price']; ?>;
                    const doubleRoomPrice = <?php echo $hotel['double_room_price']; ?>;
                    const roomPrice = roomType === 'Single' ? singleRoomPrice : doubleRoomPrice;

                    const totalPrice = roomPrice * dayDifference * numRooms;
                    document.getElementById('total_price').value = totalPrice.toFixed(2);
                }
            }
        }

        document.getElementById('check_in_date').addEventListener('change', updateTotalPrice);
        document.getElementById('check_out_date').addEventListener('change', updateTotalPrice);
        document.getElementById('room_type').addEventListener('change', updateTotalPrice);
        document.getElementById('num_rooms').addEventListener('change', updateTotalPrice);
    </script>
</body>
</html>

