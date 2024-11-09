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

$userName = isset($_POST['user']) ? $_POST['user'] : '';

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $_SESSION = array();

    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    header("Location: http://localhost/finalProject/no-account-landing-page.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch user_id based on the user_name
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

    // Get the posted data for update
    $cartId = isset($_POST['cart_id']) ? $_POST['cart_id'] : 0;
    $newVariant = isset($_POST['new_variant']) ? $_POST['new_variant'] : '';
    $newQuantity = isset($_POST['new_quantity']) ? $_POST['new_quantity'] : 0;

    // Update the variant, quantity, and total price in the cart
    $updateQuery = "UPDATE cart c
    JOIN product_variant pv ON c.variant = pv.variant AND c.product_id = pv.product_id
    SET c.variant = ?, c.quantity = ?, c.price = pv.price, c.total_price = ? * pv.price
    WHERE c.id = ? AND c.user_id = ?";
    $updateStatement = mysqli_prepare($dbConnection, $updateQuery);
    mysqli_stmt_bind_param($updateStatement, "siiii", $newVariant, $newQuantity, $newQuantity, $cartId, $user_id);
    $updateResult = mysqli_stmt_execute($updateStatement);

    if (!$updateResult) {
        die("Error in SQL query: " . mysqli_error($dbConnection));
    }

    // Redirect to cart.php after successful update
    header("Location: cart.php?user=" . urlencode($userName));
    exit;
}

// Close the database connection
mysqli_close($dbConnection);
?>
