<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Buttons</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        h1, h2 {
            color: #343a40;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-block {
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .table th, .table td {
            text-align: center;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .mt-4 {
            margin-top: 40px;
        }

        /* For the form buttons */
        .row {
            justify-content: center;
        }

        .col-md-3 {
            margin-bottom: 20px;
        }

        /* Add spacing for better mobile view */
        @media (max-width: 767px) {
            .col-md-3 {
                max-width: 45%;
            }
        }

        /* Style for Customer Details (Customer name, Total, Date, Route) */
        .customer-details {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .customer-details h3,
        .customer-details p {
            margin-bottom: 15px;
            text-align: center;
        }

        .customer-details .info-title {
            font-weight: bold;
            color: #007bff;
        }

        .customer-details .info-value {
            font-size: 1.2em;
            color: #343a40;
        }

        /* Adjustments for Action Button in Table */
        .table .btn {
            padding: 8px 12px;
            font-size: 14px;
            text-transform: uppercase;
        }

       
    </style>
</head>
<du>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Page with Eight Buttons</h1>        
        <div class="row">
            <?php
                $buttons = ["jaffna", "puththalam", "kandy", "sabaragamu", "colombo", "local sales", "fish meal", "negombo"];
                foreach ($buttons as $button) {
                    echo "<div class='col-md-3 col-sm-6 mb-3'>";
                    echo "<form method='POST' action=''>";
                    echo "<button type='submit' name='route' value='$button' class='btn btn-info btn-block'>$button</button>";
                    echo "</form>";
                    echo "</div>";
                }
            ?>
        </div>

        <div class="mt-4">
    <h2 class="text-center">Data for Selected Route</h2>
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">bill_no</th>
                    <th onclick="sortTable(1)">customer_name</th>
                    <th onclick="sortTable(2)">Route</th>
                    <th onclick="sortTable(3)">total</th>
                    <th onclick="sortTable(4)">tracker_1</th>
                    <th onclick="sortTable(5)">tracker_2</th>
                    <th onclick="sortTable(6)">tracker_3</th>
                    <th onclick="sortDateTime()">date_time</th>
                    <th onclick="sortTable(8)">emp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['route'])) {
                        $route = $_POST['route'];
                        // Database connection
                        $conn = new mysqli('localhost', 'root', '', 'okdb');
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $stmt = $conn->prepare("SELECT * FROM full_bill WHERE route = ?");
                        $stmt->bind_param('s', $route);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['bill_no'] . "</td>";
                            echo "<td>" . $row['customer_name'] . "</td>";
                            echo "<td>" . $row['route'] . "</td>";
                            echo "<td>" . $row['total'] . "</td>";
                            echo "<td>" . $row['tracker_1'] . "</td>";
                            echo "<td>" . $row['tracker_2'] . "</td>";
                            echo "<td>" . $row['tracker_3'] . "</td>";
                            echo "<td>" . $row['date_time'] . "</td>";
                            echo "<td>" . $row['emp'] . "</td>";
                            echo "<td><form method='POST' action=''><button type='submit' name='bill_no' value='" . $row['bill_no'] . "' class='btn btn-primary'>View Details</button></form></td>";
                            echo "</tr>";
                        }
                        $stmt->close();
                        $conn->close();
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    let sortDirection = {
        customer_name: 'asc',
        date_time: 'asc',
        // Add other columns as needed
    };

    function sortTable(columnIndex) {
        const table = document.getElementById("dataTable");
        const rows = Array.from(table.rows).slice(1); // Exclude the header row
        const header = table.getElementsByTagName("TH")[columnIndex].innerText.trim();

        let direction = sortDirection[header] || 'asc'; // Get the current sort direction for this column
        sortDirection[header] = direction === 'asc' ? 'desc' : 'asc'; // Toggle the direction

        rows.sort(function(a, b) {
            const x = a.cells[columnIndex].innerText.trim();
            const y = b.cells[columnIndex].innerText.trim();

            if (direction === "asc") {
                return (x > y) ? 1 : (x < y) ? -1 : 0;
            } else {
                return (x < y) ? 1 : (x > y) ? -1 : 0;
            }
        });

        // Append the sorted rows back to the table body
        rows.forEach(row => table.appendChild(row));
    }

    function sortDateTime() {
        const table = document.getElementById("dataTable");
        const rows = Array.from(table.rows).slice(1); // Exclude the header row
        const header = "date_time";

        let direction = sortDirection[header] || 'asc'; // Get the current sort direction for this column
        sortDirection[header] = direction === 'asc' ? 'desc' : 'asc'; // Toggle the direction

        rows.sort(function(a, b) {
            const x = new Date(a.cells[7].innerText.trim());
            const y = new Date(b.cells[7].innerText.trim());

            if (direction === "asc") {
                return x - y;
            } else {
                return y - x;
            }
        });

        // Append the sorted rows back to the table body
        rows.forEach(row => table.appendChild(row));
    }
</script>


        <div class="mt-4">
        <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_no'])) {
        $bill_no = $_POST['bill_no'];
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'okdb');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT customer, total, date, route FROM new_bill WHERE bill_no = ?");
        
        // Check if prepare() failed
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
    
        // Bind the parameters
        $stmt->bind_param('s', $bill_no);  // Assuming bill_no is a string, adjust if necessary.
    
        // Execute the query
        $stmt->execute();
    
        // Bind the results
        $stmt->bind_result($customer_name, $total, $date, $route);
    
        // Fetch the results
        if ($stmt->fetch()) {
            // Format the date to show only the date part (YYYY-MM-DD)
            $formatted_date = date("Y-m-d", strtotime($date));
            
            echo "<div class='customer-details'>";
            echo "<h3>Bill No: $bill_no</h3>";
            echo "<p><span class='info-title'>Customer Name:</span> <span class='info-value'>$customer_name</span></p>";
            echo "<p><span class='info-title'>Total:</span> <span class='info-value'>$total</span></p>";
            echo "<p><span class='info-title'>Date:</span> <span class='info-value'>$formatted_date</span></p>";
            echo "<p><span class='info-title'>Route:</span> <span class='info-value'>$route</span></p>";
            echo "</div>";
        } else {
            echo "<p class='text-center'>No data found for Bill No: $bill_no</p>";
        }
    
        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
    
?>

<div class="text-center mt-3">
        <button onclick="printBillDetails()" class="btn btn-success">Print Bill Details</button>
    </div>
           
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bill_no'])) {
                                $bill_no = $_POST['bill_no'];
                                // Database connection
                                $conn = new mysqli('localhost', 'root', '', 'okdb');
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
                                $stmt = $conn->prepare("SELECT * FROM new_bill WHERE bill_no = ?");
                                $stmt->bind_param('s', $bill_no);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['item'] . "</td>";
                                    echo "<td>" . $row['rate'] . "</td>";
                                    echo "<td>" . $row['quantity'] . "</td>";
                                    echo "<td>" . $row['amount'] . "</td>";
                                    echo "</tr>";
                                }
                                $stmt->close();
                                $conn->close();
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function printBillDetails() {
        const billDetails = document.getElementById('bill-details').outerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Bill Details</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        padding: 20px;
                    }
                    .customer-details {
                        background-color: #e9ecef;
                        border-radius: 8px;
                        padding: 20px;
                        margin-bottom: 20px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    }
                    .customer-details h3 {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .info-title {
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                ${billDetails}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
