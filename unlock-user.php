<?php include('admin-page.php');?>
<?php
$conn = mysqli_connect("localhost:3306", "root", "", "finalProject");

// Function to unblock a user
function unBlock($conn, $email)
{
    try {
        $resetSql = "UPDATE users SET blocked = 0, attempts = 0 WHERE email = '$email'";
        return mysqli_query($conn, $resetSql);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["searchEmail"])) {
    $searchEmail = mysqli_real_escape_string($conn, $_GET["searchEmail"]);
    $sql = "SELECT * FROM users WHERE email LIKE '%$searchEmail%'";
} else {
    // If the search form is not submitted, retrieve all users
    $sql = "SELECT * FROM users";
}

$result = mysqli_query($conn, $sql);

echo "<h1 class='text1'>USER MANAGEMENT</h1>";

echo "<button class='report-btn' onclick='generateReport()'> Generate Report </button>";

echo "<div class='view'>";

echo "<h2 class='text2'> USER LIST</h2>";

// Search Form
echo "<form method='get' action='unlock-user.php'>";
echo "<label for='searchEmail' class='text5'>Search Email: </label>";
echo "<input type='email' name='searchEmail' class='searchtxt' id='searchEmail' placeholder='Enter email address' required>";
echo "<button type='submit' class='btnSearch'>Search</button>";
echo "</form>";

echo "<form method='POST'>";
echo "<table border=1 cellspacing=0 cellpadding=10 class='viewTable'>";
echo "<tr class='thView'>";
echo "<th>Username</th>";
echo "<th>Email</th>";
echo "<th>Attempts</th>";
echo "<th>Blocked</th>";
echo "<th>Action</th>";
echo "</tr>";

while ($row = mysqli_fetch_array($result)) {
    $temp0 = $row['name'];
    $temp1 = $row['email'];
    $temp2 = $row['attempts'];
    $temp3 = $row['blocked'];

    echo "<tr><td>$temp0</td>";
    echo "<td>$temp1</td>";
    echo "<td>$temp2</td>";
    echo "<td>$temp3</td>";
    echo "<td> <input type='checkbox' name='selectedEmails[]' value='$temp1'></td></tr>";
}

echo "</table>";
echo "<button type='submit' name='delete_selected' class='deletebtn'>Delete</button>";
echo "</form>";

// Handle user unblocking
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"]) && isset($_POST["txtadmin"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $adminPassword = $_POST["txtadmin"];

    // Check if the email exists in the database
    $checkEmailSql = "SELECT COUNT(*) AS count FROM users WHERE email = '$email'";
    $checkEmailResult = mysqli_query($conn, $checkEmailSql);
    $emailCount = mysqli_fetch_assoc($checkEmailResult)['count'];

    if ($emailCount == 0) {
        echo '<script>alert("Email does not exist. Cannot unblock.");</script>';
    } else {
        // Proceed with unblocking process
        // Check if the email is admin's email
        $adminEmail = "admin@gmail.com";
        if ($email === $adminEmail) {
            echo '<script>alert("Cannot unblock the admin account.");</script>';
        } else {
            // Fetch the hashed admin password from the database
            $fetchAdminPasswordSql = "SELECT password FROM admin WHERE email = '$adminEmail'";
            $fetchAdminPasswordResult = mysqli_query($conn, $fetchAdminPasswordSql);

            if ($fetchAdminPasswordResult) {
                $adminData = mysqli_fetch_assoc($fetchAdminPasswordResult);
                $hashedAdminPassword = $adminData['password'];

                // Check if the entered admin password is correct
                if (password_verify($adminPassword, $hashedAdminPassword)) {
                    // Check if the user is already unblocked
                    $checkUnblockSql = "SELECT blocked FROM users WHERE email = ?";
                    $stmt = mysqli_prepare($conn, $checkUnblockSql);
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);

                    $checkUnblockResult = mysqli_stmt_get_result($stmt);

                    $userData = mysqli_fetch_assoc($checkUnblockResult);
                    $isBlocked = $userData['blocked'];

                    if ($isBlocked == 0) {
                        echo '<script>alert("User with email ' . $email . ' is already unblocked.");</script>';
                    } else {
                        // User is not admin and not already unblocked, proceed with unblocking
                        $success = unBlock($conn, $email);

                        if ($success) {
                            echo "<script>alert('User with email ' . $email . ' has been unblocked successfully.'); document.location.href = 'unlock-user.php';</script>";
                        } else {
                            echo '<script>alert("Failed to unblock the user.");</script>';
                        }
                    }
                } else {
                    echo '<script>alert("Incorrect admin password.");</script>';
                }
            } else {
                echo '<script>alert("Error fetching admin password.");</script>';
            }
        }
    }
}


