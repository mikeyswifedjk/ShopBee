<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

// Check connection
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

// Fetch product details based on the product ID from the URL parameter
$productId = isset($_GET['id']) ? $_GET['id'] : 0;
$query = "SELECT * FROM product WHERE id = $productId";
$result = mysqli_query($dbConnection, $query);

if (!$result) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

// Get product details
$product = mysqli_fetch_assoc($result);

// Fetch product variants based on the product ID
$variantQuery = "SELECT * FROM product_variant WHERE product_id = $productId";
$variantResult = mysqli_query($dbConnection, $variantQuery);

if (!$variantResult) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
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

// Close the database connection
mysqli_close($dbConnection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PRODUCT DETAILS </title>
    <link rel="icon" type="image/png" href="bee.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        .variant-info {
            margin-top: 10px;
        }

        *{
                scroll-behavior: smooth;
            }

            body{
                overflow-x: hidden;
                margin: 0;
                padding: 0;
                box-sizing: content-box;
                transition: background-color 0.5s ease;
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

            .content-categories{
                position: absolute;
                top: 150px;
                width: 1450px;
                height: 25px;
                background-color: black;
            }

            .containers-category{
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: row;
            }

            .containers-category a{
                text-decoration: none;
                color: black;
            }

            .categories {
                display: flex;
                align-items: center;
                justify-content: center;
                transition: 0.2s;
                font-size: 12px;
                font-family:Arial, Helvetica, sans-serif;
                width: 120px;
                padding: 3px 0 10px 0;
                color: white;
            }

            .categories:hover{
                color: #ffca00;
            }

            .all{
                position: relative;
                top: 205px;
                background-color: #f5f5f5;
                margin-left: auto;
                margin-right: auto;
                width: 75vw;
                height: max-content;
                border-radius: 5px;
                font-family: Arial, Helvetica, sans-serif;
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            }

            .all img{
                object-fit: cover;
                margin-left: 20px;
                margin-top: 20px;
                margin-bottom: 15px;
                height: 440px;
                width: 440px;
            }

            .all h1{
                position: absolute;
                top: 5%;
                left: 45%;
                font-size: 55px;
            }

            .all h2{
                position: absolute;
                top: 50%;
                left: 47%;
                font-weight: normal;
                font-size: 15px;
            }

            .peso{
                position: absolute;
                top: 25%;
                left: 47%;
                font-size: 45px;
                font-weight: bold;
                letter-spacing: 3px;
            }

            .qty{
                position: absolute;
                top: 34%;
                left: 75%;
            }

            .all span{
                padding: 0;
                margin: 0;
            }

            .variant-button{
                padding: 10px 30px 10px 30px;
                background-color: black;
                color: white;
                border: none;
                border-radius: 5px;
                margin-bottom: 10px;
                margin-left: 5px;
            }

            .var{
                position: absolute;
                top:60%;
                left: 47%;
                border-radius: 5px;
                width: 30vw;
                height: 20vh;
            }

            .add{
                position: absolute;
                top:90%;
                left: 87%;
                padding: 10px 30px 10px 30px;
                border-radius: 5px;
                background-color: black;
                color: white;
                border: none;
            }
            
            .all button:hover{
                background-color: #ffac00;
                color: black;
            }

            .chosen-variant{
                position: absolute;
                top: 49.5%;
                left: 58%;
                font-size: 15px;
            }

            .footer {
                position: relative;
                top: 230px;
                padding-top: 45px;
                background: #121518;
                font-family: Arial, Helvetica, sans-serif;
                height: 200px;
            }

            .footer-container{
                height:200px;
            }

            .footer .footer-about,
            .footer .footer-contact,
            .footer .footer-project {
                position: relative;
                margin-bottom: 45px;
                color: #999999; 
            }

            .footer .footer-about h3,
            .footer .footer-contact h3,
            .footer .footer-project h3 {
                position: relative;
                margin-bottom: 20px;
                padding-bottom: 10px;
                font-size: 20px;
                font-weight: 600;
                color: #eeeeee;
            }

            .footer .footer-about h3::after,
            .footer .footer-contact h3::after,
            .footer .footer-project h3::after {
                position: absolute;
                content: "";
                width: 50px;
                height: 2px;
                left: 0;
                bottom: 0;
                background: #eeeeee;
            }

            .footer .footer-social {
                position: relative;
                margin-top: 20px;
            }

            .footer-social i {
                position: relative;
                top: 10px;
            }

            .footer .footer-social a {
                display: inline-block;
                align-items: center;
                width: 35px;
                height: 35px;
                text-align: center;
                color: #999999;
                font-size: 14px;
                border: 1px solid #999999;
                border-radius: 45px;
                margin: 0 3px;
            }

            .footer .footer-social a:hover {
                color: #ffffff;
                background:#ffac00;
                border-color: #ffac00;
            }

            .footer .footer-contact p {
                margin-bottom: 9px;
                font-size: 16px;
                color: #999999;
            }

            .footer .footer-contact i {
                margin-right: 10px;
                font-size: 14px;
                color: #999999;
            }

            .footer .footer-project {
                float: left;
                font-size: 0;
                gap: 20px;
            }

            .footer .footer-project a {
                padding: 0 8px 8px 0;
                display: block;
                width: 33.33%;
                float: left;
            }

            .footer .footer-project a img {
                width: 100px;
                height: 100px;
            }

            .footer-about {
                padding: 0 90px;
                max-width: 20%;
            }

            .footer-contact {
                position: absolute;
                right: 55px;
                bottom: 20px;
                max-width: 40%;
                margin-left:20px;
            }

            .footer-project {
                position: absolute;
                bottom: 15px;
                width: 300px;
            }

            .row-container {
                position: relative;
                bottom: 30px;
                display: flex;
                align-items: center;
                justify-content: space-evenly;
            }

            .footer .copyright {
                position: relative;
                padding: 10px 0;
                background-color: black;
            }

            .footer .copyright .copy-text p {
                position: relative;
                left: 200px;
                top: 10px;
                margin: 0;
                font-size: 14px;
                font-weight: 400;
                color: #999999;
            }

            .footer .copyright .copy-text p a {
                color: #999999;
                text-decoration: underline;
            }

            .footer .copyright .copy-text p a:hover {
                color:#ffac00;
            }

            .footer .copyright .copy-menu {
                position: relative;
                font-size: 0;
                text-align: right;
            }

            .footer .copyright .copy-menu a {
                position: relative;
                text-decoration: none;
                right: 210px;
                bottom: 5px;
                color: #999999;
                font-size: 14px;
                font-weight: 400;
                margin-right: 15px;
                padding-right: 15px;
                border-right: 1px solid rgba(255, 255, 255, 0.3);
            }

            .footer .copyright .copy-menu a:hover {
                color:#ffac00;
            }

            .footer .copyright .copy-menu a:last-child {
                margin-right: 0;
                padding-right: 0;
                border-right: none;
            }

            .footer-about p{
                font-size: 14px;
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

    <!-- Categories -->
    <div class="content-categories">
            <div>
                <div class="containers-category">
                    <?php
                    // Database connection
                    $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

                    // Check connection
                    if (!$dbConnection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Fetch categories from the database
                    $query = "SELECT * FROM category";
                    $result = mysqli_query($dbConnection, $query);

                    // Check if the query was successful
                    if (!$result) {
                        die("Error in SQL query: " . mysqli_error($dbConnection));
                    }

                    // Loop through the categories and display only the title
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Create a link for each category that points to the customer dashboard
                        $categoryLink = "product-category.php?category=" . urlencode($row['category']) . "&user=" . urlencode($userName);
                        echo "<a href='$categoryLink'>";
                        echo "<div class='categories'>";
                        echo "<div class='category-title'>";
                        echo "<span>{$row['category']}</span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }

                    // Close the database connection
                    mysqli_close($dbConnection);
                    ?>
                </div>
            </div>
        </div>

    <!-- Display product details -->
    <div class="all">
        <h1><?php echo isset($product['name']) ? $product['name'] : ''; ?></h1>
        <img src="img/<?php echo isset($product['image']) ? $product['image'] : ''; ?>" alt="<?php echo isset($product['name']) ? $product['name'] : ''; ?>" />
        <p class="peso">₱ <span id="priceRange"><?php echo isset($product['price']) ? $product['price'] : ''; ?></span></p>
        <p class="qty">Qty: <span id="productQty"><?php echo isset($product['qty']) ? $product['qty'] : ''; ?></span></p>

        <!-- Display product variants as buttons -->
        <h2>Product Variant: </h2>
        <div class="var">
            <?php while ($variant = mysqli_fetch_assoc($variantResult)): ?>
                <button class="variant-button" data-variant="<?php echo $variant['variant']; ?>" data-price="<?php echo $variant['price']; ?>" data-qty="<?php echo $variant['qty']; ?>"><?php echo $variant['variant']; ?></button>
            <?php endwhile; ?>
        </div>

        <!-- Display selected variant information -->
        <div class="variant-info" id="selectedVariantInfo">
            <!-- Variant information will be dynamically updated here -->
        </div>

        <!-- Updated button onclick event -->
        <button class="add" onclick="addToCart(<?php echo $productId; ?>, '<?php echo $product['name']; ?>', '<?php echo $userName; ?>')">Add to Cart</button>

        <script>
            // Define selectedVariant in the global scope
            var selectedVariant = {};

            document.addEventListener('DOMContentLoaded', function () {
                var variantButtons = document.querySelectorAll('.variant-button');

                variantButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        selectedVariant = {
                            variant: button.getAttribute('data-variant'),
                            price: button.getAttribute('data-price'),
                            qty: button.getAttribute('data-qty')
                        };

                        // Update the displayed information
                        document.getElementById('selectedVariantInfo').innerHTML = '<p class="chosen-variant">' + selectedVariant.variant + '</p>';
                        document.getElementById('priceRange').innerText = selectedVariant.price;
                        document.getElementById('productQty').innerText = selectedVariant.qty;
                    });
                });
            });

            function addToCart(productId, productName, userName) {
                if (Object.keys(selectedVariant).length > 0) {
                    var variant = selectedVariant.variant;
                    var qty = selectedVariant.qty;

                    // Redirect to cart page with query parameters
                    window.location.href = 'add-to-cart.php?id=' + productId +
                        '&name=' + productName +
                        '&variant=' + variant +
                        '&quantity=' + qty +
                        '&user=' + userName;
                } else {
                    alert('Please select a variant before adding to cart.');
                }
            }
        </script>
    </div>

    <!-- Footer -->
    <footer class="footer" id="paa">
        <div class="footer-container">
            <!--Row Container-->
            <div class="row-container">
            <!--About-->
            <div class="footer-about">
                <h3>About Us</h3>
                <p>
                    We're buzzing with excitement to bring you a world of e-commerce wonders. 
                    With a commitment to convenience, quality, and customer satisfaction, 
                    we aim to be your go-to destination for all your online shopping needs.
                </p>
                <div class="footer-social">
                <a href=""><i class="fab fa-twitter"></i></a>
                <a href=""><i class="fab fa-facebook-f"></i></a>
                <a href=""><i class="fab fa-youtube"></i></a>
                <a href=""><i class="fab fa-instagram"></i></a>
                <a href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!--Contact-->
            <div class="footer-contact">
                <h3>Get In Touch</h3>
                <p><i class="fa fa-phone-alt"></i>+012 345 67890</p>
                <p><i class="fa fa-envelope"></i>shopbee800@gmail.com</p>
                <p><i class="fa-solid fa-warehouse"></i> Bustos, Bulacan Philippines</p>
            </div>

            <!--Project-->
            <div class="footer-project">
                <h3>ShopBee Logo</h3>
                <a href=""><img src="bee.png" alt="" /></a>
                <a href=""><img src="swarm.png" alt="" /></a>
            </div>
            </div>
        </div>

        <!--Copyright-->
        <div class="copyright">
            <div class="copyright-container">
            <div class="row-items">
                <div class="copy-text">
                <p>&copy; <a href="file:///Users/maikaordonez/Documents/HTML/FINAL%20PROJECT%20(3A)/index.html">
                2023 ShopBee</a>. All Rights Reserved</p>
                </div>
                <div class="copy-menu">
                <a href="">Terms & Conditions</a>
                <a href="">Privacy Policy</a>
                <a href="https://www.facebook.com/maika.ordonez.3">Designer</a>
                </div>
            </div>
            </div>
        </div>
    </footer>
</body>
</html>