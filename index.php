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
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type']; 

            // Redirect based on user type
            if ($user['user_type'] === 'admin') {
                echo "<script type='text/javascript'>
                        alert('Welcome Admin!');
                        window.location.href = 'admin_dashboard.php';
                      </script>";
            } else {
                echo "<script type='text/javascript'>
                        alert('Login Successful');
                        window.location.href = 'pages/home.php';
                      </script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Incorrect Password');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Username not registered');</script>";
    }
    $stmt->close();
}


// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="icon" type="image/png" href="imgs\3.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            overflow: hidden;
        }

        .glass-container {
            width: 350px;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            text-align: center;
            color: #fff;
        }

        .glass-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .glass-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 14px;
            transition: all 0.3s;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }

        .options {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #fff;
        }

        .options a {
            color: #4ca1af;
            text-decoration: none;
            transition: color 0.3s;
        }

        .options a:hover {
            color: #fff;
        }

        button {
            padding: 10px;
            background: #4ca1af;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background: #fff;
            color: #4ca1af;
        }

        p {
            font-size: 13px;
            margin-top: 15px;
        }

        p a {
            color: #4ca1af;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        p a:hover {
            color: #fff;
        }

        .background-effect {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 60%);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="background-effect"></div>
    <div class="glass-container">
        <h2>Welcome Back</h2>
        <form action="#" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="options">
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
