<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "okdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if customer name is provided
if (isset($_GET['name'])) {
    $customer_name = $conn->real_escape_string($_GET['name']);

    // Fetch pending oldbill records for the selected customer, extracting only the date part
    $sql = "SELECT bill_no, DATE(date_time) AS bill_date, total FROM old_bill WHERE customer = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL preparation error: " . $conn->error); // Debugging the SQL error
    }

    $stmt->bind_param("s", $customer_name);

    if (!$stmt->execute()) {
        die("SQL execution error: " . $stmt->error); // Debugging execution error
    }

    $result = $stmt->get_result();


     // Fetch pending oldbill records for the selected customer, including overdue status
     $sql = "SELECT bill_no, DATE(date_time) AS bill_date, total, 
     CASE 
         WHEN DATEDIFF(CURRENT_DATE, DATE(date_time)) > 30 THEN 1 
         ELSE 0 
     END AS overdue
     FROM old_bill 
     WHERE customer = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);

if (!$stmt) {
 die("SQL preparation error: " . $conn->error);
}

$stmt->bind_param("s", $customer_name);

if (!$stmt->execute()) {
 die("SQL execution error: " . $stmt->error);
}

$result = $stmt->get_result();

    // Calculate the total amount
    $total_amount = 0;
    while ($row = $result->fetch_assoc()) {
        $total_amount += $row['total'];
    }

    // Reset the result pointer to the beginning
    $result->data_seek(0);
} else {
    echo "<script>alert('No customer selected.'); window.location.href = 'routes.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Bills for Customer</title>
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
            position: relative;
        }
        h1 {
            text-align: center;
            color: #dc3545;
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
            background-color: #dc3545;
            color: white;
        }
        .total {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 18px;
            color: #28a745;
            font-weight: bold;
        }
        .customer-name {
            color: #007bff; /* Set your desired color here */
            text-transform: uppercase;

            
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="total">Total Amount: <?php echo number_format($total_amount, 2); ?></div><br>
        <h1>Pending Bills for Customer: <span class="customer-name"><?php echo htmlspecialchars($customer_name); ?></span></h1>
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['bill_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['bill_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['total']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending bills found for this customer.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