// Handle multiple deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_selected"])) {
    if (isset($_POST["selectedEmails"]) && is_array($_POST["selectedEmails"])) {
        foreach ($_POST["selectedEmails"] as $selectedEmail) {
            $deleteSql = "DELETE FROM users WHERE email = '$selectedEmail'";
            mysqli_query($conn, $deleteSql);
        }
        echo "<script>alert('Selected Users Deleted Successfully'); document.location.href = 'unlock-user.php';</script>";
    } else {
        echo "<script>alert('No users selected for deletion');</script>";
    }
}

echo "</form>";

mysqli_close($conn);

echo "</div>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE, edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNBLOCK USER</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .text1{
            position: relative;
            top: 15px;
            left: 290px;
            margin:0;
            padding: 0;
            font-size: 30px;
            color: black;
            letter-spacing: 5px;
            width: max-content;
        }

        .text2{
            margin: 0;
            padding: 0;
            margin-left: 7px;
            margin-bottom: 15px;
            font-family: Arial;
            letter-spacing: 3px;
            height: max-content;
        }

        .view{
            margin-left: 290px;
            margin-top: 20px;
            margin-bottom: 15px;
            padding: 20px;
            width: 76vw;
            overflow: auto;
            background-color: white;
            height: 65vh;
            border-radius: 5px;
        }

        .block{
            margin-left: 290px;
            margin-top: 20px;
            margin-bottom: 15px;
            padding: 20px;
            width: 76vw;
            background-color: white;
            height:11.5vh;
            border-radius: 5px;
        }
        
        .text5{
            margin-left: 7px;
            letter-spacing: 2px;
            font-family: arial; 
            font-size: 20px;
        }

        .searchtxt{
            width: 40vw;
            height: 20px;
            font-size: 14px;
            padding-left: 7px;
        }

        .btnSearch{
            margin-left: 5px;
            margin-top: 3px;
            padding: 5px 40px 5px 40px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 3px;
        }

        .btnSearch:hover{
            background-color: #ffca00;
            color: black;
        }

        .viewTable{
            text-align: center;
            margin-left: 7px;
            margin-top: 15px;
            width: 75vw;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .thView{
            background-color: black;
            color: white;
            font-family: arial;
        }

        .thView th{
            font-size: 14px;
            padding: 15px;
            font-weight: 100;
        }

        .thView td{
            font-size: 16px;
            padding: 15px;
        }

        .deletebtn{
            margin-top: 10px;
            margin-left: 134vh;
            margin-bottom: 15px;
            padding: 7px 42px 7px 42px;
            background-color: black;
            color: white;
            border: none;
        }

        .deletebtn:hover{
            background-color: #ffca00;
            color: black;
        }

        .block h2{
            margin:0;
            padding: 0;
            font-size: 25px;
            font-family: Arial, Helvetica, sans-serif;
            letter-spacing: 3px;
            width: max-content;
        }

        .block label{            
            position: relative;
            top: 20px;
            font-size: 18px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .block input{
            position: relative;
            top: 20px;
            width: 25vw;
            font-size: 16px;
        }

        .block button{
            position: relative;
            top: 20px;
            padding: 7px 10px 7px 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .report-btn{
            position: absolute;
            top: 2.5%;
            left: 87.7%;
            padding: 5px 20px 5px 20px;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            color: black;
            border: none;
            border-radius: 5px;
        }

        .report-btn:hover{
            color: black;
            background-color: #ffca00;
        }

    </style>
</head>
<body>
    <div class="block">
    <h2>UNBLOCK USER</h2>

    <!-- Unblock Form -->
    <form method="post" action="unlock-user.php">
        <label for="email">User Email: </label>
        <input type="email" id="email" name="email"  placeholder='Enter email address' required>

        <label for="txtadmin">Admin Password: </label>
        <input type="password" id="txtadmin" name="txtadmin" placeholder='Enter admin password' required>

        <button type="submit" >Unblock User</button>
    </form>
    </div>
    <script>
        function confirmDelete(email) {
            return confirm("Do you want to delete the email: " + email + "?");
        }

        function generateReport() {
            // Redirect to the report.php page
            window.location.href = 'users-report.php';
        }
    </script>
</body>
</html>