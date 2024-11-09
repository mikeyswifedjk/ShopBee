<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

//include('admin-account.php');

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    header("Location: http://localhost/finalProject/customer-dashboard.php?user=". $_SESSION['user_name']);
} 

// Check if a form parameter named "login" has been submitted via the HTTP POST method.
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    // Define the default admin credentials
    $default_admin_email = "admin@gmail.com";
    $default_admin_password = "admin";
    // Connect to the database
    $conn = mysqli_connect("localhost:3306", "root", "", "finalProject");

    //Check if there is an existing email address of admin
    $stmt_admin = mysqli_prepare($conn, "SELECT * FROM admin WHERE email = ?");
    mysqli_stmt_bind_param($stmt_admin, "s", $email);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);


    // Check if credentials are okay, and email is verified
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result_admin) > 0) {
        // Admin login
        $user = mysqli_fetch_object($result_admin);
        // Check if the password is incorrect
        if (!password_verify($password, $user->password)) {
            // Increment login attempts for admin
            echo "<script>alert('Password is not correct for admin.'); window.history.back();</script>";
            exit;
        }

        // Set session variables for the logged-in admin
        $_SESSION['user_type'] = 'admin';
        $_SESSION['user_email'] = $email;
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_fullname'] = $admin['fullname'];

        // You may add further checks or actions for admin login if needed
        header("Location: http://localhost/finalProject/dashboard.php");
        exit;

    } else if (mysqli_num_rows($result) > 0){
        // Regular user login
    $user = mysqli_fetch_object($result);

    // Check if the user is blocked
    if ($user->blocked == 1) {
        echo "<script>alert('Your account is blocked. Contact the admin for assistance.'); window.history.back();</script>";
        exit;
    }

    // Check if the password is incorrect
    if (!password_verify($password, $user->password)) {
        // Increment login attempts for regular user
        recordLoginAttempt($conn, $email);

        // Get the current login attempts
        $loginAttempts = getLoginAttempts($conn, $email);

        // Check if the user has reached the maximum attempts
        $maxAttempts = 3; // Adjust this value as needed

        if ($loginAttempts >= $maxAttempts) {
            // Block the user
            $blockSql = "UPDATE users SET blocked = 1 WHERE email = '$email'";
            mysqli_query($conn, $blockSql);

            echo "<script>alert('Your account is blocked. Contact the admin for assistance.'); window.history.back();</script>";
            exit;
        }

        echo "<script>alert('Password is not correct.'); window.history.back();</script>";
        exit;
    }

    // Check to verify your email
    if ($user->email_verified_at == null && strtolower($email) !== 'admin@gmail.com') {
        echo "<script>alert('Please verify your email <a href=\"email-verification.php?email=" . $email . "\">from here</a>'); window.history.back();</script>";
        exit;
    }

    // Regular user is logged in, redirect to customer landing page
    $_SESSION['user_type'] = 'customer';
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_email'] = $email;

    // Regular user is logged in, redirect to customer landing page
    header("Location: http://localhost/finalProject/customer-dashboard.php");
    exit;
    } else {
        // Email not found
        echo "<script>alert('Email not found.'); window.history.back();</script>";
        exit;
    }
}
    function recordLoginAttempt($conn, $email){
        // Check if there is a record for the user in the login_attempts table
        $sql = "SELECT * FROM users WHERE email = '" . $email . "'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "<script>alert('SQL error: " . mysqli_error($conn) . "');</script>";  // Debugging output for query errors
        }
        if (mysqli_num_rows($result) == 0) {
            // Insert a new record if it doesn't exist
            $insertSql = "INSERT INTO users (email, attempts) VALUES ('$email', 1)";
            mysqli_query($conn, $insertSql);
        } else {
            // Update the existing record
            $updateSql = "UPDATE users SET attempts = attempts + 1 WHERE email = '$email'";
            mysqli_query($conn, $updateSql);
        }
    }

    function getLoginAttempts($conn, $email){
        $sql = "SELECT attempts FROM users WHERE email = '" . $email . "'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "<script>alert('SQL error: " . mysqli_error($conn) . "');</script>"; // Debugging output for query errors
        }

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['attempts'];
        } else {
            return 0; // Return 0 if there are no login attempts
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="bee.png"/>
    <title>LOGIN</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow-y: hidden;
            overflow-x: hidden;
        }

        .container img{
            position: absolute;
            top: 0;
            left: 0;
            height: 110vh;
            width: 210vh;
            object-fit:cover;
        }

        .black{
            position: absolute;
            top: 0;
            left: 0;
            height: 110vh;
            width: 210vh;
            background-color: black;
            opacity: 0.6;
        }
        
        .container-content{
            position: absolute;
            top: 50%;
            left:50%;
            transform: translate(-50%, -50%);
            width: 80vw;
            height: 80vh;
            border-radius:10px;
        }

        .container-content video{
            width: 45vw;
            height: 80vh;
            border-top-left-radius:10px;
            border-bottom-left-radius:10px;
            object-fit: cover;
        }

        .gray{
            position: absolute;
            top: 0px;
            left: 648px;
            height: 80vh;
            width:50vw;
            background-color: white;
            opacity: 0.9;
        }

        .gray h3{
            position: absolute;
            top: 130px;
            left: 70px;
            font-size: 60px;
            color: #ffac00;
            letter-spacing: 5px;
        }

        .gray input[type="email"]{
            position: absolute;
            top: 210px;
            left: 70px;
            width: 360px;
            height: 40px;
            font-size: 18px;
            padding-left: 10px;
        }

        .gray input[type="password"]{
            position: absolute;
            top: 270px;
            left: 70px;
            width: 360px;
            height: 40px;
            font-size: 18px;
            padding-left: 10px;
        }

        .forget a{
            position: absolute;
            top: 320px;
            left: 70px;
            font-size: 16px;
            text-decoration: none;
            color:black;
            font-family: verdana;
        }

        .forget a:hover{
            color:#f0a150;
        }

        .gray input[type="submit"]{
            position: absolute;
            top: 350px;
            left: 70px;
            width: 360px;
            height: 40px;
            font-size: 18px;
            padding-left: 10px;
            background-color:#ffac00;
            border: none;
        }

        .gray input[type="submit"]:hover{
            background-color:#f0a150;
            color: black;
        }

        .signup{
            position: absolute;
            top: 410px;
            left: 150px;
            font-size: 15px;
            color: black;
            font-family: verdana;
        }
        .signup a{
            color: #ffac00;
            text-decoration: none;
        }

        .signup a:hover{
            color:#f0a150;
        }

    </style>
</head>

<body>
    <div class="container">
        <img src="bg4.jpeg" alt="">
    </div> 
    <div class="black"></div> 
    <div class="container-content">  
        <video autoplay loop muted>
            <source src="vid2.mp4" type="video/mp4">
            <source src="vid2.ogg" type="video/ogg">
        </video>    

        <div class="gray">
            <h3>LOGIN </h3>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required /><br>
                <input type="password" name="password" placeholder="Password" required /><br>
                <p class="forget"><a href="forgotpassword.php">Forgot Password?</a></p>
                <input type="submit" name="login" value="Login">
                <p class="signup">New to Shopbee? <a href="register.php"> SIGN UP </a></p>
            </form>
        </div> 
    </div>
</body>
</html>