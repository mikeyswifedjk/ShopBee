<?php include('admin-page.php');?>
<?php include 'admin-change-password.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .settings {
            position: absolute;
            top: 47px;
            left: 290px;
            padding: 10px;
            background-color: #eee;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            border-bottom-left-radius: 5px;
            width: 77vw;
            height: 88.9vh;
            text-align: center;
        }

        .settings h1 {
            margin: 0;
            padding: 0;
            margin-top: 30px;
            margin-left: 30px;
            letter-spacing: 5px;
            font-size: 50px;
        }

        .settings p {
            margin: 0;
            padding: 0;
            margin-top: 10px;
            margin-left: 30px;
            font-family: Arial, Helvetica, sans-serif;
            color: #b2b2b2;
            font-size: 30px;
        }

        .change {
            position: absolute;
            padding: 10px 30px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            border: none;
            border-radius: 5px;
            top: 85%;
            left: 45%;
            background-color: black;
            color: white;
        }

        .change:hover{
            color: black;
            background-color: #ffca00;
        }

        .settings input[type="password"] {
            margin: 0;
            padding: 15px;
            padding-left: 15px;
            margin-top: 15px;
            margin-left: 30px;
            font-size: 22px;
            width: 45vw;
        }

        label{
            font-size: 18px;
            margin-left: 32px;
            font-family: Arial, Helvetica, sans-serif;
            letter-spacing: 3px;
        }
    </style>
</head>
<body>
<div class="settings">
    <h1>PASSWORD SETTINGS</h1>
    <p> Manage password and security</p> <br> <br> <br>

    <!-- Form for changing the password -->
    <form method="post">
        <!-- Current Password -->
        <label for="current_password">OLD PASSWORD</label> <br>
        <input type="password" id="current_password" name="current_password" placeholder="Enter your old password" required /> <br> <br>

        <label for="password">NEW PASSWORD</label> <br>
        <input type="password" id="password" name="password" placeholder="Enter your new password" value="" required /> <br> <br>

        <!-- Confirm New Password -->
        <label for="confirm_password">CONFIRM NEW PASSWORD</label> <br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" value="" required /> <br> <br>

        <!-- Submit Button -->
        <button class="change" type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</body>
</html>