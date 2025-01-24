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

// Check if 'id' is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch the user's current data
    $sql = "SELECT id, username, email, password FROM users WHERE id = ?";
   $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "Invalid user ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/kNVw+8m+K4F3FGdP+j77zp4C1D+3ap1hsbM8FUV5zwVlHhP0qqQTr9VZK/VBuazbbrUvBkgW/WG3A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="new password"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="new password"]:focus{
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            font-size: 14px;
            color: #007bff;
            text-align: center;
            display: block;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .form-container i {
            margin-right: 8px;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000; /* On top of other content */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Black with transparency */
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content h2 {
            font-size: 1.5rem;
            color: #333;
        }

        .modal-content button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
        }

        .modal-content button:hover {
            background-color: #45a049;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>

<script>
        // Function to show the modal
        function showModal(message) {
    const modal = document.getElementById('successModal');
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.textContent = message; // Set the message in the modal
    modal.style.display = 'block'; // Show the modal
}

// Function to close the modal and redirect
function closeModal() {
    const modal = document.getElementById('successModal');
    modal.style.display = 'none'; // Hide the modal
    window.location.href = 'manage_user.php'; // Redirect to manage_user.php
}
    </script>
</head>
<body>

<?php
    // Check if a success message is present in the URL
    if (isset($_GET['status']) && $_GET['status'] === 'success') {
        echo "<script>document.addEventListener('DOMContentLoaded', () => showModal('User updated successfully!'));</script>";
    }
    ?>

    <!-- Modal structure -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <h2 id="modalMessage"></h2>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>
    <div class="form-container">
    <h1><i class="fas fa-user-edit"></i> Edit User</h1>
    <form action="update_user.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

    <label for="current_password">Current Password:</label>
<input type="password" id="current_password" name="current_password" required>


    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" >

    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password">

    <button type="submit"><i class="fas fa-save"></i> Save Changes</button>
</form>
<a href="manage_user.php"><i class="fas fa-arrow-left"></i> Back to manage Users</a>
</div>
</body>
</html>

