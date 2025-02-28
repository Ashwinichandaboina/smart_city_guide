<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartcity"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = isset($_GET['table']) ? $_GET['table'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch column names of the selected table
if ($table) {
    $columns_result = $conn->query("DESCRIBE $table");
    $columns = [];
    if ($columns_result) {
        while ($column_row = $columns_result->fetch_assoc()) {
            $columns[] = $column_row['Field'];
        }
    }

    // Fetch the record that needs to be updated
    $select_query = "SELECT * FROM $table WHERE id = $id";
    $record_result = $conn->query($select_query);
    $record = $record_result->fetch_assoc();
}

// Handle Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_data'])) {
    $columns = $_POST['columns'];
    $update_values = [];
    foreach ($columns as $column => $value) {
        $update_values[] = "$column = '$value'";
    }

    $update_query = "UPDATE $table SET " . implode(",", $update_values) . " WHERE id = $id";
    if ($conn->query($update_query)) {
        echo "<div class='success'>Data updated successfully!</div>";
        header("Location: admin.php?table=$table");
        exit();
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f9f9f9;
        }
        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
        }
        .form-group input[type="submit"] {
            background-color: #003366;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
        .success {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .error {
            background-color: #f44336;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 30px;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Record in <?php echo ucfirst($table); ?></h2>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_data'])): ?>
        <div class="<?php echo isset($conn->error) ? 'error' : 'success'; ?>">
            <?php echo isset($conn->error) ? 'Error: ' . $conn->error : 'Data updated successfully!'; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="table" value="<?php echo $table; ?>">

        <?php foreach ($columns as $column): ?>
            <div class="form-group">
                <label for="<?php echo $column; ?>"><?php echo ucfirst($column); ?>:</label>
                <input type="text" name="columns[<?php echo $column; ?>]" value="<?php echo $record[$column]; ?>" required>
            </div>
        <?php endforeach; ?>

        <div class="form-group">
            <input type="submit" name="update_data" value="Update Data">
        </div>
    </form>
</div>

<div class="footer">
    <p>&copy; 2024 Smart City Admin Panel. All rights reserved.</p>
</div>

</body>
</html>

<?php
$conn->close();
?>
