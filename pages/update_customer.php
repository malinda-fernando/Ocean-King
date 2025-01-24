<?php
// Start session and connect to database
session_start();
$conn = new mysqli('localhost', 'root', '', 'okdb');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $route = $conn->real_escape_string($_POST['route']);
    
    // Handle file upload
    $profile_image = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = 'uploads/';
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            $profile_image = $upload_dir . $new_file_name;
            move_uploaded_file($file_tmp, $profile_image);
        } else {
            echo "<script>alert('Invalid image format'); history.go(-1);</script>";
            exit();
        }
    }

    // Update customer data
    $sql = "UPDATE customer SET 
                name = '$name', 
                email = '$email', 
                phone = '$phone', 
                address = '$address',
                route = '$route'";
                
    if (!empty($profile_image)) {
        $sql .= ", profile_image = '$profile_image'";
    }
    $sql .= " WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Customer updated successfully'); window.location.href = 'customer_details.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
