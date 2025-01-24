<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>ALL Credit List</title>    
    <link rel="icon" type="image/png" href="imgs\3.png">

    <style>
        /* Existing styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #333;
        }

        h1 {
            text-align: center;
            color: #ecf0f1;
            font-size: 2.5rem;
            text-transform: uppercase;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
        }

        .search-container form {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 50%;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            border-radius: 8px;
        }

        .routes-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
        }

        .route-button {
            background-color: #3498db;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .route-button:hover {
            background-color: #2c81ba;
            transform: scale(1.05);
        }
    </style>
</head>
<body>    
 <!-- Navbar -->
 <div class="navbar">
        <h1>Admin Dashboard</h1>
        <ul>
            <li><a href="admin_dashboard.php">Home</a></li>
            <li>
                <a href="#">Manage <span>&#9662;</span></a>
                <div class="dropdown">
                    <a href="manage_user.php">Users</a>                
                </div>
            </li>
            <li><a href="full_bill.php">Full Bill</a></li>
            <li><a href="pages\cheque.php">All Cheques</a></li>
            <li><a style="margin-right: 3vw;color: #e74c3c;" class="logout-btn" href="logout.php">Logout</a></li>
        </ul>
    </div>    

<h1 style="color:#ecf0f1;">All Credit List</h1>

<!-- Centered and User-Friendly Search Bar -->
<div class="search-container">
    <form action="oldbillpayments.php" method="GET">
        <input 
            type="text" 
            id="search" 
            name="search_query" 
            placeholder="Enter customer name here..." 
            value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>" 
            required 
        />
        <button type="submit">Search</button>
    </form>
</div>

<!-- Route Buttons -->
<div class="routes-container">
    <a href="route1.php" class="route-button">Route 1</a>
    <a href="route2.php" class="route-button">Route 2</a>
    <a href="route3.php" class="route-button">Route 3</a>
    <a href="route4.php" class="route-button">Route 4</a>
    <a href="route5.php" class="route-button">Route 5</a>
    <a href="route6.php" class="route-button">Route 6</a>
    <a href="route7.php" class="route-button">Route 7</a>
    <a href="route8.php" class="route-button">Route 8</a>
</div>

<!-- Table to Display Results -->
<table>
    <thead>
        <tr>
            <th>Bill No</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Payment Method</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['bill_no']}</td>
                        <td>{$row['customer']}</td>
                        <td>{$row['total']}</td>
                        <td>{$row['date_time']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['payment_method']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-records'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
