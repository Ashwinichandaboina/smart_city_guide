<?php include 'db.php'; 
// Fetch all tourist places
$query = "SELECT id, name, location, entry_fee, timings, availability FROM tourist_places";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $places = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $places = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Places</title>
    <style>
        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffddc1, #ffb6c1, #ffc0cb, #ff69b4, #ff1493); /* Light mixed vibrant gradient */
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9); /* Slightly transparent */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #007BFF;
            margin-bottom: 30px;
            border-bottom: 3px solid #007BFF;
            padding-bottom: 10px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            color: transparent;
        }

        .place {
            margin-bottom: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .place:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .place h3 {
            font-size: 1.5rem;
            color: #007BFF;
        }

        .place p {
            margin: 8px 0;
            color: #555;
        }

        a {
            display: inline-block;
            font-size: 0.9rem;
            color: white;
            background-color: #007BFF;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .back-button a {
            background: linear-gradient(135deg, #ffddc1, #ffb6c1, #ffc0cb, #ff69b4, #ff1493); /* Mixed vibrant gradient */
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .back-button a:hover {
            background: linear-gradient(135deg, #ff69b4, #ff1493, #ffb6c1, #ffddc1); /* Darker gradient on hover */
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .place {
                padding: 15px;
            }

            h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem;
            }

            a {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .back-button a {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tourist Places</h1>
        <?php foreach ($places as $place): ?>
            <div class="place">
                <h3><?php echo htmlspecialchars($place['name']); ?></h3>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($place['location']); ?></p>
                <p><strong>Entry Fee:</strong> ₹<?php echo number_format($place['entry_fee'], 2); ?></p>
                <p><strong>Timings:</strong> <?php echo htmlspecialchars($place['timings']); ?></p>
                <p><strong>Availability:</strong> <?php echo $place['availability']; ?> spots</p>
                <a href="details1.php?tourist_place_id=<?php echo $place['id']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>
        <!-- Back Button -->
        <div class="back-button">
            <a href="tourist_dashboard.php">Back to Services</a>
        </div>
    </div>
</body>
</html>

