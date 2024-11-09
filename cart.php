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

// Process checkout for selected items
if (isset($_POST['checkout_items'])) {
    if (!empty($_POST['selected_items'])) {
        $_SESSION['selected_items'] =  $_POST['selected_items'];
        // Redirect to checkout page
        header("Location: checkout.php?user=$userName");
        exit;
    }
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

// Fetch updated cart count
$cartCountQuery = "SELECT COUNT(*) AS count FROM cart WHERE user_id = ?";
$cartCountStatement = mysqli_prepare($dbConnection, $cartCountQuery);
mysqli_stmt_bind_param($cartCountStatement, "i", $user_id);
mysqli_stmt_execute($cartCountStatement);
$cartCountResult = mysqli_stmt_get_result($cartCountStatement);

if (!$cartCountResult) {
    die("Error in SQL query: " . mysqli_error($dbConnection));
}

$cartCountRow = mysqli_fetch_assoc($cartCountResult);
$cartCount = isset($cartCountRow['count']) ? $cartCountRow['count'] : 0;

if (isset($_POST['delete_items'])) {
    if (!empty($_POST['selected_items'])) {
        $selectedItems = explode("-", $_POST['selected_items']);
        foreach ($selectedItems as $selectedItem) {
            // Process each selected item individually
            $selectedItem = intval($selectedItem);

            $deleteQuery = "DELETE FROM cart WHERE id = ? AND user_id = ?";
            $deleteStatement = mysqli_prepare($dbConnection, $deleteQuery);
            mysqli_stmt_bind_param($deleteStatement, "ii", $selectedItem, $user_id);
            $deleteResult = mysqli_stmt_execute($deleteStatement);

            if (!$deleteResult) {
                die("Error in SQL query: " . mysqli_error($dbConnection));
            }
        }

        header("Location: cart.php?user=$userName");
        exit;
    }
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
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CART</title>
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
            .cart-container{
                margin-top: 10rem;
                width: 1450px;
                font-size: 1.2rem;
            }

            .items {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 15px;
                background-color: #f9f9f9;
            }

            .items img {
                max-width: 90px;
                max-height: 100px;
                margin-right: 10px;
            }

            .items p {
                margin: 2rem 0;
                flex-grow: 1;
            }

            .items form {
                display: flex;
                align-items: center;
            }

            .delete-button {
                background-color: #ff5555; 
                color: #fff;
            }

            button[name="checkout_items"] {
                background-color: #4caf50;
                color: #fff;
            }

            .img-prod{
                position: relative;
                right: 71%;
            }

            .prod-name{
                position: relative;
                left:10%;
            }

            .box{
                position: relative;
                left: 1%;
                height: 1.1rem;
                width: 1.1rem;
            }

            .forms{
                position: relative;
                right: 48%;
                display: flex;
                gap: 2rem;
            }

            .forms button{
                position: relative;
                left: 250%;
                background-color: #191919;
                color: #fff;
                padding: 0.4rem 0.9rem;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                border-radius: 5px;
            }

            .row-fields{
                background-color: #191919;
                color: #fff;
                font-size: 1.2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                gap:10rem;
            }

            .row-fields p{
                position: relative;
                right: 2%;
            }

            .quantity-txt{
                position: relative;
                left: -1%;
            }
            
            .select_variant{
                position: relative;
                left: -44%;
                width: 100px;
            }

            .variant-txt{
                position: relative;
                left:-49%;
            }

            .tPrice{
                position:relative; 
                left:8%;
                text-align: center;
            }
            .total-container{
                position: sticky;
                bottom: 0;
                color:#fff;
            }
            .total-container span{
                position: relative;
                top: 3.3rem;
                margin-left: 1rem;
                font-size: 1.6rem;
                font-family: "poppins",sans-serif;
            }
            .total-container button{
                position: relative;
                bottom: 0.4rem;
                left: 68.7rem;
                margin-left: 1.2rem;
                margin-top: 1.8rem;
                border-radius: 0.3rem;
                border: none;
                padding: 0.6rem 1.6rem;
                background-color: #ffac00;
                color: #000;
                font-weight: bold;
            }
            #totalPrice{
                position: relative;
                top: 3.3rem;
                margin-left: 1rem;
            }
            #cartForm{
                width: 100vw;
                height: 5rem;
                background-color: #191919;
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

    <!-- Cart and Like Buttons -->
    <a href="cart.php?user=<?php echo $userName; ?>">
        <button class="cart-button">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-number"><?php echo $cartCount; ?></span>
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
    </nav> <br>

    <!-- Display cart -->
    <?php
    echo '<div class="cart-container">';
    echo '<div class="row-fields">';
    echo '<p></p>';
    echo '<p>Product</p>';
    echo '<p>Variant</p>';
    echo '<p>Quantity</p>';
    echo '<p>Unit Price</p>';
    echo '<p>Total Price</p>';
    echo '<p>Action</p>';
    echo '</div>';
    $totalQuantity = 0;
    $totalPrice = 0;

    // Display cart items
    $cartItemsQuery = "SELECT c.id, c.product_name, c.variant, c.quantity, pv.price, p.image, pv.variant as product_variant, pv.product_id, pv.qty as variant_qty
                       FROM cart c
                       JOIN product_variant pv ON c.variant = pv.variant AND c.product_id = pv.product_id
                       JOIN product p ON c.product_id = p.id
                       WHERE c.user_id = ?";
    $cartItemsStatement = mysqli_prepare($dbConnection, $cartItemsQuery);
    mysqli_stmt_bind_param($cartItemsStatement, "i", $user_id);
    mysqli_stmt_execute($cartItemsStatement);
    $cartItemsResult = mysqli_stmt_get_result($cartItemsStatement);

    if (!$cartItemsResult) {
        die("Error in SQL query: " . mysqli_error($dbConnection));
    }

    // Initialize total variables
    $totalQuantity = 0;
    $totalPrice = 0;

    while ($cartItem = mysqli_fetch_assoc($cartItemsResult)):
        $quantity = $cartItem['quantity'];
        $price = $cartItem['price'];
        $totalQuantity += $quantity;
        $totalPrice += ($quantity * $price);

        ?>
        <div class="items">
            <input type="checkbox" name="selected_items[]" value="<?php echo $cartItem['id']; ?>" class="box">
            <p class="prod-name"><?php echo $cartItem['product_name']; ?></p>
            <p style="visibility: hidden;">Current Variant: <?php echo $cartItem['product_variant']; ?></p>
            <p style="display:none;">Quantity: <?php echo $quantity; ?></p>
            <p class="tPrice">₱ <?php echo $price; ?></p>
            <p style="position:relative; left:15%">₱ <?php echo $quantity * $price; ?></p>
            <img src="img/<?php echo $cartItem['image']; ?>" alt="<?php echo $cartItem['product_name']; ?>" class="img-prod" height="90" width="90" />

            <!-- Variant Dropdown and Update Button -->
            <form method="post" class="forms" action="update-cart.php" id="update_form_<?php echo $cartItem['id']; ?>">
                <input type="hidden" name="user" value="<?php echo $userName; ?>">
                <input type="hidden" name="cart_id" value="<?php echo $cartItem['id']; ?>">

                <select name="new_variant" id="new_variant_<?php echo $cartItem['id']; ?>" class="select_variant">
                    <!-- Fetch and display all variants for the product -->
                    <?php
                    $allVariantsQuery = "SELECT DISTINCT variant FROM product_variant WHERE product_id = ?";
                    $allVariantsStatement = mysqli_prepare($dbConnection, $allVariantsQuery);
                    mysqli_stmt_bind_param($allVariantsStatement, "i", $cartItem['product_id']);
                    mysqli_stmt_execute($allVariantsStatement);
                    $allVariantsResult = mysqli_stmt_get_result($allVariantsStatement);

                    if (!$allVariantsResult) {
                        die("Error in SQL query: " . mysqli_error($dbConnection));
                    }

                    while ($availableVariant = mysqli_fetch_assoc($allVariantsResult)):
                        ?>
                        <option value="<?php echo $availableVariant['variant']; ?>" <?php echo ($availableVariant['variant'] == $cartItem['product_variant']) ? 'selected' : ''; ?>><?php echo $availableVariant['variant']; ?></option>
                    <?php endwhile; ?>
                </select>

                <!-- Quantity buttons -->
                <input type="number" class="quantity-txt" name="new_quantity" id="new_quantity_<?php echo $cartItem['id']; ?>" value="<?php echo $quantity; ?>" min="1" max="<?php echo $cartItem['variant_qty']; ?>">

                <button type="submit"><i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i></button>
            </form>
        </div>
    <?php endwhile;

    echo '</div>';
    ?>
    <div class="total-container">
           <!-- Display total quantity and total price dynamically using JavaScript -->
    <span id="totalQuantity">Total Quantity: <?php echo $totalQuantity; ?></span>
    <span id="totalPrice">Total Amount: <?php echo $totalPrice; ?></span>

    <form id="cartForm" method="post" action="cart.php?user=<?php echo $userName; ?>">
        <button class="delete-button" type="submit" name="delete_items">Delete Items</button>
        <button type="submit" name="checkout_items">Checkout Items</button>
        <input type="hidden" id="item-ids" name="selected_items">   
    </form>                 
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete the selected items?");
        }

        function setItems() {
            const items = document.getElementsByClassName("items");
            let itemIds = "";
            let index = 0;
            for (const item of items) {
                const itemBox = item.firstElementChild;
                if (itemBox.checked) {
                    if (index == 0){
                        itemIds += itemBox.value;
                    }else {
                        itemIds += "-" + itemBox.value;
                    }
                }
                index++;
            }
            return itemIds;
        }

        document.getElementById('cartForm').addEventListener('submit', function (event) {
            const deleteButton = document.querySelector('button[name="delete_items"]');
            const checkoutButton = document.querySelector('button[name="checkout_items"]');

            // Check if the clicked button is the "Checkout Selected" button
            if (event.submitter === deleteButton) {
                // For other buttons (e.g., "Delete Selected"), show the confirmation dialog
                if (confirmDelete()) {
                    document.getElementById("item-ids").value = setItems();
                } else {
                    event.preventDefault(); // Prevents the default form submission if the user cancels the confirmation
                }
            }else if (event.submitter === checkoutButton) {
                document.getElementById("item-ids").value = setItems();
            }
        });

        function updateTotals() {
            let checkboxes = document.getElementsByName('selected_items[]');
            totalQuantity = 0;
            totalPrice = 0;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    let parentDiv = checkbox.closest('.items');
                    let quantity = parseInt(parentDiv.querySelector('.quantity-txt').value);
                    let price = parseFloat(parentDiv.querySelector('.tPrice').innerText.replace('₱ ', ''));
                    totalQuantity += quantity;
                    totalPrice += quantity * price;
                }
            });

            document.getElementById('totalQuantity').innerText = 'Total Quantity: ' + totalQuantity;
            document.getElementById('totalPrice').innerText = 'Total Amount: ₱ ' + totalPrice.toFixed(2);
        }


        // Reattach the updateTotals function to the change event of checkboxes after changes in HTML structure
        function attachUpdateEventListeners() {
            let checkboxes = document.getElementsByName('selected_items[]');
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', updateTotals);
            });
        }

        // Initialize totals and attach event listeners when the page loads
        updateTotals();
        attachUpdateEventListeners();
    </script>
</body>
</html>