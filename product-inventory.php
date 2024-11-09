<?php include('admin-page.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory</title>
    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
            overflow-x: hidden;
            transition: background-color 0.5s ease;
        }

        .all {
            margin-top:10px;
            margin-left: 20%;
            margin-bottom: 20px;
            padding: 20px;
            background-color: white;
            width: 76vw;
            height: max-content;
        }

        .text1 {
            padding: 0;
            margin: 0;
            margin-top: 15px;
            margin-left: 20%;
            font-size: 30px;
            color: black;
            letter-spacing: 5px;
        }

        form {
            margin-bottom: 10px;
        }

        .text5{
            letter-spacing: 2px;
            font-size: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .search-bar{
            padding: 3px 0 3px 7px;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            width: 35vw;
        }

        .search-button{
            padding: 5px 20px 5px 20px;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            color: white;
            background-color: black;
            border: none;
            border-radius: 5px;
        }

        .search-button:hover{
            color: black;
            background-color: #ffca00;
        }

        .table{
            font-size: 18px;
            text-align: center;
        }

        th {
            padding: 15px 55px 15px 55px;
        }

        .report-btn{
            position: absolute;
            top: 2.5%;
            left: 87.7%;
            padding: 5px 20px 5px 20px;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            color: black;
            border: none;
            border-radius: 5px;
        }

        .report-btn:hover{
            color: black;
            background-color: #ffca00;
        }
    </style>
</head>

<body>
    <h1 class="text1">PRODUCT INVENTORY</h1>
    <button class="report-btn" onclick="generateReport()">Generate Report</button>

    <script>
    function generateReport() {
        // Redirect to the report.php page
        window.location.href = 'inventory-report.php';
    }
    </script>

    <!-- Add this form inside the body tag, before the table -->
    <div class="all">
    <form method="GET" action="">
        <label for="searchProductId" class="text5">Search:</label>
        <input class="search-bar" type="text" id="searchProductId" name="searchProductId" placeholder="Enter product ID" required />
        <button class="search-button" type="submit">Search</button>
    </form>

        <?php
        // Establish a database connection
        $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

        if (!$dbConnection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch products and their variants from the database
        // Define the initial query without the search condition
        $selectProductsQuery = "SELECT * FROM product";

        // Check if a search query is provided
        if (isset($_GET['searchProductId'])) {
            $searchProductId = $_GET['searchProductId'];

            // Validate input to prevent SQL injection
            $searchProductId = mysqli_real_escape_string($dbConnection, $searchProductId);

            // Modify the initial query to include the search condition
            $selectProductsQuery = "SELECT * FROM product WHERE id = '$searchProductId'";
        }

        // Execute the modified query
        $productsResult = mysqli_query($dbConnection, $selectProductsQuery);

        if (!$productsResult) {
            die("Error in SQL query: " . mysqli_error($dbConnection));
        }

        echo "<table class='table' border='1' cellpadding='20' cellspacing='0'>";
        echo "<tr><th>Product ID</th><th>Product Name</th><th>Category</th><th>Total Quantity</th><th>Total Qty On Hand </th></tr>";

        while ($product = mysqli_fetch_assoc($productsResult)) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>" . $product['category'] . "</td>";

            // Fetch total quantity from product table
            $totalQuantity = $product['qty'];

            // Fetch total quantity sold from orders table
            $selectOrdersQuery = "SELECT SUM(quantity) as total_sold FROM orders WHERE product_id = " . $product['id'];
            $ordersResult = mysqli_query($dbConnection, $selectOrdersQuery);

            if (!$ordersResult) {
                die("Error in SQL query: " . mysqli_error($dbConnection));
            }

            $totalSold = mysqli_fetch_assoc($ordersResult)['total_sold'];

            // Calculate total on hand quantity
            $totalOnHand = $totalQuantity - $totalSold;

            echo "<td>" . $totalQuantity . "</td>";
            echo "<td>" . $totalOnHand . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        mysqli_close($dbConnection); // Close the database connection
        ?>
    </div>
</body>

</html>