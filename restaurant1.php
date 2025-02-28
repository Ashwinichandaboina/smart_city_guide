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

// Get the search query if it exists
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch all restaurants based on the search query
$query = "SELECT id, name, location, cuisine, type FROM restaurants WHERE name LIKE ? OR location LIKE ?";
$stmt = $conn->prepare($query);
$search_term = "%$search_query%";
$stmt->bind_param('ss', $search_term, $search_term);
$stmt->execute();
$restaurants_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <style>
        /* Reset styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f1e1; /* cream background */
            color: #1f2a44; /* navy blue text */
        }

        header {
            background-color: #1f2a44; /* navy blue */
            padding: 20px 0;
            text-align: center;
            border-bottom: 4px solid #f4f1e1; /* cream color */
        }

        header h1 {
            font-size: 2.5em;
            color: #f4f1e1; /* cream text */
        }

        .search-container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .search-bar {
            padding: 10px;
            width: 60%;
            font-size: 1em;
            border: 2px solid #1f2a44;
            border-radius: 5px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: #1f2a44;
            color: white;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #4a5a7d; /* Lighter navy for hover */
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        .restaurants-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .restaurant-card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            width: 30%;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .restaurant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .restaurant-card h3 {
            font-size: 1.6em;
            color: #1f2a44; /* Navy Blue */
            cursor: pointer;
            text-decoration: none;
            transition: color 0.3s, text-decoration 0.3s;
        }

        .restaurant-card h3:hover {
            color: #4a5a7d; /* Lighter navy for hover */
            text-decoration: underline;
        }

        .restaurant-card p {
            margin: 10px 0;
            color: #4a5a7d; /* Soft navy color */
        }
    </style>
</head>
<body>
    <header>
        <h1>Hyderabad Restaurants</h1>
    </header>

    <!-- Search Bar -->
    <div class="search-container">
        <form action="" method="POST" style="width: 100%; display: flex; justify-content: center;">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" class="search-bar" placeholder="Search restaurants or location">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <div class="container">
        <div class="restaurants-list">
            <?php
            if ($restaurants_result->num_rows > 0) {
                while ($row = $restaurants_result->fetch_assoc()) {
                    echo "<div class='restaurant-card'>";
                    echo "<h3><a href='menu1.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></h3>";
                    echo "<p><strong>Cuisine:</strong> " . $row['cuisine'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
                    echo "<p><strong>Type:</strong> " . $row['type'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No restaurants found matching your search criteria.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
