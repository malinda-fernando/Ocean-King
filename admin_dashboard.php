<?php
// Start session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script type='text/javascript'>
            alert('Access Denied: Admins Only');
            window.location.href = 'index.php'; // Redirect to login page
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>    <link rel="icon" type="image/png" href="imgs\3.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            
        }
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            margin-top: 20px;
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 120px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
        }
        .card h2 {
            font-size: 18px;
            color: #34495e;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 14px;
            color: #7f8c8d;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background: #3498db;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .card a:hover {
            background: #2980b9;
        }
        .navbar {
            width: 100%;
            background-color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar h1 {
            color: #ecf0f1;
            font-size: 20px;
        }
        .navbar ul {
            display: flex;
            list-style: none;
            gap: 15px;
        }
        .navbar ul li {
            position: relative;
        }
        .navbar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
            background: transparent;
            border-radius: 5px;
            transition: 0.3s;
        }
        .navbar ul li a:hover {
            background-color: #3498db;
        }
        .navbar ul li .dropdown {
            position: absolute;
            top: 45px;
            left: 0;
            background-color: #34495e;
            border-radius: 5px;
            display: none;
            flex-direction: column;
            padding: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar ul li:hover .dropdown {
            display: flex;
        }
        .navbar ul li .dropdown a {
            padding: 8px 15px;
            white-space: nowrap;
        }
        .logout-btn {
            background: #e74c3c;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
    
        <h1>Admin Dashboard</h1>
        <ul>
            <li>
                <a href="#">Manage <span>&#9662;</span></a>
                <div class="dropdown">
                    <a href="manage_user.php">Users new</a>                
                </div>
            </li>
            <li><a href="full_bill.php">Full Bill</a></li>
            <li><a href="pages\cheque.php">All Cheques</a></li>
            <li><a href="oldbillpayments.php">On Credit</a></li>
            <li><a style="color: #e74c3c;" class="logout-btn" href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="container" style="margin-bottom:20px;">
        <!-- Card 1: User Management -->
        <div class="card">
            <h2>Manage Users</h2>
            <p>View, edit, and delete registered users.</p>
            <a href="manage_user.php">Go to User Management</a>
        </div>

        <!-- Card 2: System Logs -->
        <div class="card">
            <h2>Full Bill</h2>
            <p>Check Sales Bill Activities.</p>
            <a href="full_bill.php">Go to Bill Details.</a>
        </div>

        <!-- Card 3: Settings -->
        <div class="card">
            <h2>All Cheque</h2>
            <p>Check And Edit All Cheque Details.</p>
            <a href="pages\cheque.php">Go to Cheque details</a>
        </div>
        <div class="card1" style="background-color: transparent;">
           
           </div>
         <!-- Card 3: Settings -->
         <div class="card">
            <h2>Old Bill Payments</h2>
            <p>Check Old Credit Details.</p>
            <a href="oldbillpayments.php">Go to Old Credit Details</a>
        </div>
        <div class="card1" style="background-color: transparent;">
            
            </div>
         <!-- Card 3: Settings -->
         <div class="card">
            <h2>Register </h2>
            <p>Make User Accounts</p>
            <a href="signup.php">Go To Register</a>
        </div> 
     
        <div class="card">
            <h2>Customer Details </h2>
            <p>Edit Customers</p>
            <a href="pages/customer_details.php">Go To Edit</a>
        </div>
       
        <div class="card">
            <h2>Add Routes </h2>
            <p>Add New Routes</p>
            <a href="pages/route_register.php">Go To Add</a>
        </div>
       
    </div>
</body>
</html>
