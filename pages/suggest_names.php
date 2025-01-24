<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "okdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'query' is set in the GET request
if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);

    // Query to fetch matching customer names
    $sql = "SELECT name FROM customer WHERE name LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);

    $suggestions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['name'];
        }
    }

    // Return suggestions as a JSON array
    echo json_encode($suggestions);
}

// Close the connection
$conn->close();
?>
