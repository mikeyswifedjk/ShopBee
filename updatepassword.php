<?php
    //retrieving a value from the query string of a URL and storing it in a variable named $email.
    $email = $_GET['email'];
    //user is logged in
    if (isset($_POST['updatepass']))
    {
    //start changing password
    //check fields
    $newpassword = md5($_POST['newpassword']);
    $confirmpassword = md5($_POST['confirmpassword']);
    $conn = mysqli_connect("localhost:3306", "root", "", "finalProject");
    $sql ="SELECT password FROM users WHERE email='.$email'";
    $result = mysqli_query($conn, $sql);
    //check two new passwords
    if($newpassword==$confirmpassword){
    //successs
    //change password in db
    $querychange = "UPDATE users SET password='" .password_hash($_POST['newpassword'], PASSWORD_DEFAULT)."' WHERE email='" .$email."'";
    $change_result = mysqli_query($conn, $querychange);
    echo "<script>alert('Your password has been changed'); window.location.href = 'login.php';</script>";
    }
    else{
    echo "<script>alert('New password doesn\'t match!');</script>";
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
    <title> UPDATE PASSWORD </title>

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
            width: 590px;
            height: 660px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;
        }

        .content img{
            margin-top: 60px;
            height: 160px;
            width: 160px;
        }

        .set-label{
            font-size: 42px;
            font-family:verdana;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .content input[type="submit"]{
            position: relative;
            left: 50%;
            top: 0;
            transform: translate(-50%,0);
            font-size:15px;
            margin-top:40px;
            padding: 10px;
            width: 200px;
            border-radius:5px; 
            border: none;
            background-color: #ffac00; 
            font-family: monospace; 
        }

        .content input[type="submit"]:hover{
            color: white;
            background-color: black;
        }

        .content input[type="password"]{
            position: relative;
            left: 50%;
            top: 0;
            transform: translate(-50%,0);
            font-size:16px;
            margin-top:10px;
            padding: 10px;
            width: 300px;       
        }

        .content label{
            position: relative;
            left: 155px;
            top: 0;      
            font-size: 20px;
            letter-spacing: 2px;
            font-family: arial;
        }

        .header-black img {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 200vh;
            height: 100vh;
            opacity: 0.6;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div class="header-black">
            <img src="bg3.jpeg" alt="">           
        </div> 
    </div>

    <div class="content">
        <center> 
            <img src="bee.png" alt="">
            <p class="set-label"> Set Password </p>
        </center>

        <form method="POST">
            <label>New Password</label><br>
            <input type="password" name="newpassword" placeholder="************" required /><br> <br>
            <label>Confirm Password</label><br>
            <input type="password" name="confirmpassword" placeholder="************" required /> <br>
            <input type="submit" name="updatepass" value="Update Password">
        </form>
    </div>    
</body>
</html>