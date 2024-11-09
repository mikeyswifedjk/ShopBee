<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "finalproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    <title>ORDERS</title>
    <style>
         *{
            scroll-behavior: smooth;
        }
        body{
            background-color: <?php echo $bgColor; ?>;
            color: <?php echo $fontColor; ?>;
        }
        .header {
                background-color:black;
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
                background-color:black;
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

            .nav-right{
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
    <!-- Header Black -->
    <div class="header"></div>

    <!-- Header Content -->
    <a href="customer-dashboard.php?user=<?php echo $userName; ?>">
        <div class="container-header">
            <img class="logo" src="img/<?php echo basename($logoPath); ?>" alt="Logo">
            <label class="shop"><?php echo $shopName; ?></label>
        </div>
    </a>

    <!-- Search Bar -->
    <div class="content-search">
        <input type="text" class="search-bar" />
        <button class="search-button">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>

    <!-- Cart Buttons -->
    <a href="cart.php?user=<?php echo $userName; ?>">
            <button class="cart-button">
                <i class="fas fa-shopping-cart"></i>
                <?php
                $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");
                
                $userQuery = "SELECT id FROM users WHERE name = ?";
                $userStatement = mysqli_prepare($dbConnection, $userQuery);
                mysqli_stmt_bind_param($userStatement, "s", $userName);
                mysqli_stmt_execute($userStatement);
                $userResult = mysqli_stmt_get_result($userStatement);
                        
                if (!$userResult) {
                    die("Error in SQL query: " . mysqli_error($dbConnection));
                }
                
                $userRow = mysqli_fetch_assoc($userResult);
                $user_id = isset($userRow['id']) ? $userRow['id'] : 0;

                // Fetch the cart count for the current user
                $cartCountQuery = "SELECT COUNT(*) AS count FROM cart WHERE user_id = ?";
                $cartCountStatement = mysqli_prepare($dbConnection, $cartCountQuery);

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

    <!-- Navigation Links with Dropdown -->
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

    <h1>Purchase History</h1>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Fetch details of the selected items from the cart
    $selectedItems = $_SESSION['selected_items'];
    $ids = explode("-", $selectedItems);
    // Filter out non-numeric values
    $ids = array_filter($ids, 'is_numeric');
    // Ensure all IDs are integers
    $ids = array_map('intval', $ids);
    $selectQuery = "SELECT * FROM cart WHERE id IN (" . implode(',', $ids) . ")";
    $selectResult = mysqli_query($conn, $selectQuery);

    if (!$selectResult) {
        die("Error in SQL query: " . mysqli_error($conn));
    }

    // Initialize the total variable
    $totalAmount = 0;

    // Insert order details into the orders table
    $insertOrderQuery = "INSERT INTO orders (user_id, product_id, product_name, price, product_variant, image, quantity, total_amount, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $insertOrderStatement = mysqli_prepare($conn, $insertOrderQuery);

    // Update product quantity in the 'product' table by subtracting purchased quantity
    // $updateProductQuantityQuery = "UPDATE product SET qty = qty - ? WHERE id = ?";
    // $updateProductQuantityStatement = mysqli_prepare($dbConnection, $updateProductQuantityQuery);

    // Update product_variant quantity
    // $updateProductVariantQuantityQuery = "UPDATE product_variant SET qty = qty - ? WHERE id = ?";
    // $updateProductVariantQuantityStatement = mysqli_prepare($dbConnection, $updateProductVariantQuantityQuery);

    // Create a table for the order summary
    echo "<table border='1' cellpadding=10 cellspacing=0>";
    echo "<tr><th>Product</th><th>Image</th><th>Variant</th><th>Quantity</th><th>Unit Price</th><th>Total Amount</th><th>Order Date</th></tr>";

    // Update product quantity in the 'product' table by subtracting purchased quantity
    $updateQuantityQuery = "UPDATE product AS p
        JOIN product_variant AS pv ON p.id = pv.product_id
        SET p.qty = p.qty - pv.qty
        WHERE pv.id = ?";
    $updateQuantityStatement = mysqli_prepare($conn, $updateQuantityQuery);

    // Delete items from the cart after placing the order
    $deleteCartItemsQuery = "DELETE FROM cart WHERE id = ?";
    $deleteCartItemStatement = mysqli_prepare($conn, $deleteCartItemsQuery);

    while ($item = mysqli_fetch_assoc($selectResult)) {
        // Display item in the order summary
        echo "<tr>";
        echo "<td>" . $item['product_name'] . "</td>";
        echo "<td><img src='img/" . $item['product_image'] . "' height='150px' width='150px'></td>";
        echo "<td>" . $item['variant'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>₱" . $item['price'] . "</td>";

        // Calculate the total amount for each item and accumulate it
        $itemTotal = $item['price'] * $item['quantity'];
        $totalAmount += $itemTotal;

        echo "<td>₱" . $itemTotal . "</td>";
        echo "<td>" . date("Y-m-d H:i:s") . "</td>";
        echo "</tr>";
        
        // Insert into orders table
        mysqli_stmt_bind_param($insertOrderStatement,
            "iisdsdid",
            $item['user_id'],
            $item['product_id'],
            $item['product_name'],
            $item['price'],
            $item['variant'],
            $item['product_image'],
            $item['quantity'],
            $itemTotal);  // Use the calculated item total instead of $totalAmount
        mysqli_stmt_execute($insertOrderStatement);

        // Update product quantity
        // mysqli_stmt_bind_param($updateProductQuantityStatement, "ii", $item['quantity'], $item['product_id']);
        // mysqli_stmt_execute($updateProductQuantityStatement);

        // Update product quantity
        // mysqli_stmt_bind_param($updateQuantityStatement, "i", $item['product_variant_id']);
        // mysqli_stmt_execute($updateQuantityStatement);

        // if (!mysqli_stmt_execute($updateQuantityStatement)) {
        //     die("Error updating product quantity: " . mysqli_error($dbConnection));
        // }

        // Delete item from the cart
        mysqli_stmt_bind_param($deleteCartItemStatement, "i", $item['id']);
        mysqli_stmt_execute($deleteCartItemStatement);
    }

    mysqli_stmt_close($updateQuantityStatement);
    mysqli_stmt_close($deleteCartItemStatement);
    echo "</table>";

    mysqli_stmt_close($insertOrderStatement);
    mysqli_close($conn); // Close the database connection
    ?>

</body>
</html>