<?php
session_start(); // Start the session

// Check if the session username is set
if (!isset($_SESSION['username'])) {
    die("Session expired. Please log in again."); // Handle unauthenticated access
}

$username = $_SESSION['username']; // Retrieve username from session

// Database connection
$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "okdb";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get bill details from POST
$bill_number = $_POST['bill_number'];
$customer_name = $_POST['customer_name'];
$payment_method = $_POST['payment_method'];
$route = $_POST['route'];
$total_amount = $_POST['total_amount']; // Total Amount field

// Get item details
$item_details = $_POST['item_details'];
$rate = $_POST['rate'];
$quantity = $_POST['quantity'];
$amount = $_POST['amount'];

// Insert each item's details into 'new_bill'
foreach ($item_details as $index => $item) {
    $item_rate = $rate[$index];
    $item_quantity = $quantity[$index];
    $item_amount = $amount[$index];

    $sql = "INSERT INTO new_bill (bill_no, customer, route, item, rate, quantity, amount, payment_method, total) 
            VALUES ('$bill_number', '$customer_name','$route', '$item', '$item_rate', '$item_quantity', '$item_amount', '$payment_method', '$total_amount')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Insert summary into 'full_bill' with the username in 'emp'
$tracker1 = $payment_method; 
$tracker_3_value = "Received"; // Default value for tracker_3
$sql_full_bill = "INSERT INTO full_bill (bill_no, customer_name, route,  total, tracker_1, emp, date_time)
                  VALUES ('$bill_number', '$customer_name','$route', '$total_amount', '$tracker1', '$username', CURRENT_TIMESTAMP)";

if ($conn->query($sql_full_bill) === TRUE) {
    // Insert the data into 'oldbill', 'cheque', or handle 'cash' cases
    if ($payment_method == 'Credit') {
        $sql_oldbill = "INSERT INTO old_bill (bill_no, customer, route,  total, date_time)
                        VALUES ('$bill_number', '$customer_name','$route', '$total_amount', CURRENT_TIMESTAMP)";

        if ($conn->query($sql_oldbill) !== TRUE) {
            echo "Error inserting into oldbill: " . $conn->error;
        }
    } elseif ($payment_method == 'Cheque') {
        $sql_cheque = "INSERT INTO cheques (bill_no, customer, route,  total, date_time)
                       VALUES ('$bill_number', '$customer_name','$route', '$total_amount', CURRENT_TIMESTAMP)";

        if ($conn->query($sql_cheque) !== TRUE) {
            echo "Error inserting into cheque: " . $conn->error;
        }
    } elseif ($payment_method == 'Cash') {
        $sql_update_tracker3 = "UPDATE full_bill 
                                SET tracker_3 = '$tracker_3_value' 
                                WHERE bill_no = '$bill_number'";

        if ($conn->query($sql_update_tracker3) !== TRUE) {
            echo "Error updating tracker_3: " . $conn->error;
        }
    }

    echo "<script>alert('Bill submitted successfully!'); window.location.href='newbill.php';</script>";
} else {
    echo "Error: " . $sql_full_bill . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
