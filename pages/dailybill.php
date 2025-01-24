
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cash Expense Form</title>    <link rel="icon" type="image/png" href="imgs\3.png">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      margin-top: 100px;
    }
    form {
      max-width: 400px;
      margin: auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #f9f9f9;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input, textarea, button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
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
            margin-right: 100px;
        }

        nav ul li a:hover {
            color:rgb(182, 103, 29);
        }
        

  </style>
</head>
<body>
<nav>
        <div class="logo">Ocean King</div>
        <ul>
        <li><a href="newbill.php">New Bill</a></li>
            <li><a href="oldbill.php">Old Bill Payments</a></li>
            <li><a href="cheque.php">All Cheque</a></li>
        </ul>
        <a href="logout.html" class="logout">Logout</a>
    </nav>
  <h1 >Enter Cash Expense</h1>
  <form id="expense-form">
    <label for="amount">Amount</label>
    <input type="number" id="amount" name="amount" placeholder="Enter amount " required>
    <label for="amount">Amount</label>
    <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
    <label for="amount">Amount</label>
    <input type="number" id="amount" name="amount" placeholder="Enter amount " required>
    <label for="description">Description</label>
    <textarea id="description" name="description" placeholder="Enter a brief description" rows="4" required></textarea>

    <button type="submit">Submit Expense</button>
  </form>

  <script>
    document.getElementById('expense-form').addEventListener('submit', function(event) {
      event.preventDefault();
      alert('Expense submitted successfully!');
      // You can add logic here to save or process the expense data
    });
  </script>
</body>
</html>
