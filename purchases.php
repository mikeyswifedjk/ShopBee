<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost:3306", "root", "", "finalProject");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    $userName = $_SESSION['user_name'];
} else {
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/finalProject/no-account-landing-page.php");
    exit;
}

// If you want to log out, you can add a condition to check for a logout action
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Clear all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to the login page or handle accordingly
    header("Location: http://localhost/finalProject/no-account-landing-page.php");
    exit;
}

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1";
$resultSettings = $conn->query($sqlGetSettings);

if ($resultSettings->num_rows > 0) {
    // Output data ng bawat row
    while ($row = $resultSettings->fetch_assoc()) {
        $bgColor = $row["background_color"];
        $fontColor = $row["font_color"];
        $shopName = $row["shop_name"];
        $logoPath = $row["logo_path"];
        $imageOnePath = $row["image_one_path"];
        $imageTwoPath = $row["image_two_path"];
        $imageThreePath = $row["image_three_path"];
        $bannerOnePath = $row["banner_one_path"];
        $bannerTwoPath = $row["banner_two_path"];
        $endorseOnePath = $row["endorse_one_path"];
        $endorseTwoPath = $row["endorse_two_path"];
        $endorseThreePath = $row["endorse_three_path"];
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Purchase History</title>
    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            box-sizing: content-box;
            transition: background-color 0.5s ease;
            background-color: <?php echo $bgColor; ?>;
            color: <?php echo $fontColor; ?>;
        }

        .header {
            background-color: black;
            position: absolute;
            top: 0px;
            left: 0px;
            width: 1450px;
            height: 150px;
        }

        .logo {
            height: 100px;
            width: 100px;
            margin-top: 20px;
            transition: all 0.5s ease;
        }

        .container-header {
            position: absolute;
            top: 0;
            left: 130px;
            width: 1200px;
            height: 150px;
            background-color: black;
            transition: background-color 0.5s ease;
        }

        .shop {
            position: absolute;
            top: 40px;
            left: 110px;
            font-size: 75px;
            color: white;
            transition: all 0.5s ease;
        }

        .search-bar {
            border-radius: 6px;
            position: absolute;
            top: 73px;
            left: 530px;
            width: 650px;
            height: 30px;
            border: none;
        }

        .search-button {
            position: absolute;
            top: 75px;
            left: 1144px;
            color: black;
            height: 28px;
            width: 38px;
            background-color: #ffce00;
            border: none;
            border-radius: 6px;
        }

        .search-button:hover {
            background-color: #ffac00;
        }

        .cart-button {
            position: absolute;
            top: 78px;
            left: 85%;
            display: flex;
            gap: 0.7rem;
            font-size: 20px;
            height: 40px;
            width: 50px;
            color: white;
            background-color: black;
            border-radius: 2px;
            border: none;
        }

        .cart-button:hover {
            color: #ffce00;
        }

        .nav-right {
            position: absolute;
            top: 9px;
            left: 1200px;
        }

        .dropdown {
            display: inline-block;
        }

        .dropbtn {
            background-color: transparent;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-family: Arial, Helvetica, sans-serif;
        }

        .dropdown-content a:hover {
            background-color: #ffac00;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            color: #ffce00;
        }
        table {
                font-family: "poppins",sans-serif;
                width: 1320px;
                border-collapse: collapse;
                margin-top: 7rem;
                text-align:center;
                margin-left: auto;
                margin-right: auto;
            }

            th, td {
                padding: 12px;
                border: 1px solid #000;
            }

            th {
                background-color: #000;
                color: #fff;
            }

            td {
                background-color: #ffffff;
                color: #333;
            }

            /* Style the first row differently, for example, make it bold */
            tr:first-child {
                font-weight: bold;
            }
    </style>
</head>

<body>
    <!-- Display header and navigation links -->
    <div class="header"></div>
    <a href="customer-dashboard.php?user=<?php echo $userName; ?>">
        <div class="container-header">
            <img class="logo" src="img/<?php echo basename($logoPath); ?>" alt="Logo">
            <label class="shop"><?php echo $shopName; ?></label>
        </div>
    </a>
    <div class="content-search">
        <input type="text" class="search-bar" />
        <button class="search-button">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
    <a href="cart.php?user=<?php echo $userName; ?>">
        <button class="cart-button">
            <i class="fas fa-shopping-cart"></i>
            <!-- Fetch the cart count for the current user -->
            <?php
            $userQuery = "SELECT id FROM users WHERE name = ?";
            $userStatement = mysqli_prepare($conn, $userQuery);
            mysqli_stmt_bind_param($userStatement, "s", $userName);
            mysqli_stmt_execute($userStatement);
            $userResult = mysqli_stmt_get_result($userStatement);

            if (!$userResult) {
                die("Error in SQL query: " . mysqli_error($conn));
            }

            $userRow = mysqli_fetch_assoc($userResult);
            $user_id = isset($userRow['id']) ? $userRow['id'] : 0;

            $cartCountQuery = "SELECT COUNT(*) AS count FROM cart WHERE user_id = ?";
            $cartCountStatement = mysqli_prepare($conn, $cartCountQuery);

            if ($cartCountStatement) {
                mysqli_stmt_bind_param($cartCountStatement, "i", $user_id);
                mysqli_stmt_execute($cartCountStatement);
                $cartCountResult = mysqli_stmt_get_result($cartCountStatement);

                if ($cartCountResult) {
                    $cartCountRow = mysqli_fetch_assoc($cartCountResult);
                    $cartCount = isset($cartCountRow['count']) ? $cartCountRow['count'] : "0";

                    // Display the cart number
                    echo "<span class='cart-number'>$cartCount</span>";
                }

                mysqli_stmt_close($cartCountStatement);  // Close the prepared statement
            }
            ?>
        </button>
    </a>
    <nav class="nav-right">
        <div class="dropdown">
            <button class="dropbtn">Welcome, <?php echo $userName; ?> &#9662;</button>
            <div class="dropdown-content">
                <a href="user-profile-settings.php">Profile Settings</a>
                <a href="users-change-password.php">Password</a>
                <a href="purchases.php">My Purchases</a>
                <a href="?logout=1">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Fetch user's purchase history -->
    <h1>Purchase History</h1>
    <?php

        $selectQuery = "SELECT orders.*, product.name AS product_name, product.image AS product_image 
        FROM orders 
        INNER JOIN product ON orders.product_id = product.id
        WHERE orders.user_id = ?";
        $selectStatement = mysqli_prepare($conn, $selectQuery);

        if ($selectStatement) {
        mysqli_stmt_bind_param($selectStatement, "i", $user_id);
        mysqli_stmt_execute($selectStatement);
        $result = mysqli_stmt_get_result($selectStatement);

        if ($result) {
        if (mysqli_num_rows($result) > 0) {
        // Display the purchase history in a table
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr>
            <th>Product</th>
            <th>Image</th>
            <th>Variant</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Order Date</th>
        </tr>";

        while ($item = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $item['product_name'] . "</td>";

        // Assuming 'product_image' is the alias for the product image column
        $productImagePath = "img/" . $item['product_image'];
        echo "<td><img src='$productImagePath' height='150px' width='150px'></td>";

        echo "<td>" . $item['product_variant'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>₱" . $item['price'] . "</td>";
        echo "<td>₱" . $item['total_amount'] . "</td>";
        echo "<td>" . $item['order_date'] . "</td>";
        echo "</tr>";
        }

        echo "</table>";
        } else {
        echo "No results found in the purchase history.";
        }
        } else {
        echo "Error fetching results: " . mysqli_error($conn);
        }

        mysqli_stmt_close($selectStatement);
        } else {
        echo "Error in prepared statement: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    ?>

</body>

</html>