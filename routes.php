<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "okdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch routes
$sql = "SELECT route_name FROM routes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select a Route</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color:rgb(15, 69, 122);
            font-family: Arial, Helvetica, sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 150px;
            background-color: transparent;
        }
        .card {
            padding: 20px;
            background-color:rgba(255, 255, 255, 0.93);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-custom {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight:600;
            color: black;
        }
        .btn-custom:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Select a Route</h1>
            <form action="view_shops.php" method="GET">
                <?php if ($result->num_rows > 0): ?>
                    <div class="row">
                        <?php $counter = 0; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php if ($counter % 4 == 0 && $counter != 0): ?>
                                </div><div class="row"> <!-- Start a new row after 4 buttons -->
                            <?php endif; ?>
                            <div class="col-md-3 mb-3">
                                <button type="submit" name="route" value="<?php echo htmlspecialchars($row['route_name']); ?>" class="btn btn-outline-primary btn-custom">
                                    <?php echo htmlspecialchars($row['route_name']); ?>
                                </button>
                            </div>
                            <?php $counter++; ?>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">No routes found. Please contact the administrator.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
