<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script type='text/javascript'>
            alert('Access Denied: Admins Only');
            window.location.href = '../index.php'; // Redirect to login page
          </script>";
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "okdb";   

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch rows where 'status' is 'Pending'
$sql = "SELECT bill_no, customer, date_time, total, cheq_no, bank FROM cheques WHERE status = 'Pending'";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Cheques</title>    <link rel="icon" type="image/png" href="..\imgs\3.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    .table-container {
        width: 100%;
        max-width: 1200px;
        background: rgba(38, 36, 175, 0.47);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin-bottom: 30px;
        overflow-x: auto;
        margin-top: 10vh;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 16px;
        color: #333;
        text-align: left;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        overflow: hidden;
    }

    thead {
        background-color:#007bff;
        color: white;
    }

    th, td {
        padding: 15px;
        border: none;
    }
    td{
        font-weight: 600;

    }
    th {
        font-weight: bold;
    }

    tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    tbody tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    tbody tr:hover {
        background-color: rgba(0, 128, 255, 0.1);
        transition: background-color 0.3s ease;
    }

    .styled-table input {
        width: calc(100% - 20px);
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

        .payment-method-header {
            text-align: center;
        }
        .sub-header {
            background-color: #f9f9f9;
        }

        .table-heading{
            margin-left: 40px;
        }

        .creative-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #007BFF; /* Soft blue for modern look */
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 20px; /* Rounded for a friendlier appearance */
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.creative-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: translateY(-2px); /* Lift the button for a dynamic effect */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

.creative-button:active {
    background-color: #003f8a; /* Even darker blue for click feedback */
    transform: translateY(0); /* Neutralize lift on click */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}


.creative-button:disabled {
    background-color: #cccccc; /* Gray out when disabled */
    color: #666666;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}
.navbar {
            width: 100%;
            height:7vh;
            background-color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: sticky;
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
                    <a href="manage_user.php">Users</a>                
                </div>
            </li>
            <li><a href="../admin_dashboard.php">Home</a></li>

            <li><a href="../full_bill.php">Full Bill</a></li>
            <li><a href="../oldbillpayments.php">On Credit</a></li>
            <li><a style="color: #e74c3c;" class="logout-btn" href="../logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="table-container">
        <h2 style="color:#ecf0f1;">Cheque Details</h2><br>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Bill Number</th>
                    <th>Customer Name</th>
                    <th>Receive Date</th>
                    <th>Total Amount</th>
                    <th>Cheque No</th>
                    <th>Bank</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['bill_no']) ?></td>
                            <td><?= htmlspecialchars($row['customer']) ?></td>
                            <td style="width:20%;"><?= htmlspecialchars($row['date_time']) ?></td>
                            <td>Rs. <?= htmlspecialchars($row['total']) ?></td>
                            <td><input type="text" class="chq-no" value="<?= htmlspecialchars($row['cheq_no']) ?>"></td>
                            <td><input type="text" class="bank" value="<?= htmlspecialchars($row['bank']) ?>"></td>
                            <td><button class="creative-button" data-id="<?= $row['bill_no'] ?>">Save</button></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="7">No cheques found</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $(".creative-button").on("click", function () {
                const btn = $(this);
                const bill_no = btn.data("id");
                const chq_no = btn.closest("tr").find(".chq-no").val();
                const bank = btn.closest("tr").find(".bank").val();

                btn.prop("disabled", true).text("Saving...");
                
                $.post("update_cheque.php", { bill_no, chq_no, bank }, function (response) {
                    btn.prop("disabled", false).text("Save");
                    const data = JSON.parse(response);
                    if (data.status === "success") {
                        alert("Cheque updated successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                }).fail(function () {
                    btn.prop("disabled", false).text("Save");
                    alert("An error occurred. Please try again.");
                });
            });
        });
    </script>
</body>
</html>