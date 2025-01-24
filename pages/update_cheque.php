<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root"; // Replace with your database username
    $password = "";     // Replace with your database password
    $dbname = "okdb";   // Replace with your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection failed"]));
    }

    // Get data from AJAX request
    $bill_no = $_POST['bill_no'] ?? '';
    $chq_no = $_POST['chq_no'] ?? '';
    $bank = $_POST['bank'] ?? '';

    // Validate inputs
    if (empty($bill_no) || empty($chq_no) || empty($bank)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit();
    }

    // Update query for 'cheques' table
    $sql_cheques = "UPDATE cheques 
                    SET cheq_no = ?, bank = ?, status = 'Paid', dtdate = NOW() 
                    WHERE bill_no = ?";
    $stmt_cheques = $conn->prepare($sql_cheques);
    $stmt_cheques->bind_param("ssi", $chq_no, $bank, $bill_no);

    if ($stmt_cheques->execute()) {
        // Update query for 'full_bill' table
        $sql_full_bill = "UPDATE full_bill 
                          SET tracker_3 = 'Received' 
                          WHERE bill_no = ?";
        $stmt_full_bill = $conn->prepare($sql_full_bill);
        $stmt_full_bill->bind_param("i", $bill_no);

        if ($stmt_full_bill->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt_full_bill->error]);
        }

        $stmt_full_bill->close();
    } else {
        echo json_encode(["status" => "error", "message" => $stmt_cheques->error]);
    }

    $stmt_cheques->close();
    $conn->close();
}
?>