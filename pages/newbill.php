<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}
// Welcome message using the session username
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Sales System Form</title>    
    <link rel="icon" type="image/png" href="../imgs/3.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            background-size: cover;
            color: #333;
           
        }

        .container {
            max-width: 810px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

       

        form div, table {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, button {
            width: 98%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        h1 {
            text-align: center;
            color: #007bff; /* Professional blue */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px; /* Rounded corners */
            overflow: hidden; /* Ensures corners stay rounded */
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #007bff; /* Blue header */
            color: white;
            font-weight: bold;
        }
        tr {
            border-bottom: 1px solid #ddd; /* Subtle row separator */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light gray for alternating rows */
        }
        tr:hover {
            background-color: #f1f5ff; /* Subtle hover effect */
        }
        td input[type="text"], 
        td input[type="number"] {
            width: 95%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box; /* Ensures padding doesn't affect width */
        }
        td button {
            background-color: #dc3545; /* Red button for "Remove" */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        td button:hover {
            background-color: #c82333; /* Darker red for hover */
        }
        .add-row-btn {
            width: auto;
            display: block;
            margin: 0 auto;
            background-color: #28a745;
        }

        @media (max-width: 600px) {
            input, select, button {
                font-size: 14px;
            }

            table {
                font-size: 14px;
            }
        }
        .bill-info {
        background-color: #f8f9fa;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
        font-size: 16px;
        color: #333;
        width: fit-content;
        margin: 10px auto;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 95px;
    }

    .bill-info strong {
        color: #007bff;
    }

    .bill-info span {
        font-weight: bold;
        color: #28a745;
    }
 


    .submit-btn {
        width: auto; /* Adjust to fit the text */
        padding: 8px 20px; /* Reduce padding for a smaller size */
        font-size: 14px; /* Smaller font size */
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        display: block; /* Ensures the button behaves like a block element */
         margin: 0 auto; /* Centers the block element horizontally */
    }

    .submit-btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
        transform: scale(1.05); /* Slight enlargement on hover for feedback */
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
            margin-right: 40px;
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
       /* Adjust table styles for smaller screens */
@media (max-width: 600px) {
    table {
        font-size: 12px; /* Smaller font size */
    }

    th, td {
        padding: 8px; /* Reduced padding */
    }

    table thead {
        display: none; /* Hide table header */
    }

    table tr {
        display: flex; /* Turn rows into cards */
        flex-direction: column;
        border: 1px solid #ddd; /* Add border for cards */
        margin-bottom: 10px;
        padding: 10px; /* Add padding inside cards */
        border-radius: 8px;
    }

    table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: none; /* Remove inner borders */
    }

    table td:before {
        content: attr(data-label); /* Use `data-label` attribute for column headers */
        flex: 1;
        font-weight: bold;
        color: #333;
    }

    button {
        font-size: 12px; /* Smaller buttons */
    }
}
</style>



    </style>
    <script>
        // Function to calculate the amount based on rate and quantity
        function calculateAmount(row) {
            const rate = parseFloat(row.querySelector('input[name="rate[]"]').value) || 0;
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const amountField = row.querySelector('input[name="amount[]"]');
            amountField.value = (rate * quantity).toFixed(2); // Update the amount field
        }

        function addRow() {
            const table = document.getElementById('item-table-body');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="item_details[]" required></td>
                <td><input type="number" step="0.01" name="rate[]" oninput="calculateAmount(this.parentElement.parentElement)" required></td>
                <td><input type="number" name="quantity[]" oninput="calculateAmount(this.parentElement.parentElement)" required></td>
                <td><input type="number" step="0.01" name="amount[]" readonly></td>
                <td><button type="button" onclick="removeRow(this)">Remove</button></td>
            `;
            table.appendChild(row);
        }

        function removeRow(button) {
            const row = button.parentElement.parentElement;
            row.remove();
        }
        function validateForm() {
    const rates = document.querySelectorAll('input[name="rate[]"]');
    const quantities = document.querySelectorAll('input[name="quantity[]"]');

    for (let i = 0; i < rates.length; i++) {
        if (rates[i].value <= 0 || quantities[i].value <= 0) {
            alert('Rate and Quantity must be greater than zero');
            return false; // Stop submission
        }
    }
    return true; // Proceed
}
    </script>


</head>
<body>
<nav>
<a href="home.php" class="logo" style="text-decoration: none; color: white;">
    <div>Ocean King</div>
</a>        <ul>
            <li><a href="newbill.php">New Bill</a></li>
            <li><a href="oldbill.php">On Credit</a></li>
            
        </ul>   
         <h1>Welcome, <?php echo htmlspecialchars(string: $username); ?>!</h1>

        <a href="../logout.php" class="logout">Logout</a>
    </nav>
    <?php
    // Fetch most recent bill number
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "okdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Default value for latest bill number
    $latest_bill_no = "No bills found";
    
    // Fetch the most recent bill based on created_at field
    $sql = "SELECT bill_no FROM new_bill ORDER BY date DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $latest_bill_no = $row['bill_no']; // Get the most recent bill number
    }
    
    // Close the database connection
    $conn->close();
    ?>

    
    <p class="bill-info">
    <strong>Most Recent Bill Number:</strong> 
    <span><?php echo htmlspecialchars($latest_bill_no); ?></span>
</p>
    
<form action="submit_bill.php" method="POST" onsubmit="return validateForm()">
    <div class="container">
        <h1>New Sales Bill</h1>
        <form action="submit_bill.php" method="POST">
        <div>
    <label for="bill_number">Bill Number:</label>
    <input 
        type="number" 
        id="bill_number" 
        name="bill_number" 
        placeholder="Enter Bill Number" 
        required 
        oninput="checkBillNumber()"
    >
    <span id="bill-status" style="color: red; font-weight: bold;"></span> <!-- Display status -->
</div>

<script>
function checkBillNumber() {
    const billNumber = document.getElementById('bill_number').value;
    const statusSpan = document.getElementById('bill-status');

    if (billNumber.trim() === "") {
        statusSpan.innerHTML = ""; // Reset when input is empty
        return;
    }

    // Use AJAX to send the bill number to the server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "check_bill_number.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText === "exists") {
                statusSpan.innerHTML = "Bill Number already exists!";
                statusSpan.style.color = "red";
            } else {
                statusSpan.innerHTML = "Bill Number is available.";
                statusSpan.style.color = "green";
            }
        } else {
            console.error("Error checking bill number:", xhr.status);
        }
    };

    xhr.send("bill_number=" + encodeURIComponent(billNumber));
}
</script>

<div style="position: relative;">
    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" placeholder="Enter Customer Name" required>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const customerNameInput = document.getElementById('customer_name');
        const suggestionBox = document.createElement('div');
        suggestionBox.style.position = 'absolute';
        suggestionBox.style.backgroundColor = '#fff';
        suggestionBox.style.border = '1px solid #ccc';
        suggestionBox.style.zIndex = '1000';
        suggestionBox.style.maxHeight = '200px';
        suggestionBox.style.overflowY = 'auto';

        customerNameInput.parentElement.appendChild(suggestionBox);

        customerNameInput.addEventListener('input', function () {
            const query = customerNameInput.value.trim();

            if (query.length < 2) {
                suggestionBox.innerHTML = '';
                return;
            }

            // Fetch suggestions
            fetch(`suggest_names.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(suggestions => {
                    suggestionBox.innerHTML = ''; // Clear previous suggestions

                    if (suggestions.length === 0) {
                        suggestionBox.innerHTML = '<div style="padding: 8px; color: #999;">No matches found</div>';
                        return;
                    }

                    suggestions.forEach(name => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.style.padding = '8px';
                        suggestionItem.style.cursor = 'pointer';
                        suggestionItem.style.borderBottom = '1px solid #eee';
                        suggestionItem.textContent = name;

                        suggestionItem.addEventListener('click', () => {
                            customerNameInput.value = name;
                            suggestionBox.innerHTML = ''; // Clear suggestions
                        });

                        suggestionBox.appendChild(suggestionItem);
                    });
                })
                .catch(error => console.error('Error fetching suggestions:', error));
        });

        document.addEventListener('click', function (event) {
            if (!customerNameInput.contains(event.target) && !suggestionBox.contains(event.target)) {
                suggestionBox.innerHTML = ''; // Hide suggestions when clicking outside
            }
        });
    });
