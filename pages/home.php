<?php
// Start session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'user') {
    echo "<script type='text/javascript'>
            alert('Access Denied: Users Only');
            window.location.href = '../index.php'; // Redirect to login page
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocean King - Home</title>    <link rel="icon" type="image/png" href="imgs\3.png">

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
            height: 140vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            color: white;
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

        .header {
            text-align: center;
            margin-top: 120px; /* Adjusted for navigation bar height */
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 600;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.6);
            color: #000000;
        }

        .header p {
            font-size: 1.2rem;
            font-weight: 400;
            margin-top: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            width: 100%;
            max-width: 600px;
            text-align: center;
            text-decoration-color: #000000;
        }

        .grid-container a {
            text-decoration: none;
        }

        .button {
            background-color: #ff8c00;
            color: black;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            height: 100%;
            width:100%;
        }

        .button:hover {
            background-color: #e67e00;
            transform: scale(1.05);
        }

        .footer {
            margin-top: auto;
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
        <div class="logo">Ocean King</div>
        <ul>
            <li><a href="newbill.php">New Bill</a></li>
            <li><a href="oldbill.php">On Credit</a></li>
            
        </ul>
        <a href="../logout.php" class="logout">Logout</a>
    </nav>
    <div class="header">
    <img src="../imgs/1.png" alt="Ocean King Products" style="height: 70%; width: 100%;">
    <p>Premium Dried Fish Food Products</p>
</div>

<div class="grid-container">
    <a href="newbill.php"><button class="button">New Bill</button></a>
    <a href="oldbill.php"><button class="button">On Credit Payments</button></a>
    <a href="register_customer_form.php"><button class= "button">Customer Profile</button></a>
</div>
    <div class="footer">
        <p>&copy; 2024 Ocean King. All Rights Reserved.</p>
        <p>Contact us: <a href="mailto:info@oceanking.com">info@oceanking.com</a> | Phone: +123 456 7890</p>
        <p>
            Follow us:
            <a href="https://facebook.com/oceanking" target="_blank">Facebook</a> |
            <a href="https://instagram.com/oceanking" target="_blank">Instagram</a> |
            <a href="https://twitter.com/oceanking" target="_blank">Twitter</a>
        </p>
    </div>
</body>
</html>




