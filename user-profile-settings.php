<?php
session_start();

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

// Retrieve user information from the database
$query = "SELECT image_path, address, first_name, middle_name, last_name, contact_number FROM users WHERE name='$userName'";
$result = mysqli_query($dbConnection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Save the user information in session variables
    $_SESSION['image_path'] = $row['image_path'];
    $_SESSION['address'] = $row['address'];
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['middle_name'] = $row['middle_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['contact_number'] = $row['contact_number'];
}

// Settings for customer-design-settings
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
                    height: 144vh;
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


                .cart-button:hover {
                    color: #ffce00;
                }

                .like-button {
                    position: absolute;
                    top: 68px;
                    left: 1220px;
                    font-size: 18px;
                    height: 40px;
                    width: 50px;
                    color: white;
                    background-color: black;
                    border: none;
                    border-radius: 2px;
                }

                .like-button:hover {
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

                .settings{
                    position: relative;
                    top: 180px;
                    width: 80vw;
                    height: 110vh;
                    margin-left: auto;
                    margin-right: auto;
                    background-color: #fff;
                    padding: 1rem;
                    border-radius: 0.4rem;
                    box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
                }

                .settings h1{
                    margin: 0;
                    padding: 0;
                    margin-top: 20px;
                    margin-left: 30px;
                    letter-spacing: 5px;
                    font-size: 40px;
                }

                .settings p{
                    margin: 0;
                    padding: 0;
                    margin-top: 10px;
                    margin-left: 30px;
                    font-family: Arial, Helvetica, sans-serif;
                    color: #b2b2b2;
                    font-size: 20px;
                }

                .profile-con{
                    position: absolute;
                    left: 54%;
                    top: 20%;
                    width: 35vw;
                    height: 62vh;
                    border-radius: 5px;
                    background-color: #f5f5f5;
                }

                .profile-image{
                    padding: 20px;
                    object-fit: cover;
                    width: 32.2vw;
                    height: 56.5vh;
                    border-radius: 20px;
                }

                .forms-content label{
                    font-size: 18px;
                    margin-left: 32px;
                    font-family: Arial, Helvetica, sans-serif;
                    letter-spacing: 3px;
                }
                
                .forms-content input[type="text"],
                .forms-content input[type="tel"]{
                    margin: 0;
                    padding: 12px;
                    padding-left: 15px;
                    margin-top: 15px;
                    margin-left: 30px;
                    font-size: 22px;
                    width: 36vw;
                }

                .profile-con input[type="file"] {
                    position: absolute;
                    top: 115%;
                    left: 180px;
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 12px;
                }

                .profile-txt{
                    position: relative;
                    top: 22px;
                    left: 180px;
                    font-family: Arial, Helvetica, sans-serif;
                    letter-spacing: 3px;
                    font-size: 20px;
                }

                .save-btn {
                    position: absolute;
                    padding: 10px 35px;
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 12px;
                    border: none;
                    border-radius: 5px;
                    top: 88%;
                    left: 68.5%;
                    background-color: black;
                    color: white;
                    width: 12rem;
                }

                .save-btn:hover {
                    background-color: #ffca00;
                    color: black;
                }
                <?php
                // Add a timestamp to the end of the CSS file
                echo '<link rel="stylesheet" href="your-stylesheet.css?' . time() . '" />';
                ?>
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

        <script>
            // Add the function definition for updateProfileImage
            function updateProfileImage(newImagePath) {
            document.getElementById('profileImage').src = newImagePath;
            }
        </script>
    
            <!-- Profile Settings Form -->
<div class="settings">
        <h1>ACCOUNT SETTINGS</h1>
        <p>Manage and protect your account</p> <br>
    <form action="update-profile.php" class="forms" method="post" enctype="multipart/form-data">
        <div class="profile-con">
            <!-- New Profile Picture -->
            <div id="profilePicturePreviewContainer"></div>
            <img id="profileImage" class="profile-image" src="<?php echo isset($_SESSION['image_path']) ? $_SESSION['image_path'] . '?' . time() : ''; ?>" alt="Profile Picture">
            <label for="profile_picture" class="profile-txt">Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
        </div>

        <div class="forms-content">
        <!-- Display username -->
        <label for="new_username">Username:</label> <br>
        <input type="text" id="new_username" name="new_username" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required /> <br> <br>

        <!-- First Name -->
        <label for="first_name">First Name:</label> <br>
        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" value="<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : ''; ?>" required /> <br> <br>

        <!-- Middle Name -->
        <label for="middle_name">Middle Name:</label> <br>
        <input type="text" id="middle_name" name="middle_name" placeholder="Enter your middle name" value="<?php echo isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : ''; ?>" required /> <br> <br>

        <!-- Last Name -->
        <label for="last_name">Last Name:</label> <br>
        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" value="<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>" required /> <br> <br>

        <!-- Address --> 
        <label for="address">Address:</label> <br>
        <input type="text" id="address" name="address" placeholder="Enter your address" value="<?php echo isset($_SESSION['address']) ? htmlspecialchars($_SESSION['address']) : ''; ?>" required /> <br> <br>

        <!-- Contact Number -->
        <label for="contact_number">Contact Number:</label><br>
        <input type="text" id="contact_number" name="contact_number" pattern="[0-9]{11}" value="<?php echo $_SESSION['contact_number']; ?>"><br>

        <!-- Submit Button -->
        <button type="submit" class="save-btn">Save Changes</button>
        </div>
    </form>
    </div>

    <script>
        function validateForm() {
            var contactNumber = document.getElementById("contact_number").value;
            var pattern = /^[0-9]{11}$/;
            if (!pattern.test(contactNumber)) {
                alert("Please enter a valid 11-digit contact number.");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>