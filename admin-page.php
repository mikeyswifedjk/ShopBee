<?php
$conn = mysqli_connect("localhost:3306", "root", "", "finalProject");

// Check if the newUsername query parameter is set
if (isset($_GET["newUsername"])) {
    // Retrieve the updated username from the query parameter
    $newAdminName = $_GET["newUsername"];
} else {
    // Check if the username is stored in the session
    if (isset($_SESSION['adminUsername'])) {
        // Retrieve the username from the session
        $newAdminName = $_SESSION['adminUsername'];
    } else {
        // Fetch the actual admin username from the database
        $result = mysqli_query($conn, "SELECT username FROM admin WHERE email = 'admin@gmail.com'");
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $newAdminName = $row['username'];
        } else {
            // If fetching from the database fails, use a default value
            $newAdminName = "ADMIN";
        }
    }
}

// Fetch the profile picture path from the database based on the retrieved username
$result = mysqli_query($conn, "SELECT image FROM admin WHERE username = '$newAdminName'");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $profile_picture = $row['image'];
} else {
    // If fetching from the database fails or no image is found, provide a default path
    $profile_picture = "default_image.jpg"; // Update this with your default image path
}

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
    // Perform logout logic
    session_destroy();
    header("Location: login.php"); // Redirect to the login page after logout
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="bee.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia&effect=neon|outline|emboss|shadow-multiple">
    <title>ADMIN PAGE</title>
    <style>

        body{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: white;
        }
        .sidebar{
            position: relative;
            top: 15px;
            left: 15px;
            position: fixed;
            width: 18vw;
            height:96vh;
            background-color:black;
            border-radius: 5px;
        }

        .admin-btn{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
            height: 185px;
            width: 18vw;
            font-size: 20px;
            text-align: center;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .logout{
            border-bottom-right-radius:  5px;
            border-bottom-left-radius: 5px;
        }

        .admin-btn img{
            height: 100px;
            width: 100px;
            object-fit: cover;
            border-radius: 50px;
        }

        .btn button, .logout{
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 15px;
            height: 7.8vh;
            width: 18vw;
            background-color:#191919;
            border: none;
            color: white; 
            font-size: 13px;     
        }

        a {
            text-decoration: none;
        }

        .btn button:hover{
            background-color:orange;
            transition: 0.3s;
        }

        i{
            padding:1rem;
        }

    </style>
</head>

<body>
    <div class="sidebar">
        <div class="content">
            <a href="admin-account.php?newUsername=<?php echo urlencode($newAdminName); ?>">
                <button class="admin-btn">
                    <img src="<?php echo $profile_picture; ?>" alt="">
                    <label> <?php echo $newAdminName; ?> </label>
                </button>
            </a>
            <div class="btn">
                <a href="dashboard.php"><button class="dashboard"> <i class="fa-solid fa-house-chimney" style="color: #ffffff;"></i> <span> DASHBOARD </span> </button></a>
                <a href="add-product.php"> <button class="product"> <i class="fa-solid fa-box-open" style="color: #ffffff;"></i> <span> PRODUCT </span> </button> </a>
                <a href="category-management.php"> <button class="category"> <i class="fa-solid fa-list" style="color: #ffffff;"></i> <span> CATEGORY </span> </button> </a>
                <a href="product-inventory.php"> <button class="inventory"> <i class="fa-solid fa-clipboard-list" style="color: #ffffff;"></i> <span> INVENTORY </span></button> </a>
                <a href="orders.php"> <button class="orders"> <i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i> <span> ORDERS </span></button> </a>
                <a href="sales.php"> <button class="reports"> <i class="fa-solid fa-chart-simple" style="color: #ffffff;"></i> <span> SALES </span> </button> </a>
                <a href="unlock-user.php"><button> <i class="fa-solid fa-user-group" style="color: #ffffff;"></i> <span> CUSTOMER</span></button></a>
                <a href="customer-design-setting.php"> <button><i class="fa-solid fa-gears" style="color: #ffffff;"></i> <span> DESIGN SETTING </span></button></a>
                <a href="no-account-landing-page.php"><button class="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i> <span> LOG OUT</span></button></a>
            </div>
        </div>
    </div>
</body>
</html>