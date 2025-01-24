<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root"; // Change as per your DB credentials
$password = "";     // Change as per your DB credentials
$database = "okdb";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$search_query = "";
$result = null;
$total_sum = 0; // Variable to store the sum of totals

// Query to fetch data (with search functionality)
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = "%" . $conn->real_escape_string($_GET['search_query']) . "%";
    $stmt = $conn->prepare("SELECT * FROM old_bill WHERE customer LIKE ? AND status = 'Pending'");
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM old_bill WHERE status = 'Pending'";
    $result = $conn->query($sql);
}

// Calculate the total sum
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_sum += $row['total']; // Add the current total to the total_sum
    }
    $result->data_seek(0); // Reset pointer for reuse
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>ALL Credit List</title>
    <link rel="icon" type="image/png" href="imgs\3.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #333;
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-size: 2.5rem;
            text-transform: uppercase;
        }

        table {
            width: 95%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f3ff;
        }

        td {
            color: #333;
            font-weight: 600;
        }

        td, th {
            border: 1px solid #ddd;
        }

        .no-records {
            text-align: center;
            color: #f44336;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            text-align: center;
            display: block;
            width: fit-content;
            margin: 30px auto;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .navbar {
            width: 100%;
            height: 7vh;
            background-color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: sticky;
            z-index: 1000;
        }

        .navbar h1 {
            color: #ecf0f1;
            font-size: 20px;
        }

        .navbar ul {
            display: flex;
            list-style: none;
            gap: 15px;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
            background: transparent;
            border-radius: 5px;
            transition: 0.3s;
        }

        .navbar ul li a:hover {
            background-color: #3498db;
        }

        .logout-btn {
            background: #e74c3c;
        }

        .total-sum {
            display: flex;
            justify-content: flex-end;
            margin-right: 30px;
            margin-top: 20px;
            font-size: 1.7rem;
            font-weight: bold;
            color: #ecf0f1;
        }
        .routes-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
        }

        .route-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }
        .route-button {
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .route-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <ul>
            <li><a href="admin_dashboard.php">Home</a></li>
            <li><a href="manage_user.php">Users</a></li>
            <li><a href="full_bill.php">Full Bill</a></li>
            <li><a href="pages/cheque.php">All Cheques</a></li>
            <li><a class="logout-btn" href="logout.php" style="margin-right:20px;">Logout</a></li>
        </ul>
    </div>

    <h1 style="color: #ecf0f1;">All Credit List</h1>
    <div style="text-align: center;">
        <a href="routes.php" class="route-button" style="text-decoration:none;">Go to Route Page</a>
    </div>
    <!-- Total Sum -->
    <div class="total-sum">
        Total Credit: Rs.<?php echo number_format($total_sum, 2); ?>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>Bill No</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Date & Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['bill_no']}</td>
                            <td>{$row['customer']}</td>
                            <td>{$row['total']}</td>
                            <td>{$row['date_time']}</td>
                            <td>{$row['status']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-records'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
