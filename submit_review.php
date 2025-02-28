<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartcity";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $restaurant_id = $_POST['restaurant_id'];
    $user_id = 1; // Assume the user ID is 1 for now; modify this as needed
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Insert review into the database
    $stmt = $conn->prepare("INSERT INTO reviews (restaurant_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('iiis', $restaurant_id, $user_id, $rating, $review_text);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: menu1.php?id=$restaurant_id"); // Redirect back to the restaurant page
    } else {
        echo "Error submitting review. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

