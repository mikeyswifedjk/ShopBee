<?php 
include('admin-page.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment History</title>
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

            table{
                text-align: center;
            }

            .date-report{
                position: absolute;
                top: 18px;
                left: 59%;
            }

        </style>
    </head>
    <body>
        <h1 class="text1"> PAYMENT HISTORY</h1>
        <div class="all">
            <form method="GET" action="">
                <label for="search-bar" class="text5"> Search: </label>
                <input class="search-bar" type="search" name="search-bar" id="search-bar" placeholder="Enter Transaction ID">
                <input class="search-button" type="submit" name="search-btn" id="search-btn" value="Search">
            </form>

            <form method="GET" action="orders-report.php" class="date-report">
                <label for="start-date" class="text5">Start Date:</label>
                <input type="date" name="start-date" id="start-date" required />
                
                <label for="end-date" class="text5">End Date:</label>
                <input type="date" name="end-date" id="end-date" required />
                
                <input class="search-button" type="submit" name="search-btn" id="search-btn" value="Report">
            </form>


            <?php
            // Establish a database connection
            $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

            if (!$dbConnection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch payment history from the orders table
            $selectPaymentHistoryQuery = "SELECT * FROM orders";

            // Check if a search has been performed
                if (isset($_GET['search-bar']) && !empty($_GET['search-bar'])) {
                    $searchTransactionID = mysqli_real_escape_string($dbConnection, $_GET['search-bar']);
                    $selectPaymentHistoryQuery .= " WHERE id = '$searchTransactionID'";
                }

                $paymentHistoryResult = mysqli_query($dbConnection, $selectPaymentHistoryQuery);

                if (!$paymentHistoryResult) {
                    die("Error in SQL query: " . mysqli_error($dbConnection));
                }

            echo "<table border='1' cellpadding='8' cellspacing='0'>";
            echo "<tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Variant</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                    <th>Amount Paid</th>
                    <th>Order Date</th>
            </tr>";

            while ($order = mysqli_fetch_assoc($paymentHistoryResult)) {
                echo "<tr>";
                echo "<td>" . $order['id'] . "</td>";
                echo "<td>" . $order['user_id'] . "</td>";
                echo "<td>" . $order['product_id'] . "</td>";
                echo "<td>" . $order['product_name'] . "</td>";
                echo "<td>" . $order['product_variant'] . "</td>";
                echo "<td>" . $order['quantity'] . "</td>";
                echo "<td>₱" . $order['price'] . "</td>";
                echo "<td>₱" . $order['total_amount'] . "</td>";
                echo "<td>₱" . $order['total_amount'] . "</td>";
                echo "<td>" . $order['order_date'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            mysqli_close($dbConnection); // Close the database connection
            ?>
        </div>
    </body>
</html>