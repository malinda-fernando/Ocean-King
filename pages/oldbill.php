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

// Check connection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bill_no'], $_POST['payment_method'])) {
    // Capture POST data
    $bill_no = $_POST['bill_no'];
    $payment_method = $_POST['payment_method'];

    // Fetch additional details like customer and total for "Cheque" logic
    $stmtFetch = $conn->prepare("SELECT customer, total FROM old_bill WHERE bill_no = ?");
    if (!$stmtFetch) {
        die("Query preparation failed for fetching details: " . $conn->error);
    }
    $stmtFetch->bind_param("s", $bill_no);
    $stmtFetch->execute();
    $result = $stmtFetch->get_result();

    // If the bill number exists, retrieve details
    if ($row = $result->fetch_assoc()) {
        $customer = $row['customer'];
        $total = $row['total'];
    } else {
        echo "<script>alert('Bill No not found.');</script>";
        exit;
    }
    $stmtFetch->close();

    // Update the `old_bill` table
    $stmt1 = $conn->prepare("UPDATE old_bill SET status = 'Paid', payment_method = ?, paidtime = NOW() WHERE bill_no = ?");
    if (!$stmt1) {
        die("Query preparation failed for old_bill: " . $conn->error);
    }
    $stmt1->bind_param("ss", $payment_method, $bill_no);

    if ($stmt1->execute()) {
        // Update the `full_bill` table for all payment methods
        $tracker_value = $payment_method; // Use the payment method as the tracker value
        $stmt3 = $conn->prepare("UPDATE full_bill SET tracker_2 = ? WHERE bill_no = ?");
        if (!$stmt3) {
            die("Query preparation failed for full_bill: " . $conn->error);
        }
        $stmt3->bind_param("ss", $tracker_value, $bill_no);

        if ($stmt3->execute()) {
            echo "<script>alert('Tracker_2 updated for Bill No: $bill_no');</script>";
        } else {
            echo "<script>alert('Failed to update tracker_2 in full_bill for Bill No: $bill_no');</script>";
        }
        $stmt3->close();

        // Update tracker_3 if payment method is 'Cash'
        if ($payment_method === 'Cash') {
            $stmt4 = $conn->prepare("UPDATE full_bill SET tracker_3 = ' Received' WHERE bill_no = ?");
            if (!$stmt4) {
                die("Query preparation failed for tracker_3 update: " . $conn->error);
            }
            $stmt4->bind_param("s", $bill_no);
            $stmt4->execute();
            $stmt4->close();
        }

        // Additional logic for "Cheque"
        if ($payment_method === 'Cheque') {
            $stmt2 = $conn->prepare("INSERT INTO cheques (bill_no, customer, total, date_time) VALUES (?, ?, ?, NOW())");
            if (!$stmt2) {
                die("Query preparation failed for cheques: " . $conn->error);
            }
            $stmt2->bind_param("ssd", $bill_no, $customer, $total); // "s" = string, "d" = decimal

            if (!$stmt2->execute()) {
                echo "<script>alert('Failed to record cheque for Bill No: $bill_no');</script>";
            }
            $stmt2->close();
        }
    } else {
        echo "<script>alert('Failed to update status in old_bill for Bill No: $bill_no');</script>";
    }   
    $stmt1->close();

}

// Query to fetch rows where status is 'Pending'
$sql = "SELECT bill_no, customer, total, date_time FROM old_bill WHERE status = 'Pending'";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On Credite Bills    
    </title><link rel="icon" type="image/png" href="imgs\3.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }

        .search-container {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid white;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1 );
            margin-top:50px;
        }

        .search-container h2 {
            margin-bottom: 10px;
            text-align: center;
            
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-between;
        }

        .search-form input, .search-form select, .search-form button {
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid black;
            background: transparent;
            color: white;
            font-size: 14px;
        }

        .search-form input::placeholder {
            color:white;
        }

        .search-form button {
            flex-basis: 100%;
            background: purple;
            color: black;
            cursor: pointer;
            font-weight: bold;
        }

        .search-form button:hover {
            background: transparent;
            color: white;
            border: 1px solid white;
        }

       /* General Table Container Styling */
       .table-container {
    margin: 50px auto;
    max-width: 90%;
    padding: 20px;
    border-radius: 8px;
    background: #f9f9f9;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 200px;
    overflow-x: auto; /* Enables horizontal scrolling */
}

.table-container h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Arial', sans-serif;
    min-width: 600px; /* Ensure the table doesn't shrink too much */
}

