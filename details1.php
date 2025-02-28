<?php
include 'db.php';

if (isset($_GET['tourist_place_id'])) {
    $tourist_place_id = filter_input(INPUT_GET, 'tourist_place_id', FILTER_VALIDATE_INT);

    if ($tourist_place_id === false) {
        die("Invalid tourist place ID.");
    }

    // Fetch tourist place details
    $query = "SELECT * FROM tourist_places WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tourist_place_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $place = $result->fetch_assoc();
    } else {
        die("Tourist place not found.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($place['name']); ?> - Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            margin: 0 0 20px;
        }
        p {
            line-height: 1.6;
        }
        img.place-image {
            max-width: 800px;
            height: auto;
            border-radius: 10px;
        }
        a {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($place['name']); ?></h1>
        <?php if (!empty($place['image'])): ?>
            <img class="place-image" src="data:<?php echo htmlspecialchars($place['image_type']); ?>;base64,<?php echo base64_encode($place['image']); ?>" 
                 alt="Image of <?php echo htmlspecialchars($place['name']); ?>">
        <?php else: ?>
            <p><strong>Image not available.</strong></p>
        <?php endif; ?>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($place['location']); ?></p>
        <p><strong>Entry Fee:</strong> ₹<?php echo number_format($place['entry_fee'], 2); ?></p>
        <p><strong>Timings:</strong> <?php echo htmlspecialchars($place['timings']); ?></p>
        <p><strong>Availability:</strong> <?php echo $place['availability']; ?> spots</p>
        <h3>Description:</h3>
        <p><?php echo nl2br(htmlspecialchars($place['description'])); ?></p>
        <h3>History:</h3>
        <p><?php echo nl2br(htmlspecialchars($place['history'])); ?></p>
        <a href="view_place1.php">Back to Tourist Places</a>
    </div>
</body>
</html>
