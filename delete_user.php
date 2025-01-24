<?php
// Start the session
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}
// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "okdb";   

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the ID is passed via the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID to prevent SQL injection

    // Prepare the SQL DELETE query
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Record deleted successfully, redirect to manage_user.php
        header("Location: manage_user.php");
        exit();
    } else {
        // Handle errors
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    // If ID is not provided, redirect to manage_user.php
    header("Location: manage_user.php");
    exit();
}

$conn->close();
?>