thead {
    background-color:rgb(58, 85, 240);
    color: white;
    font-weight: bold;
}

thead th {
    padding: 12px;
    text-align: center;
    font-size: 1rem;
}

tbody tr {
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

td, th {
    padding: 10px;
    text-align: center;
    font-size: 0.95rem;
    color: #000;
}
/* Adjust font sizes and padding for smaller devices */
@media (max-width: 768px) {
    table {
        font-size: 0.8rem;
    }

    thead th, tbody td {
        padding: 8px;
    }

    .btn-submit {
        font-size: 0.8rem;
        padding: 6px 10px;
    }

    .payment-method {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    thead th, tbody td {
        font-size: 0.75rem;
        padding: 6px;
    }

    .btn-submit {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    .payment-method {
        font-size: 0.75rem;
    }
}
/* Dropdown Styling */
.payment-method {
    padding: 8px;
    width: 90%;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: #fff;
    font-size: 0.9rem;
    color: #333;
    cursor: pointer;
}

/* Button Styling */
.btn-submit {
    background-color: #0000FF;
    color: white;
    border: none;
    padding: 8px 12px;
    font-size: 0.9rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

.btn-submit:hover {
    background-color: #0000FF;
    transform: scale(1.05);
}

/* No Data Styling */
.no-data {
    text-align: center;
    font-size: 1rem;
    color: #555;
    padding: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        font-size: 0.8rem;
    }

    .btn-submit {
        font-size: 0.8rem;
        padding: 6px 10px;
    }

    .payment-method {
        font-size: 0.8rem;
    }
}

        .creative-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0000FF; /* Vibrant red color */
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 25px;
            border: 2px solid #0000FF; /* Border for definition */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
            transition: all 0.3s ease; /* Smooth transition effects */
            text-align: center;
        }

        .creative-button:hover {
            background-color: #0000FF; /* Darker red on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Stronger shadow on hover */
            transform: scale(1.05); /* Slightly larger for effect */
        }

        .creative-button:active {
            transform: scale(1); /* Reset scale on click */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle shadow on click */
        }
        nav {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        nav .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #f7f7f7;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
        }

        nav .logout {
            font-size: 1rem;
            color: #f7f7f7;
            text-decoration: none;
            background-color: #ff8c00;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        nav .logout:hover {
            background-color: #e67e00;
            transform: scale(1.05);
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #f7f7f7;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #ff8c00;
        }
        .footer {
            margin-top: 370px;
            text-align: center;
            font-size: 0.9rem;
            color: #f7f7f7;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            width: 100%;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.2);
        }

        .footer a {
            color: #ff8c00;
            text-decoration: none;
            margin: 0 5px;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav>
        <a href="home.php" class="logo" style="text-decoration: none; color: inherit;">
            <div>Ocean King</div>
        </a>
        <ul>
            <li><a href="newbill.php">New Bill</a></li>
            <li><a href="oldbill.php">On Credit</a></li>
        
        </ul>
        <a href="../logout.php" class="logout">Logout</a>
    </nav>

    <div class="table-container">
    <h2>On Credits</h2>
    <div style="overflow-x: auto;"> 
    <table>
        <thead>
            <tr>
                <th>Bill Number</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Date & Time</th>
                <th>Payment Method</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if query returns any rows
            if ($result && $result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <form method="POST" action="">
                            <td><?= htmlspecialchars($row['bill_no']) ?></td>
                            <td><?= htmlspecialchars($row['customer']) ?></td>
                            <td><?= number_format($row['total'], 2) ?> LKR</td>
                            <td><?= htmlspecialchars($row['date_time']) ?></td>
                            <td>
                                <select name="payment_method" required class="payment-method">
                                    <option value="">Select</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </td>
                            <td>
                                <button 
                                    type="submit" 
                                    name="bill_no" 
                                    value="<?= htmlspecialchars($row['bill_no']) ?>" 
                                    class="btn-submit">
                                    Submit
                                </button>
                            </td>
                        </form>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="no-data">No cheques found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>




    <footer class="footer">
        <p>&copy; 2024 Ocean King. All Rights Reserved.</p>
        <p>Contact us: <a href="mailto:info@oceanking.com">info@oceanking.com</a> | Phone: +123 456 7890</p>
        <p>
            Follow us:
            <a href="https://facebook.com/oceanking" target="_blank">Facebook</a> |
            <a href="https://instagram.com/oceanking" target="_blank">Instagram</a> |
            <a href="https://twitter.com/oceanking" target="_blank">Twitter</a>
        </p>
    </footer>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
