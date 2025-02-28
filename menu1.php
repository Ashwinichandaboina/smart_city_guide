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

// Start session for cart functionality
session_start();

// Fetch the restaurant details
$restaurant_id = $_GET['id'];
$restaurant_query = "SELECT * FROM restaurants WHERE id = $restaurant_id";
$restaurant_result = $conn->query($restaurant_query);
$restaurant = $restaurant_result->fetch_assoc();

// Fetch dishes for the restaurant
$dishes_query = "SELECT * FROM dishes WHERE restaurant_id = $restaurant_id";
$dishes_result = $conn->query($dishes_query);

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $dish_id = $_POST['dish_id'];
    $quantity = $_POST['quantity'];
    $dish_query = "SELECT * FROM dishes WHERE id = $dish_id";
    $dish_result = $conn->query($dish_query);
    $dish = $dish_result->fetch_assoc();

    // Add dish to cart
    $cart_item = [
        'id' => $dish['id'],
        'name' => $dish['dish_name'],
        'price' => $dish['price'],
        'quantity' => $quantity
    ];

    // Check if cart already exists in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add the item to the cart
    $_SESSION['cart'][] = $cart_item;
}

// Handle removing from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];

    // Remove the item from the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Reindex the cart array to avoid gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Handle order confirmation
if (isset($_POST['order_now'])) {
    $order_details = $_SESSION['cart'];
    $total_price = 0;

    // Calculate total price
    foreach ($order_details as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    // Empty the cart after order
    $_SESSION['cart'] = [];

    // Display order confirmation
    $order_confirmation = [
        'order_details' => $order_details,
        'total_price' => $total_price
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $restaurant['name']; ?> Menu</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F5F5F5; /* Light Gray */
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #003366; /* Navy Blue */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        header a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #004d80;
            transition: background-color 0.3s;
            position: absolute;
            left: 20px;
        }

        header a:hover {
            background-color: #003366;
        }

        h1 {
            margin: 0;
            text-align: center;
            flex-grow: 1; /* Ensures restaurant name is centered */
        }

        .container {
            display: flex;
            gap: 30px;
            margin: 30px;
        }

        .cart {
            flex: 0 0 35%; /* Cart occupies 35% of the page */
            background-color: #FFF8E1; /* Cream background for cart */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .menu {
            flex: 1; /* Menu occupies the remaining space */
            background-color: #FFF8E1; /* Cream background for menu */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #003366; /* Navy Blue */
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px; /* Added gap between each dish */
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #ffffff; /* White background for dish */
            color: #003366; /* Navy Blue text */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            font-size: 16px; /* Medium dish font size */
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .menu-item form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            background-color: #003366; /* Navy Blue Button */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #001f33; /* Darker Navy Blue on hover */
        }

        .remove-btn {
            color: #DC3545; /* Red */
            text-decoration: none;
        }

        .remove-btn:hover {
            text-decoration: underline;
        }

        .order-confirmation {
            margin-top: 20px;
            background-color: #f5f5f5; /* Light Gray */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .thank-you {
            font-weight: bold;
            font-size: 20px;
            color: #28A745; /* Green */
            margin-bottom: 15px;
        }

        .back-btn {
            margin-top: 20px;
            display: block;
            background-color: #6C757D; /* Gray */
            color: white;
            padding: 10px 15px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #5A6268; /* Darker Gray */
        }
    </style>
</head>
<body>
    <header>
        <a href="restaurant1.php">Back to Restaurants</a>
        <h1><?php echo $restaurant['name']; ?> Menu</h1>
    </header>

    <div class="container">
        <!-- Cart Section -->
        <div class="cart">
            <h3>Your Cart</h3>
            <?php
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                echo "<ul>";
                $total_price = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total_price += $item['price'] * $item['quantity'];
                    echo "<li>" . $item['name'] . " - ₹" . $item['price'] . " x " . $item['quantity'] . 
                    " <a href='?id=$restaurant_id&remove=" . $item['id'] . "' class='remove-btn'>Remove</a></li>";
                }
                echo "</ul>";
                echo "<p><strong>Total Price:</strong> ₹" . $total_price . "</p>";
                echo "<form method='POST'><button type='submit' name='order_now' class='btn'>Order Now</button></form>";
            } else {
                echo "<p>Your cart is empty.</p>";
            }

            // Display order confirmation
            if (isset($order_confirmation)) {
                echo "<div class='order-confirmation'>";
                echo "<div class='thank-you'>Thank you for your order!</div>";
                echo "<ul>";
                foreach ($order_confirmation['order_details'] as $order_item) {
                    echo "<li>" . $order_item['name'] . " x " . $order_item['quantity'] . " - ₹" . $order_item['price'] . "</li>";
                }
                echo "</ul>";
                echo "<p><strong>Total Price:</strong> ₹" . $order_confirmation['total_price'] . "</p>";
                echo "</div>";
            }
            ?>
        </div>

        <!-- Menu Section -->
        <div class="menu">
            <h3>Menu</h3>
            <?php
            if ($dishes_result->num_rows > 0) {
                while ($dish = $dishes_result->fetch_assoc()) {
                    echo "<div class='menu-item'>";
                    echo "<span>" . $dish['dish_name'] . " - ₹" . $dish['price'] . "</span>";
                    echo "<form method='POST'>
                            <input type='hidden' name='dish_id' value='" . $dish['id'] . "'>
                            <input type='number' name='quantity' value='1' min='1' style='width: 50px;'>
                            <button type='submit' name='add_to_cart' class='btn'>Add to Cart</button>
                          </form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No dishes available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
