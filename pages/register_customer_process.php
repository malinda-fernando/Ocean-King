<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'okdb');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $route = $conn->real_escape_string($_POST['route']);


    // Handle file upload
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
    }

    $file_name = $_FILES['profile_image']['name'];
    $file_tmp = $_FILES['profile_image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = uniqid() . '.' . $file_ext;
        $file_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert data into the database
            $sql = "INSERT INTO customer (name, email, phone, address,route, profile_image) 
                    VALUES ('$customer_name', '$email', '$phone', '$address','$route', '$file_path')";
            if ($conn->query($sql) === TRUE) {
                echo "<script type='text/javascript'>
                        alert('Customer registered successfully!');
                        window.location.href = 'register_customer_form.php';
                      </script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "<script type='text/javascript'>
                    alert('Failed to upload the image.');
                    window.location.href = 'register_customer_form.php';
                  </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.');
                window.location.href = 'register_customer_form.php';
              </script>";
    }

    $conn->close();
}
?>
