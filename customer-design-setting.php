<?php include('admin-page.php');?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "finalproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check kung may existing record na
$sqlCheck = "SELECT * FROM design_settings WHERE id = 1";
$resultCheck = $conn->query($sqlCheck);

if ($resultCheck->num_rows == 0) {

    $sqlInsert = "INSERT INTO design_settings (background_color, font_color, shop_name, logo_path, image_one_path, image_two_path, image_three_path, banner_one_path, banner_two_path, endorse_one_path, endorse_two_path, endorse_three_path)
    VALUES ('#ffffff', '#000000', 'My Shop', 'default_logo.png', 'default_image1.png', 'default_image2.png', 'default_image3.png', 'default_banner1.png', 'default_banner2.png', 'default_endorse1.png', 'default_endorse2.png', 'default_endorse3.png')";

    if ($conn->query($sqlInsert) === TRUE) {
        echo "Default record added successfully";
    } else {
        echo "Error adding default record: " . $conn->error;
    }
}

// Kumuha ng design settings mula sa database
$sqlGetSettings = "SELECT * FROM design_settings WHERE id = 1"; // Id 1 assumes there's only one record for design settings
$resultSettings = $conn->query($sqlGetSettings);

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["updateColors"])) {
        // Handle color form submission
        $bgColor = $_POST["background_color"];
        $fontColor = $_POST["font_color"];

        $sqlUpdateColors = "UPDATE design_settings SET
            background_color='$bgColor',
            font_color='$fontColor'
        WHERE id = 1";

        if ($conn->query($sqlUpdateColors) === TRUE) {
            echo "<script>alert('Updated Successfully');</script>";
        } else {
            echo "Error updating colors: " . $conn->error;
        }
    } elseif (isset($_POST["clearColors"])) {
        // Clear All Colors
        $bgColor = '#ffffff';
        $fontColor = '#000000';

        $sqlClearColors = "UPDATE design_settings SET
            background_color='$bgColor',
            font_color='$fontColor'
        WHERE id = 1";

        if ($conn->query($sqlClearColors) === TRUE) {
            echo "<script>alert('Updated Successfully');</script>";
        } else {
            echo "Error clearing colors: " . $conn->error;
        }
    } elseif (isset($_POST["updateShopDetails"])) {
        // Handle shop details form submission
        $shopName = $_POST["shop_name"];
        $logoPath = ''; // Define the default value or handle the update logic for the logo path

        // Check if a new logo file is uploaded
        if ($_FILES["logo_path"]["size"] > 0) {
            $targetDirectory = "img/";
            $logoPath = $targetDirectory . basename($_FILES["logo_path"]["name"]);
            move_uploaded_file($_FILES["logo_path"]["tmp_name"], $logoPath);
        }

        // Update shop details in the database
        $sqlUpdateShopDetails = "UPDATE design_settings SET
            shop_name='$shopName',
            logo_path='$logoPath'
        WHERE id = 1";

        if ($conn->query($sqlUpdateShopDetails) === TRUE) {
            echo "<script>alert('Updated Successfully');</script>";
        } else {
            echo "Error updating shop details: " . $conn->error;
        }
    } elseif (isset($_POST["updateImages"])) {
        // Handle images form submission
        handleFileUpload($_FILES["image_one_path"], "image_one_path");
        handleFileUpload($_FILES["image_two_path"], "image_two_path");
        handleFileUpload($_FILES["image_three_path"], "image_three_path");
    } elseif (isset($_POST["updateBanners"])) {
        // Handle banners form submission
        handleFileUpload($_FILES["banner_one_path"], "banner_one_path");
        handleFileUpload($_FILES["banner_two_path"], "banner_two_path");
    } elseif (isset($_POST["updateEndorsements"])) {
        // Handle endorsements form submission
        handleFileUpload($_FILES["endorse_one_path"], "endorse_one_path");
        handleFileUpload($_FILES["endorse_two_path"], "endorse_two_path");
        handleFileUpload($_FILES["endorse_three_path"], "endorse_three_path");
    }
}

$conn->close();

