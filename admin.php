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

// Handle table selection and record actions
$table = isset($_GET['table']) ? $_GET['table'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Handle Insert Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_data']) && $table) {
    $columns = $_POST['columns'];
    $column_names = implode(",", array_keys($columns));
    $values = implode(",", array_map(function ($value) {
        return "'" . $value . "'";
    }, array_values($columns)));

    $insert_query = "INSERT INTO $table ($column_names) VALUES ($values)";

    if ($conn->query($insert_query)) {
        echo "<div class='success'>Data inserted successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}

// Handle Delete Data
if ($action == 'delete' && $id && $table) {
    $delete_query = "DELETE FROM $table WHERE id = $id";
    if ($conn->query($delete_query)) {
        echo "<div class='success'>Record deleted successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}

// Fetch tables for selection
$tables_result = $conn->query("SHOW TABLES");
$tables = [];
if ($tables_result) {
    while ($row = $tables_result->fetch_assoc()) {
        $tables[] = $row['Tables_in_' . $dbname];
    }
}

// Fetch column names of the selected table
if ($table) {
    $columns_result = $conn->query("DESCRIBE $table");
    $columns = [];
    if ($columns_result) {
        while ($column_row = $columns_result->fetch_assoc()) {
            $columns[] = $column_row['Field'];
        }
    }

    // Fetch records from the selected table
    $select_query = "SELECT * FROM $table";
    $records_result = $conn->query($select_query);
    $records = [];
    if ($records_result) {
        while ($row = $records_result->fetch_assoc()) {
            $records[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e4f0f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            font-size: 36px;
        }
        select, input[type="text"], input[type="number"], input[type="submit"] {
            padding: 12px;
            margin: 12px 0;
            width: 100%;
            box-sizing: border-box;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #003366;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #005bb5;
        }
        .table-select {
            margin-bottom: 30px;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }
        .action-btn {
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            margin: 0 5px;
            font-size: 14px;
            cursor: pointer;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .action-btn:hover {
            background-color: #002244;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        .success {
            background-color: #28a745;
            color: white;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .error {
            background-color: #dc3545;
            color: white;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Panel</h1>

    <!-- Table Selection -->
    <form method="GET" class="table-select">
        <label for="table">Select Table:</label>
        <select name="table" id="table" onchange="this.form.submit()">
            <option value="">--Select Table--</option>
            <?php foreach ($tables as $table_name): ?>
                <option value="<?php echo $table_name; ?>" <?php echo ($table == $table_name) ? 'selected' : ''; ?>>
                    <?php echo ucfirst($table_name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    

    <?php if ($table): ?>
        <!-- Insert Data -->
        <h2>Insert Data into <?php echo ucfirst($table); ?></h2>
        <form method="POST">
            <input type="hidden" name="table" value="<?php echo $table; ?>">

            <?php foreach ($columns as $column): ?>
                <div class="form-section">
                    <label for="<?php echo $column; ?>"><?php echo ucfirst($column); ?>:</label>
                    <input type="text" name="columns[<?php echo $column; ?>]" required>
                </div>
            <?php endforeach; ?>

            <input type="submit" name="insert_data" value="Insert Data">
        </form>

        <!-- Display Records -->
        <h2>Records in <?php echo ucfirst($table); ?></h2>
        <table>
            <thead>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?php echo ucfirst($column); ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <td><?php echo htmlspecialchars($record[$column]); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="admin.php?table=<?php echo $table; ?>&action=delete&id=<?php echo $record['id']; ?>" class="action-btn">Delete</a>
                            <a href="update.php?table=<?php echo $table; ?>&id=<?php echo $record['id']; ?>" class="action-btn">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="action-btn back-btn">Back to Dashboard</a>
    <?php endif; ?>
</div>

<div class="footer">
    <p>&copy; 2024 Smart City Admin Panel. All rights reserved.</p>
</div>

</body>
</html>

<?php
$conn->close();
?>
