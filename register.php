<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Function to check if an email already exists in the database
function isEmailUnique($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    return (mysqli_num_rows($result) == 0);
}

// Check if a form parameter named "register" has been submitted via the HTTP POST method.
if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["confirm_password"];
    $phone_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $fname = $_POST['first_name'];
    $mname = $_POST['middle_name'];
    $lname = $_POST['last_name'];
    $image = $_POST['image_path'];

    // Check if the email is unique (not already in the database)
    $conn = mysqli_connect("localhost:3306", "root", "", "finalProject");

    if (!isEmailUnique($conn, $email)) {
        echo "<script>alert('Email already exists. Please choose a different email.'); window.history.back();</script>";
    } else {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Enable verbose debug output
            $mail->SMTPDebug = 0; // SMTP::DEBUG_SERVER;
            // Send using SMTP
            $mail->isSMTP();
            // Set the SMTP server to send through
            $mail->Host = 'smtp.gmail.com';
            // Enable SMTP authentication
            $mail->SMTPAuth = true;
            // SMTP username
            $mail->Username = 'shopbee800@gmail.com'; // email that will be host
            // SMTP password
            $mail->Password = 'fqjhitqjtddbxqvz'; // app name password
            // Enable TLS encryption;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->Port = 587;
            // Sender
            $mail->setFrom('shopbee800@gmail.com', 'Shopbee');
            // Add a recipient
            $mail->addAddress($email, $name);
            // Set email format to HTML
            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Email verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
            // send function to email
            $mail->send();

            // Insert the user into the database
            $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
            // insert in users table
            $sql = "INSERT INTO users (name, email, password, verification_code, email_verified_at, attempts, contact_number, address, first_name, middle_name, last_name, image_path) VALUES ('$name', '$email', '$encrypted_password', '$verification_code', NULL, 0, '$phone_number', '$address', '$fname', '$mname', '$lname', '$image')";

            // Execute the SQL query only if it's not an admin account
            mysqli_query($conn, $sql);

            // Redirect to the email verification page
            header("Location:http://localhost/finalProject/email-verification.php?email=" . $email);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: " . $e->getMessage();
        }
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
    <title>REGISTER</title>

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
            top: 20px;
            left: 70px;
            font-size: 60px;
            color: #ffac00;
            letter-spacing: 5px;
        }

        form{
            margin-top: 80px;
        }

        .gray input{
            margin-left: 70px;
            margin-top: 10px;
            width: 360px;
            height: 30px;
            font-size: 16px;
            padding-left: 10px;
        }

        .gray input[type="checkbox"]{
            position: absolute;
            top: 84.5%;
            width: 15px;
            height: 15px;
        }

        .gray input[type="submit"]{
            position: absolute;
            top: 77%;
            width: 360px;
            height: 35px;
            font-size: 18px;
            padding-left: 10px;
            background-color:#ffac00;
            border: none;
        }

        .gray input[type="submit"]:hover{
            background-color:#f0a150;
            color: black;
        }

        .gray label{
            position: absolute;
            top: 86%;
            left: 70px;
            font-size: 13px;
            color: black;
            font-family: verdana;
            width: 50%;
            text-align: center;
        }

        .login{
            position: absolute;
            top: 93%;
            left: 150px;
            font-size: 15px;
            color: black;
            font-family: verdana;
        }
        .login a{
            color: #ffac00;
            text-decoration: none;
        }

        .login a:hover{
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
            <h3>SIGN UP </h3>    
            <form method="POST" onsubmit="return validateForm();">
                <input type="text" id="first_name" name="first_name" placeholder="First Name" required /><br>
                <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" required /><br>
                <input type="text" id="last_name" name="last_name" placeholder="Last Name" required /><br>
                <input type="tel" id="contact_number" name="contact_number" placeholder="Contact Number" required /><br>
                <input type="text" id="address" name="address" placeholder="Address" required /><br> 
                <input type="text" name="name" placeholder="Username" required /><br>
                <input type="email" name="email" placeholder="info@gmail.com" required /><br>
                <input type="password" name="password" id="password" placeholder="Password" required /><br>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required /><br>            
                <input type="submit" name="register" value="REGISTER">
                <input type="checkbox" name="word" id="word" required />
                <label for="word">By signing up, You agree to ShopBee's 
                    Terms of Service & Privacy Policy.
                </label>
                <p class="login">Have an account? <a href="login.php"> LOGIN </a></p>
            </form>
        </div> 
    </div>
    <script>
         function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            // Check if password and confirm password are equal
            if (password !== confirmPassword) {
                alert('Password and Confirm Password do not match.');
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</body>
</html>