// Function to handle file upload and database update
function handleFileUpload($file, $column) {
    global $conn;

    $targetDirectory = "img/";
    $filePath = $targetDirectory . basename($file["name"]);

    move_uploaded_file($file["tmp_name"], $filePath);

    // Update file path in the database
    $sqlUpdateFile = "UPDATE design_settings SET
        $column='$filePath'
    WHERE id = 1";

    if ($conn->query($sqlUpdateFile) === TRUE) {
        echo "<script>alert(ucfirst($column) . 'Updated Successfully');</script>";
    } else {
        echo "Error updating " . $column . ": " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Landing Page</title>
    <style>
            body{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                background-color: <?php echo $bgColor; ?>;
                color: <?php echo $fontColor; ?>;
                overflow-x: hidden;
                transition: background-color 0.5s ease;
            }

            #setting-panel{
                position: relative;
                top: 20px;
                left: 290px;
                display: grid;
                grid-template-columns: auto auto;
                gap: 15px;
                width: 78.7vw;
            }

            .all{
                display: grid;
                grid-template-rows: auto auto;
                gap: 15px;
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

            .logo-container, 
            .image-container, 
            .banner-container{
                margin-top: 20px;
            }

            .logo, 
            .imageOne, 
            .imageTwo,
            .imageThree,
            .bannerOne,
            .bannerTwo,
            .endorseOne,
            .endorseTwo,
            .endorseThree {
                max-width: 150px;
                max-height: 150px;
                transition: all 0.5s ease;
            }

            .colors{
                padding: 0;
                padding: 20px;
                border-radius: 5px;
                font-family: Arial, Helvetica, sans-serif;
                height: 19vh;
                overflow-y: hidden;
                background-color: white;
                width: 36vw;
            }

            .colors h2{
                padding: 0;
                margin: 0;
                margin-left: 10px;
                margin-bottom: 10px;
                letter-spacing: 3px;
            }

            .colors label{
                font-size: 20px;
                margin-left: 10px;
                letter-spacing: 3px;
            }

            .colors input[type="color"]{
                width: 5vw;
            }

            .colors button{
                position: relative;
                top: 8px;
                left: 70%;
                padding: 5px 20px 5px 20px;
                background-color: black;
                color: white;
                border: none;
                border-radius: 5px;
            }

            .colors button:hover{
                background-color: #ffca00;
                color: black;
            }

            .colors input[type="text"]{
                padding-left: 7px;
                width: 15vw;
                font-size: 16px;
            }

            .other{
                margin:0;
                padding: 0;
                padding: 20px;
                border-radius: 5px;
                font-family: Arial, Helvetica, sans-serif;
                height: 31vh;
                overflow:auto;
                background-color: white;
                width: 36vw;
            }

            .other h2{
                padding: 0;
                margin: 0;
                margin-left: 10px;
                margin-bottom: 20px;
                letter-spacing: 3px;
                letter-spacing: 3px;
            }

            .other img{
                margin: 0;
                padding: 0;
                padding: 10px;
                margin-left: 15px;
                object-fit: contain;
                width: 150px;
                height: 150px;
                background-color: #f5f5f5;
                border: 1px solid black;
            }

            .other label{
                font-size: 20px;
                margin-left: 10px;
                letter-spacing: 3px;
            }

            .other button{
                position: relative;
                left: 75%;
                padding: 5px 20px 5px 20px;
                background-color: black;
                margin-right: 15px;
                color: white;
                border: none;
                border-radius: 5px;
            }

            .other button:hover{
                background-color: #ffca00;
                color: black;
            }

            .other input[type="text"]{
                padding-left: 7px;
                width: 15vw;
                font-size: 16px;
            }
            
            .slider{
                margin:0;
                padding: 0;
                padding: 20px;
                border-radius: 5px;
                font-family: Arial, Helvetica, sans-serif;
                height: 58vh;
                overflow:auto;
                background-color: white;
                width: 36vw;
            }

            .slider h2{
                padding: 0;
                margin: 0;
                margin-left: 10px;
                margin-bottom: 20px;
                letter-spacing: 3px;
                letter-spacing: 3px;
            }

            .slider span{
                margin: 0;
                padding: 0;
                margin-left: 10px;
            }

            .slider label{
                font-size: 20px;
                margin-left: 10px;
                letter-spacing: 3px;
            }

            .slider img{
                margin: 0;
                padding: 0;
                padding: 10px;
                margin-left: 15px;
                object-fit: contain;
                width: 150px;
                height: 150px;
                background-color: #f5f5f5;
                border: 1px solid black;
            }

            .slider input[type="file"]{
                padding: 0 7px 0 7px;
                width: 15vw;
                font-size: 16px;
            }

            .slider button{
                position: relative;
                left: 75%;
                padding: 5px 20px 5px 20px;
                background-color: black;
                margin-right: 15px;
                color: white;
                border: none;
                border-radius: 5px;
            }

            .slider button:hover{
                background-color: #ffca00;
                color: black;
            }

            .banner{
                margin:0;
                padding: 0;
                padding: 20px;
                border-radius: 5px;
                font-family: Arial, Helvetica, sans-serif;
                height: 58vh;
                overflow:auto;
                background-color: white;
                width: 36vw;
            }

            .banner h2{
                padding: 0;
                margin: 0;
                margin-left: 10px;
                margin-bottom: 20px;
                letter-spacing: 3px;
                letter-spacing: 3px;
            }

            .banner span{
                margin: 0;
                padding: 0;
                margin-left: 10px;
            }

            .banner label{
                font-size: 20px;
                margin-left: 10px;
                letter-spacing: 3px;
            }

            .banner img{
                margin: 0;
                padding: 0;
                padding: 10px;
                margin-left: 15px;
                object-fit: contain;
                width: 150px;
                height: 150px;
                background-color: #f5f5f5;
                border: 1px solid black;
            }

            .banner input[type="file"]{
                padding: 0 7px 0 7px;
                width: 15vw;
                font-size: 16px;
            }

            .banner button{
                position: relative;
                left: 72%;
                padding: 5px 20px 5px 20px;
                background-color: black;
                margin-right: 15px;
                color: white;
                border: none;
                border-radius: 5px;
            }

            .banner button:hover{
                background-color: #ffca00;
                color: black;
            }

            .endorse{
                margin:0;
                margin-bottom: 15px;
                padding: 0;
                padding: 20px;
                border-radius: 5px;
                font-family: Arial, Helvetica, sans-serif;
                height: 58vh;
                overflow:auto;
                background-color: white;
                width: 36vw;
            }

            .endorse h2{
                padding: 0;
                margin: 0;
                margin-left: 10px;
                margin-bottom: 20px;
                letter-spacing: 3px;
                letter-spacing: 3px;
            }

            .endorse span{
                margin: 0;
                padding: 0;
                margin-left: 10px;
            }

            .endorse label{
                font-size: 20px;
                margin-left: 10px;
                letter-spacing: 3px;
            }

            .endorse img{
                margin: 0;
                padding: 0;
                padding: 10px;
                margin-left: 15px;
                object-fit: contain;
                width: 150px;
                height: 150px;
                background-color: #f5f5f5;
                border: 1px solid black;
            }

            .endorse input[type="file"]{
                padding: 0 7px 0 7px;
                width: 15vw;
                font-size: 16px;
            }

            .endorse button{
                position: relative;
                left: 65%;
                padding: 5px 20px 5px 20px;
                background-color: black;
                margin-right: 15px;
                color: white;
                border: none;
                border-radius: 5px;
            }

            .endorse button:hover{
                background-color: #ffca00;
                color: black;
            }

            .logo-container span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .image-container1 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .image-container2 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .image-container3 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }
            
            .banner-container1 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .banner-container2 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .endorse-container1 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .endorse-container2 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }

            .endorse-container3 span{
                position: relative;
                bottom: 120px;
                left: 220px;
            }
        </style>
    </style>
