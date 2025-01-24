<?php
// Start session and connect to database
session_start();
$conn = new mysqli('localhost', 'root', '', 'okdb');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the customer ID is provided
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Fetch the customer's profile image to delete it from the server
    $sql = "SELECT profile_image FROM customer WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (!empty($row['profile_image']) && file_exists($row['profile_image'])) {
            unlink($row['profile_image']); // Delete the image file
        }

        // Delete the customer from the database
        $sql = "DELETE FROM customer WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Customer deleted successfully!');
                    window.location.href = 'customer_details.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error deleting customer: " . $conn->error . "');
                    window.location.href = 'customer_details.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Customer not found.');
                window.location.href = 'customer_details.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'customer_details.php';
          </script>";
}

$conn->close();
?>