</script>
            <label for="route">Route:</label>
                <select id="route" name="route" required>
                <option value="" disabled selected>Select a route</option>
                <option value="jaffna">jaffna</option>
                <option value="puththalam">puththalam</option>
                <option value="colombo">colombo</option>
                <option value="kandy">kandy</option>
                <option value="sabaragamu">sabaragamu</option>
                <option value="local">local sales</option>
                <option value="meal">fish meal</option>
            </select>

            <div class="responsive-table">
    <table>
        <thead>
            <tr>
                <th>Item Details</th>
                <th>Rate</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="item-table-body">
            <tr>
                <td data-label="Item Details"><input type="text" name="item_details[]" required></td>
                <td data-label="Rate"><input type="number" step="0.01" name="rate[]" oninput="calculateAmount(this.parentElement.parentElement)" required></td>
                <td data-label="Quantity"><input type="number" name="quantity[]" oninput="calculateAmount(this.parentElement.parentElement)" required></td>
                <td data-label="Amount"><input type="number" step="0.01" name="amount[]" readonly></td>
                <td data-label="Action"><button type="button" onclick="removeRow(this)">Remove</button></td>
            </tr>
        </tbody>
    </table>
</div>

        <button type="button" class="add-row-btn" onclick="addRow()">Add Item</button>
        <!-- Add a Total Display -->
<div>
    <label for="total_amount">Total Amount:</label>
    <input type="text" id="total_amount" name="total_amount" readonly>
</div>

<script>
// Function to calculate the total of the 'amount' column
function calculateTotal() {
    const rows = document.querySelectorAll('#item-table-body tr');
    let total = 0;

    rows.forEach(row => {
        const amountField = row.querySelector('input[name="amount[]"]');
        const amount = parseFloat(amountField.value) || 0;
        total += amount;
    });

    // Update the total amount field
    document.getElementById('total_amount').value = total.toFixed(2);
}

// Call calculateTotal when a row's amount is updated
function calculateAmount(row) {
    const rate = parseFloat(row.querySelector('input[name="rate[]"]').value) || 0;
    const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
    const amountField = row.querySelector('input[name="amount[]"]');

    amountField.value = (rate * quantity).toFixed(2); // Update individual amount
    calculateTotal(); // Recalculate the total
}

function addRow() {
    const table = document.getElementById('item-table-body');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="item_details[]" required></td>
        <td><input type="number" step="0.01" name="rate[]" oninput="calculateAmount(this.parentElement.parentElement)" required></td>
        <td><input type="number" name="quantity[]" oninput="calculateAmount(this.parentElement.parentElement)" required></td>
        <td><input type="number" step="0.01" name="amount[]" readonly></td>
        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
    `;
    table.appendChild(row);
    calculateTotal();
}

function removeRow(button) {
    const row = button.parentElement.parentElement;
    row.remove();
    calculateTotal();
}
</script>
                <label for="payment_method">Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="" disabled selected>Select a Payment Method</option>
                    <option value="Cash">Cash</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Credit">Credit</option>

                </select>
            </div>
            
            <button type="submit" class="submit-btn">Submit</button>
    </form>
</body>
</html>