</head>
<body>
<h1 class="text1"> CUSTOMER DESIGN SETTING </h1>
<div id="setting-panel">
    <div class="all">
        <div class="colors">
        <h2> COLOR SETTINGS </h2>
        <!-- Form for updating colors -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="colorForm" enctype="multipart/form-data">
            <label for="background_color">Background Color:</label>
            <input type="color" id="background_color" name="background_color" value="<?php echo $bgColor; ?>" required /> <br> <br>

            <label for="font_color">Font Color:</label>
            <input type="color" id="font_color" name="font_color" value="<?php echo $fontColor; ?>" required /> <br>

            <button type="submit" name="updateColors">SAVE</button>
            <button type="button" onclick="clearColors()">RESET</button>
        </form>
    </div>

    <!-- Form for updating shop details -->
    <div class="other">  
    <h2> LOGO & SHOP NAME </h2>  
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="shopDetailsForm" enctype="multipart/form-data">
            <label for="shop_name">Shop Name:</label>
            <input type="text" id="shop_name" name="shop_name" value="<?php echo $shopName; ?>" required /> <br> <br>

            <label for="logo_path">Logo Path:</label>
            <input type="file" id="logo_path" name="logo_path" required /> <br>

            <div class="logo-container">
                <img src="img/<?php echo basename($logoPath); ?>" alt="Logo"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div>
            <button type="submit" name="updateShopDetails">Update Details</button>
        </form>
    </div> <!-- other -->
    </div> <!-- all -->

    <!-- Form for updating images -->
    <div class="slider">
        <h2> IMAGE SLIDER </h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="imagesForm" enctype="multipart/form-data">
            <label for="image_one">Image One:</label>
            <input type="file" id="image_one_path" name="image_one_path" required /> <br> <br>

            <div class="image-container1">
                <img src="img/<?php echo basename($imageOnePath); ?>" alt="Image One"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span>
            </div> <br>

            <label for="image_two">Image Two:</label>
            <input type="file" id="image_two_path" name="image_two_path" required /> <br> <br>

            <div class="image-container2">
                <img src="img/<?php echo basename($imageTwoPath); ?>" alt="Image Two"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span>
            </div> <br>

            <label for="image_three">Image Three:</label>
            <input type="file" id="image_three_path" name="image_three_path" required /> <br> <br>

            <div class="image-container3">
                <img src="img/<?php echo basename($imageThreePath); ?>" alt="Image Three"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div>
            <button type="submit" name="updateImages">Update Slider</button>
        </form>
    </div> 

    <!-- Form for updating banners -->
    <div class="banner">
    <h2> BANNER </h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="bannersForm" enctype="multipart/form-data">
            <label for="banner_one">Banner One:</label>
            <input type="file" id="banner_one_path" name="banner_one_path" required /> <br> <br>

            <div class="banner-container1">
                <img src="img/<?php echo basename($bannerOnePath); ?>" alt="Banner One"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div> <br>

            <label for="banner_two">Banner Two:</label>
            <input type="file" id="banner_two_path" name="banner_two_path" required /> <br> <br>

            <div class="banner-container2">
                <img src="img/<?php echo basename($bannerTwoPath); ?>" alt="Banner Two"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div>
            <button type="submit" name="updateBanners">Update Banners</button>
        </form>
    </div>

    <!-- Form for updating endorsements -->
    <div class="endorse">
    <h2> ENDORSE PRODUCT </h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="endorsementsForm" enctype="multipart/form-data">
            <label for="endorse_one">Endorse One:</label>
            <input type="file" id="endorse_one_path" name="endorse_one_path" required /> <br> <br>

            <div class="endorse-container1">
                <img src="img/<?php echo basename($endorseOnePath); ?>" alt="Endorse One"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div> <br>

            <label for="endorse_two">Endorse Two:</label>
            <input type="file" id="endorse_two_path" name="endorse_two_path" required /> <br> <br>

            <div class="endorse-container2">
                <img src="img/<?php echo basename($endorseTwoPath); ?>" alt="Endorse Two"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div> <br>

            <label for="endorse_three">Endorse Three:</label>
            <input type="file" id="endorse_three_path" name="endorse_three_path" required /> <br> <br>

            <div class="endorse-container3">
                <img src="img/<?php echo basename($endorseThreePath); ?>" alt="Endorse Three"> <br>
                <span> File Size: Maximum 70kb </span> <br>
                <span> File Extensions: .jpg, .jpeg, .png </span> 
            </div> <br>

            <button type="submit" name="updateEndorsements">Update Endorsements</button>
        </form>
    </div>

</div> <!-- settings panel -->

    <script>
        function clearColors() {
            // Set default values for background and font colors
            document.getElementById('background_color').value = '#f5f5f5';
            document.getElementById('font_color').value = '#000000';
        }
    </script>

</body>
</html>