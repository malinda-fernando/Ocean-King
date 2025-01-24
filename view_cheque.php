<?php
include 'okdb'; // Include the database connection file

$sql = "SELECT bill_no, customer, date_time, total, cheq_no, bank FROM cheques";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheque Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->

    <style>
        /* Add styles for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f5ff;
        }
        .save-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: #218838;
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
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Saved Cheque Details</h2>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Bill Number</th>
                    <th>Customer Name</th>
                    <th>Receive Date</th>
                    <th>Total Amount</th>
                    <th>Chq No</th>
                    <th>Bank</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['bill_no']) ?></td>
                            <td><?= htmlspecialchars($row['customer']) ?></td>
                            <td><?= htmlspecialchars($row['date_time']) ?></td>
                            <td><?= htmlspecialchars($row['total']) ?></td>
                            <td><?= htmlspecialchars($row['cheq_no']) ?></td>
                            <td><?= htmlspecialchars($row['bank']) ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="6">No saved cheque details found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
