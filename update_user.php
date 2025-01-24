<?php
// Start the session
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "okdb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : null;
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email)) {
        echo "Username and email are required.";
        exit();
    }

    if (!empty($newPassword) && ($newPassword !== $confirmPassword)) {
        echo "Passwords do not match.";
        exit();
    }
 // Fetch the current hashed password from the database
 $sql = "SELECT password FROM users WHERE id = ?";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("i", $userId);
 $stmt->execute();
 $result = $stmt->get_result();

 if ($result->num_rows === 1) {
     $user = $result->fetch_assoc();
     $hashedPassword = $user['password'];

     // Verify the current password
     if (!password_verify($currentPassword, $hashedPassword)) {
         echo "Current password is incorrect.";
         exit();
     }
 } else {
     echo "User not found.";
     exit();
 }

 // Check if a new password is provided and validate it
 if (!empty($newPassword)) {
     if ($newPassword !== $confirmPassword) {
         echo "New passwords do not match.";
         exit();
     }

     // Hash the new password
     $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

     // Update query with the new password
     $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("sssi", $username, $email, $hashedNewPassword, $userId);
 } else {
     // Update query without changing the password
     $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("ssi", $username, $email, $userId);
 }

    if ($stmt->execute()) {
        header("Location: edit_user.php?id=$userId&status=success");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
