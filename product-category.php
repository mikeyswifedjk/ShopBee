<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
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

//settings for customer-design-settings
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1";
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
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SHOPBEE</title>
    <link rel="icon" type="image/png" href="bee.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
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

            .daily-discover-content{
                display: block;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 20px;
                height: max-content;
            }

            .daily-discover-title{
                position: relative;
                top: 540px;
                left: 95px;
                padding: 10px;
                margin-bottom: 9px;
                letter-spacing: 2px;
                font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                font-size: 16px;
                background-color:#f5f5f5;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                border-bottom: 4px solid #ffac00;
                width: 86vw;
            }

            .daily-discover-title button{
                border: none;
                background-color:gainsboro;
                border-radius: 5px;
            }

            .daily-discover-container{
                height: max-content;
            }

            .shopnow-button{
                position: relative;
                top: 0px;
                background-color:#ffac00;
                opacity: 0;
                transition: opacity 0.2s
            }

            .items:hover .shopnow-button{
                opacity: 1;
            }

            .shopnow-button p{
                padding: 4px;
            }

            .grid-items a{
                text-decoration: none;
                color:black;
            }

            .items{
                max-width: fit-content;
                text-align: center;
                height:270px;
                transition: 0.2s;
                background-color:#f5f5f5;
            }

            .items:hover {
                box-shadow: #ffac00 0px 1px 8px;
            }

            .items img{
                object-fit: cover;
            }

            .grid-items{
                margin-top: 540px;
                display: grid;
                grid-template-columns: auto auto auto auto auto auto;
                padding: 10px;
                justify-content: center;
                gap: 10px;
            }

            .discover-description{
                position: relative;
                top: 6px;
                font-size: 16px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .discover-price p{
                position: relative;
                top: 4px;
                background-color:#ffac00;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin-left: auto;
                margin-right: auto;
                border-radius: 20px;
                width: 130px;
                font-size: 13px;
            }
            
            .sample-product{
                position: absolute;
                top: 190px;
                left: 130px;
                font-size: 14px;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                letter-spacing: 5px;
            }

            .OneEndorse .endorseOne{
                position: absolute;
                top: 215px;
                left: 130px;
                vertical-align: middle;
                height: 300px;
                width: 800px;
                transition: all 0.5s ease;
                object-fit: cover;
            }

            .TwoEndorse .endorseTwo{
                position: absolute;
                top: 215px;
                left: 945px;  
                vertical-align: middle;
                height: 145px;
                width: 385px;
                transition: all 0.5s ease;
                object-fit: cover;
            }

            .ThreeEndorse .endorseThree{
                position: absolute;
                top: 370px;
                left: 945px;  
                vertical-align: middle;
                height: 145px;
                width: 385px;
                transition: all 0.5s ease;
                object-fit: cover;
            }

            .footer {
                position: relative;
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

            .page{
                display: flex;
                justify-content:center;
                text-align:center;
                gap: 10px;
            }

            .pagination-link{
                background-color: #f5f5f5;
                box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;
                border-radius: 3px;
                padding: 8px 12px 8px 12px;
                font-family: Arial, Helvetica, sans-serif;
                transition: 0.2s;
                cursor: pointer;
                text-decoration:none;
                color:black;
            }

            .pagination-link:hover{
                background-color: #ffac00;
                color: black;
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

        <!-- Product Endorse -->
        <label class="sample-product">SAMPLE PRODUCTS</label>
            <div class="OneEndorse">
            <img class="endorseOne" src="img/<?php echo basename($endorseOnePath); ?>" alt="Endorse One">
        </div>

        <div class="TwoEndorse">
            <img class="endorseTwo" src="img/<?php echo basename($endorseTwoPath); ?>" alt="Endorse Two">
        </div>

        <div class="ThreeEndorse">
            <img class="endorseThree" src="img/<?php echo basename($endorseThreePath); ?>" alt="Endorse Three">
        </div>

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

        <!-- Products -->
        <div class="daily-discover-title">
            <label> FILTER BY</label>
            <a href="product-category.php?category=<?php echo urlencode($_GET['category']); ?>&filter=all&user=<?php echo $userName; ?>"><button name="all">ALL</button></a>
            <a href="product-category.php?category=<?php echo urlencode($_GET['category']); ?>&filter=latest&user=<?php echo $userName; ?>"><button name="latest">LATEST</button></a>
            <a href="product-category.php?category=<?php echo urlencode($_GET['category']); ?>&filter=topsale&user=<?php echo $userName; ?>"><button name="topsale">TOP SALES</button></a>
        </div>

        <!-- Daily discover content -->
        <div class="daily-discover-content" id="product">
            <!-- Items container -->
            <div class="daily-discover-container">
                <!-- Grid items -->
                <div class="grid-items">
                    <?php
                    // Database connection
                    $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

                    // Check connection
                    if (!$dbConnection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Default value for itemsPerPage
                    $itemsPerPage = 6;

                    // Fetch filterType if it's set
                    $filterType = isset($_GET['filter']) ? $_GET['filter'] : 'all';

                    // Fetch products from the database based on the filter type
                    switch ($filterType) {
                        case 'all':
                            // Show all products from the selected category with pagination
                            $category = isset($_GET['category']) ? urldecode($_GET['category']) : '';
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $offset = ($page - 1) * $itemsPerPage;
                            $query = "SELECT * FROM product WHERE category = '$category' LIMIT $offset, $itemsPerPage";
                            break;

                        case 'latest':
                            // Show the latest 6 products from the selected category without pagination
                            $category = isset($_GET['category']) ? urldecode($_GET['category']) : '';
                            $query = "SELECT * FROM product WHERE category = '$category' ORDER BY id DESC LIMIT 6";
                            break;

                        case 'topsale':
                            // Show top sales based on total_amount without pagination and specific category
                            $category = isset($_GET['category']) ? urldecode($_GET['category']) : '';
                            $query = "SELECT p.* 
                                        FROM product p
                                        JOIN (
                                            SELECT product_id, SUM(quantity) AS total_sales
                                            FROM orders o
                                            JOIN product p ON o.product_id = p.id
                                            WHERE p.category = '$category'
                                            GROUP BY product_id
                                            ORDER BY total_sales DESC
                                            LIMIT 6
                                      ) o ON p.id = o.product_id";
                                break;

                        default:
                            // Default case for handling invalid filter types
                            $category = '';
                            $query = '';
                            break;
                    }

                    $result = mysqli_query($dbConnection, $query);

                    if (!$result) {
                    die("Error in SQL query: " . mysqli_error($dbConnection));
                    }

                    // Display products
                    while ($product = mysqli_fetch_assoc($result)) {
                        echo "<a href='product-details.php?id={$product['id']}'>";
                        echo "<div class='items'>";
                        echo "<img src='img/{$product['image']}' alt='{$product['name']}' height='195' width='200' />";
                        echo "<div class='discover-description'>";
                        echo "<span>{$product['name']}</span>";
                        echo "</div>";
                        echo "<div class='discover-price'>";
                        echo "<p>₱{$product['price']}</p>";
                        echo "</div>";
                        echo "<div class='shopnow-button'>";
                        echo "<p>SHOP NOW</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</a>";
                    }

                    ?>
                </div>
            </div>
                <?php
                    // Pagination links for "ALL" filter
                    if ($filterType == 'all') {
                        echo "<br> <div class='page'>";
                        $totalProductsQuery = "SELECT COUNT(*) AS total FROM product WHERE category = '$category'";
                        $totalResult = mysqli_query($dbConnection, $totalProductsQuery);
                    
                        if (!$totalResult) {
                            die("Error in SQL query: " . mysqli_error($dbConnection));
                        }
                    
                        $totalProducts = mysqli_fetch_assoc($totalResult)['total'];
                        $totalPages = ceil($totalProducts / $itemsPerPage);
                    
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<a href='?filter=$filterType&category=$category&page=$i&user=$userName' class='pagination-link'>$i</a>";
                        }
                    
                        echo "</div>";
                    }

                    // Close the database connection
                    mysqli_close($dbConnection);
                ?>
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