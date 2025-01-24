<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "okdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected route
if (isset($_GET['route'])) {
    $route = $conn->real_escape_string($_GET['route']);

    // Fetch shops for the selected route
    $sql = "SELECT * FROM customer WHERE route = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $route);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "<script>alert('No route selected.'); window.location.href = 'routes.php';</script>";
    exit();
}
// Variables to handle route and search query
$route = isset($_GET['route']) ? $conn->real_escape_string($_GET['route']) : '';
$search_query = isset($_GET['search_query']) ? $conn->real_escape_string($_GET['search_query']) : '';

// Redirect if no route is selected
if (!$route) {
    echo "<script>alert('No route selected.'); window.location.href = 'routes.php';</script>";
    exit();
}

// Adjust SQL query based on whether a search query is provided
if ($search_query) {
    $sql = "SELECT * FROM customer WHERE route = ? AND name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_query_param = "%" . $search_query . "%";
    $stmt->bind_param("ss", $route, $search_query_param);
} else {
    $sql = "SELECT * FROM customer WHERE route = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $route);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shops for Route: <?php echo htmlspecialchars($route); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }

        .search-container {
        background: #ffffff; /* White background */
        padding: 20px; /* Add spacing around the search box */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Light shadow */
    }

    .form-control:focus {
        border-color: #007bff; /* Bootstrap primary color */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Glow effect on focus */
    }

    .btn-primary {
        transition: transform 0.2s ease-in-out, background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Darker primary on hover */
        transform: scale(1.05); /* Slightly enlarge the button */
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Shops for Route: <?php echo htmlspecialchars($route); ?></h1>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Search Form -->
     <form action="" method="GET" class="mb-4">
            <input type="hidden" name="route" value="<?php echo htmlspecialchars($route); ?>">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search_query" 
                    class="form-control" 
                    placeholder="Search by customer name..." 
                    value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>"
                >
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="text-transform: uppercase;"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td>
                            <a href="view_credit.php?name=<?php echo urlencode($row['name']); ?>" class="btn btn-warning btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No shops found for this route.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
