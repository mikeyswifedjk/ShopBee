<?php
if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];
    
    // Connect with the database
    $conn = mysqli_connect("localhost:3306", "root", "", "finalProject");
    
    // Check if the verification code is correct
    $sql = "SELECT * FROM users WHERE email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Correct verification code, proceed with database update
        $row = mysqli_fetch_assoc($result);
        if (isset($_GET['type'])) {
            $sql_update = "UPDATE users SET reset_token = '', reset_token_expiration = '' WHERE email = '$email' AND verification_code = '$verification_code'";
        } else {
            $sql_update = "UPDATE users SET email_verified_at = NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
        }
        
        $result_update = mysqli_query($conn, $sql_update);
        if ($result_update && mysqli_affected_rows($conn) > 0) {
            if (isset($_GET['type'])) {
                header("Location: http://localhost/finalProject/updatepassword.php?email=$email");
                exit(); // Ensure no further code execution after redirection
            } else {
                echo "<script>alert('Successfully Registered!'); document.location.href = 'login.php';</script>";
                exit(); // Ensure no further code execution after displaying alert
            }
        } else {
            echo "<script>alert('Database error. Please try again later.'); window.history.back();</script>";
            exit(); // Ensure no further code execution after displaying alert
        }
    } else {
        // Incorrect verification code, show an alert to the user
        echo "<script>alert('Incorrect verification code. Please try again.'); window.history.back();</script>";
        exit(); // Ensure no further code execution after displaying alert
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VERIFY CODE</title>
    <link rel="icon" type="image/png" href="bee.png"/>
    <style>
        body{
            overflow-x: hidden;
            overflow-y: hidden;
            background-color:black;
        }
        .header-black{
            position: absolute;
            top: 0px;
            left: 0px;
            height: 45vh;
            width: 1450px;
            background-color: black;
        }

        .content{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color:white;
            width: 630px;
            height: 680px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;
        }

        .welcome{
            text-align: center;
            font-size: 50px;
            font-family: sans-serif;
            letter-spacing: 3px;
        }

        .content img{
            height: 110px;
            width: 110px;
        }

        .verify-label{
            font-size: 30px;
            font-family: monaco;
            letter-spacing: 3px;
        }

        .sentences{
            font: 17px;
            font-family: verdana;
            max-width: 500px;
        }

        .content input[type="text"]{
            position: relative;
            left: 50%;
            top: 0;
            transform: translate(-50%,0);
            font-size:16px;
            margin-top:10px;
            padding: 10px;
            width: 200px;            
        }

        .content input[type="submit"]{
            position: relative;
            left: 50%;
            top: 0;
            transform: translate(-50%,0);
            font-size:15px;
            margin-top:10px;
            padding: 10px;
            width: 150px;
            border-radius:5px; 
            border: none;
            background-color: #ffac00; 
            font-family: monospace; 
        }

        .content input[type="submit"]:hover{
            color: white;
            background-color: black;
        }

        .try-again{
            font-size: 17px;
            margin-top: 20px;
            font-family: Verdana;
        }

        .try-again a {
            text-decoration: none;
            color: black;
        }

        .try-again a:hover{
            color: #ffac00;
        }

        .header-black img{
            position: absolute;
            top: 0px;
            left: 10px;
            height:100vh;
            width:100vh;
        }

        .png2 img{
            position: absolute;
            top: 0px;
            left: 570px;
            height:100vh;
            width:120vh;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div class="header-black">
            <img src="airplane.png" alt="">           
        </div>
        <div class="png2">
            <img src="airplane.png" alt="">
        </div>  
    </div>
    <div class="content">
        <p class="welcome"> Welcome!</p>
        <center> 
            <a href="register.php"> <img src="bee.png" alt=""> </a>
            <p class="verify-label"> Verify its you.</p>
            <p class="sentences"> We sent verification code to your email account.
                Please check your inbox and enter the 6-digits code.
            </p>
        </center>

        <form method="POST">
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
            <input type="text" name="verification_code" placeholder="Enter verification code" required /> <br> <br>
            <input type="submit" name="verify_email" value="Verify Email">
        </form>
        <center>
            <p class="try-again"> Didn't receive an email? <a href=""> Try Again</a></p>
        </center>
    </div>
</body>
</html>