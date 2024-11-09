<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

if (!$dbConnection) {
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

if (!empty($_SESSION["selected_items"])){
    $selectedItems = $_SESSION['selected_items'];
    $ids = explode("-", $selectedItems);

    $ids = array_filter($ids, 'strlen'); // Remove empty values
    $selectQuery = "SELECT * FROM cart WHERE id IN (" . implode(',', $ids) . ")";
    $selectResult = mysqli_query($dbConnection, $selectQuery);
   
    if (!$selectResult) {
        die("Error in SQL query: " . mysqli_error($dbConnection));
    }
}else{
    // Handle the case where session data is not set
    echo "Session data not set. Please ensure you have selected items in your cart.";
    exit;
}

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
$resultSettings = $dbConnection->query($sqlGetSettings);

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
<heads>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Document</title>
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

            .checkout-container {
                width: 1250px;
                margin: 10rem auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .items {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 1px solid #ddd;
                text-align: center;
                padding: 10px;
                font-family: "poppin",sans-serif;
            }

            .items p {
                flex: 1;
                margin-right: 11px;
            }

            .items img {
                max-width: 100px;
                max-height: 100px;
                margin-right: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .total {
                margin-top: 20px;
                text-align: right;
                font-size: 18px;
            }

            .total p {
                font-family: "poppins",sans-serif;
                margin-right: 2rem;
                font-size: 2rem;
                font-weight: bold;
                color: #333;
            }

            form {
                position: relative;
                right: 13rem;
                bottom: 1.7rem;
                font-family: "poppins",sans-serif;
                margin-left: 70rem;
                width: 255px;
            }

            form label {
                font-family: "poppins",sans-serif;
                font-size: 20px;
                margin-right: 10px;
            }

            form input[type="text"] {
                position: relative;
                left: 6rem;
                width: 200px;
                padding: 5px;
                font-size: 16px;
            }

            form input[type="submit"] {
                background-color: #000;
                color: #fff;
                padding: 6px 15px;
                font-size: 18px;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                width: 310px;
                transition: 0.2s;
            }

            form input[type="submit"]:hover {
                background-color: #ffce00;
                color: #000;
            }
            .row-fields{
                display: flex;
                justify-content: space-around;
                background-color: #191919;
                color: #fff;
                font-family: "poppins",sans-serif;
            }
            .img-prod{
                margin-left: 9rem;
            }
            .display-text{
                position: relative;
                left: 2%;
            }
            .display-text1{
                margin-left: 6rem;
            }
            .display-text2{
                position: relative;
                left: 1%;
            }
            .display-text3{
                position: relative;
                left: 1%;
            }
            .payment-txt{
                position: relative;
                top:1.6rem;
                right: 0.2rem;
                font-family: "poppins",sans-serif;
                font-weight: bold;
            }
            .spanError {
                position: relative;
                height: 20rem;
                top: 0.5rem;
                color: red;
                font-size: 10px;
            }
    </style>
</heads>
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

<div class="checkout-container">
    <div class="row-fields">
        <p>Product Name</p>
        <p>Image</p>
        <p>Variant</p>
        <p>Quantity</p>
        <p>Price</p>
    </div>
    <?php
    // Initialize the total variable
    $totalAmount = 0;
    // Check if selectedItemsStatement is set before trying to fetch rows
    if (isset($selectResult) && $selectResult) {
        // Display selected items for checkout
        while ($item = mysqli_fetch_assoc($selectResult)):
            ?>
            <div class="items">
                <p class="display-text"><?php echo $item['product_name']; ?></p>
                <?php
                $imagePath = "img/" . $item['product_image'];
                if (file_exists($imagePath)) {
                    echo '<img src="' . $imagePath . '" alt="Product Image" height="150px" width="150px" class="img-prod">';
                } else {
                    echo 'Image not found: ' . $imagePath;
                }
                ?>
                <p class="display-text1"><?php echo $item['variant']; ?></p>
                <p class="display-text2"><?php echo $item['quantity']; ?></p>
                <p class="display-text3"><?php echo $item['price']; ?></p>
                
                <?php
                // Add the item price to the total
                $totalAmount += ($item['quantity'] * $item['price']);
                ?>
            </div>
        <?php endwhile; ?>

        <!-- Display the total amount -->
    <div class="total">
        <p>Total Amount: ₱<?php echo $totalAmount; ?></p>
    </div>
        <form action="process-payment.php" method="post" onsubmit="return validatePayment()">
            <label for="payment" class="payment-txt">Payment:</label>
            <input type="text" name="payment" id="payment">
            <span id="paymentError" class="spanError"></span><br> <br>
            <input type="submit" name="place-order" value="Place Order">
        </form>
    <?php } ?>
</div>
    <script>
        function validatePayment() {
        var paymentInput = document.getElementById('payment');
        var paymentError = document.getElementById('paymentError');

        // Parse the payment amount as a float
        var paymentAmount = parseFloat(paymentInput.value);

        // Check if the payment amount is not a valid number or is not equal to the total amount
        if (isNaN(paymentAmount) || paymentAmount !== <?php echo $totalAmount; ?>) {
            paymentError.innerHTML = 'Payment amount must be equal to the total amount.';
            return false; // Prevent form submission
        } else if (paymentAmount < <?php echo $totalAmount; ?>) {
            paymentError.innerHTML = 'Payment amount must be greater than or equal to the total amount.';
            return false; // Prevent form submission
        } else if (paymentAmount > <?php echo $totalAmount; ?>) {
            paymentError.innerHTML = 'Payment amount must not exceed the total amount.';
            return false; // Prevent form submission
        } else {
            paymentError.innerHTML = ''; // Clear any previous error messages
            return true; // Allow form submission
        }
    }
    </script>
</body>
</html>