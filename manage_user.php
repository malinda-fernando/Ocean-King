<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "okdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch registered user details
$sql = "SELECT id, username, email FROM users WHERE user_type='user'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Table</title>    <link rel="icon" type="image/png" href="imgs\3.png">

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

        h1 {
            text-align: center;
            color: #444;
        }

        /* Table Container */
        .table-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 16px;
            text-align: left;
        }

        table thead {
            background-color: #007bff;
            color: white;
        }

        table thead th {
            padding: 12px 15px;
            letter-spacing: 0.03em;
        }

        table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody td {
            padding: 10px 15px;
            font-weight: 500;
        }

        /* Button Styling */
        .btn {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .edit-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            table thead {
                display: none;
            }

            table tbody tr {
                margin-bottom: 15px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 8px;
            }

            table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
            }

            table tbody td::before {
                content: attr(data-label);
                font-weight: bold;
                text-transform: uppercase;
                margin-right: 10px;
            }

            .btn {
                margin-top: 10px;
            }
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
            <li><a href="admin_dashboard.php">Home</a></li>
            <li>
                <a href="#">Manage <span>&#9662;</span></a>
                <div class="dropdown">
                    <a href="manage_user.php">Users</a>                
                </div>
            </li>
            <li><a href="full_bill.php">Full Bill</a></li>
            <li><a href="pages\cheque.php">All Cheques</a></li>
            <li><a href="oldbillpayments.php">On Credit</a></li>
            <li><a style="color: #e74c3c;" class="logout-btn" href="logout.php">Logout</a></li>
        </ul>
    </div>
    <h1 style="color:#ecf0f1;margin-top: 120px;" >Registered Users</h1>
    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Username"><?php echo $row['username']; ?></td>
                            <td data-label="Email"><?php echo $row['email']; ?></td>
                            <td data-label="Actions">
                                <!-- Edit button -->
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn edit-btn">Edit</a>

                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn delete-btn" 
                                onclick="return confirm('Are you sure you want to delete this user?');">
                                 Delete
                                    </a>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
