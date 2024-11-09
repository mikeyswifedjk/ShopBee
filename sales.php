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
    <title>Point of Sales</title>
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

        .text5 {
            letter-spacing: 2px;
            font-size: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .search-bar {
            padding: 3px 0 3px 7px;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            width: 35vw;
        }

        .search-button {
            padding: 5px 20px 5px 20px;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            color: white;
            background-color: black;
            border: none;
            border-radius: 5px;
        }

        th{
            padding: 15px 40px;
        }

        .search-button:hover {
            color: black;
            background-color: #ffca00;
        }

        table {
            text-align: center;
        }

        .date-report {
            position: absolute;
            top: 18px;
            left: 59%;
        }

        .summary {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1 class="text1"> POINT OF SALES </h1>
    <div class="all">
        <form method="GET" action="">
            <label for="search-bar" class="text5"> Search: </label>
            <input class="search-bar" type="search" name="search-bar" id="search-bar" placeholder="Enter Transaction ID">
            <input class="search-button" type="submit" name="search-btn" id="search-btn" value="Search">
        </form>

        <form method="GET" action="sales-report.php" class="date-report">
            <label for="start-date" class="text5">Start Date:</label>
            <input type="date" name="start-date" id="start-date">

            <label for="end-date" class="text5">End Date:</label>
            <input type="date" name="end-date" id="end-date">

            <input class="search-button" type="submit" name="search-btn" id="search-btn" value="Report">
        </form>

        <?php
            $dbConnectionOrders = mysqli_connect("localhost:3306", "root", "", "finalProject");

            if (!$dbConnectionOrders) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch on-hand quantity for each product
            $selectOnHandQtyQuery = "SELECT 
                pv.product_id,
                SUM(pv.qty) AS on_hand_qty
            FROM finalProject.product_variant pv
            GROUP BY pv.product_id";

            $onHandQtyResult = mysqli_query($dbConnectionOrders, $selectOnHandQtyQuery);
            $onHandQtyMap = [];

            while ($row = mysqli_fetch_assoc($onHandQtyResult)) {
                $onHandQtyMap[$row['product_id']] = $row['on_hand_qty'];
            }

            // Fetch total quantity of all products sold within the date range
            $startDate = isset($_GET['start-date']) ? $_GET['start-date'] : '';
            $endDate = isset($_GET['end-date']) ? $_GET['end-date'] : '';

            $selectTotalQtySoldQuery = "SELECT 
                pv.product_id,
                SUM(o.quantity) AS total_qty_sold
            FROM finalProject.orders o
            JOIN finalProject.product_variant pv ON o.product_id = pv.product_id
            WHERE o.order_date BETWEEN '$startDate' AND '$endDate'
            GROUP BY pv.product_id";

            $totalQtySoldResult = mysqli_query($dbConnectionOrders, $selectTotalQtySoldQuery);
            $totalQtySoldMap = [];

            while ($row = mysqli_fetch_assoc($totalQtySoldResult)) {
                $totalQtySoldMap[$row['product_id']] = $row['total_qty_sold'];
            }

            // Fetch payment history with product details
            $selectPaymentHistoryQuery = "SELECT 
                o.id AS order_id, 
                o.user_id, 
                u.first_name, 
                u.last_name, 
                u.contact_number,
                u.address,        
                o.product_name, 
                o.quantity, 
                o.price, 
                o.image, 
                o.product_variant, 
                o.product_id, 
                o.order_date, 
                o.total_amount
            FROM finalProject.orders o
            JOIN finalProject.users u ON o.user_id = u.id";

            // Check if a search has been performed
            if (isset($_GET['search-bar']) && !empty($_GET['search-bar'])) {
                $searchTransactionID = mysqli_real_escape_string($dbConnectionOrders, $_GET['search-bar']);
                $selectPaymentHistoryQuery .= " WHERE o.id = '$searchTransactionID'";
            }

            $paymentHistoryResult = mysqli_query($dbConnectionOrders, $selectPaymentHistoryQuery);

            if (!$paymentHistoryResult) {
                die("Error in SQL query: " . mysqli_error($dbConnectionOrders));
            }

            echo "<table border='1' cellpadding='8' cellspacing='0'>";
            echo "<tr>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                </tr>";

            $totalSales = 0;
            $totalQuantitySold = 0;

            while ($order = mysqli_fetch_assoc($paymentHistoryResult)) {
                echo "<tr>";
                echo "<td>" . $order['order_id'] . "</td>";
                echo "<td>" . $order['first_name'] . " " . $order['last_name'] . "</td>";
                echo "<td>" . $order['product_name'] . "</td>";
                echo "<td>" . $order['quantity'] . "</td>";
                echo "<td>&#8369; " . $order['price'] . "</td>";
                echo "<td>&#8369; " . $order['total_amount'] . "</td>";
                echo "<td>" . $order['order_date'] . "</td>";
                echo "</tr>";

                $totalSales += $order['total_amount'];
                $totalQuantitySold += $order['quantity'];
            }

            echo "</table>";

            echo "<div class='summary'>";
            echo "<p>Total Quantity Sold: " . $totalQuantitySold . "</p>";
            echo "<p>Total Sales: &#8369; " . $totalSales . "</p>";
            echo "</div>";

            mysqli_close($dbConnectionOrders);
        ?>
    </div>
</body>
</html>