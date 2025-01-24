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
<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "okdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Check if email or username already exists
        $check_sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email or Username already exists. Please try again with different credentials.');</script>";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $insert_sql = "INSERT INTO users (username, email, password) 
                           VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($insert_stmt->execute()) {
                echo "<script>
                        alert('User Registered Successfully.');
                        window.location.href = 'index.php'; // Redirect to login page
                      </script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>    
    <link rel="icon" type="image/png" href="imgs/3.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
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

        .glass-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            width: 350px;
            padding: 30px;
            color: #fff;
            text-align: center;
            margin-top: 100px;
        }

        .glass-container h2 {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 12px 15px;
            margin: 10px 0;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 14px;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        button {
            margin-top: 15px;
            padding: 12px 15px;
            border: none;
            border-radius: 10px;
            background: #6a11cb;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #2575fc;
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        #register {
            color: #fff;
            text-decoration: underline;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        #register:hover {
            color: #ffcc00;
        }
    </style>
</head>
<body>
    <nav class="navbar">
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
    </nav>

    <div class="glass-container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
