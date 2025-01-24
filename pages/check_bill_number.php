<?php
header("Content-Type: text/plain"); // Ensure plain response

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "okdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the input bill number
if (isset($_POST['bill_number'])) {
    $bill_number = trim($_POST['bill_number']);
    $stmt = $conn->prepare("SELECT COUNT(*) FROM new_bill WHERE bill_no = ?");
    $stmt->bind_param("s", $bill_number);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    // Check if the bill number exists
    if ($count > 0) {
        echo "exists";
    } else {
        echo "available";
    }

    $stmt->close();
}
$conn->close();
?>
