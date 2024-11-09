<?php include('admin-page.php');?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve product details using prepared statements
    $selectProductQuery = "SELECT * FROM product WHERE id = ?";
    $stmtProduct = mysqli_prepare($conn, $selectProductQuery);
    mysqli_stmt_bind_param($stmtProduct, 'i', $id);
    if (mysqli_stmt_execute($stmtProduct)) {
        $result = mysqli_stmt_get_result($stmtProduct);
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching product details: " . mysqli_stmt_error($stmtProduct);
    }
    mysqli_stmt_close($stmtProduct);

    // Check if a product with the specified ID exists
    if ($product) {
        // Retrieve product variants
        $variantResult = mysqli_query($conn, "SELECT * FROM product_variant WHERE product_id = $id");
        $variants = mysqli_fetch_all($variantResult, MYSQLI_ASSOC);

        if (isset($_POST['submit'])) {
            // Handle the form submission
            $newName = mysqli_real_escape_string($conn, $_POST['name']);
            $newCategory = mysqli_real_escape_string($conn, $_POST['category']);

            // Check if a new image is being uploaded
            if (!empty($_FILES['image']['name'])) {
                $newImage = $_FILES['image']['name'];

                // Specify the directory where you want to save the uploaded image
                $uploadDir = 'img/';

                // Get the temporary file name
                $tempName = $_FILES['image']['tmp_name'];

                // Create a unique name for the image
                $newImageName = time() . '_' . $newImage;

                // Move the uploaded image to the destination directory
                if (move_uploaded_file($tempName, $uploadDir . $newImageName)) {
                    // Update the product details, including the new image path
                    $updateProductQuery = "UPDATE product SET name = ?, category = ?, image = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $updateProductQuery);
                    mysqli_stmt_bind_param($stmt, 'sssi', $newName, $newCategory, $newImageName, $id);
                    mysqli_stmt_execute($stmt);
                }
            } else {
                // No new image uploaded, update product details without changing the image
                $updateProductQuery = "UPDATE product SET name = ?, category = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $updateProductQuery);
                mysqli_stmt_bind_param($stmt, 'ssi', $newName, $newCategory, $id);
                mysqli_stmt_execute($stmt);
            }

            // Handle deletion of existing product variants
            if (isset($_POST['deleted_variant_ids'])) {
                $deletedVariantIds = $_POST['deleted_variant_ids'];

                foreach ($deletedVariantIds as $variantId) {
                    $deleteVariantQuery = "DELETE FROM product_variant WHERE id = ?";
                    $stmtDeleteVariant = mysqli_prepare($conn, $deleteVariantQuery);
                    mysqli_stmt_bind_param($stmtDeleteVariant, 'i', $variantId);

                    if (mysqli_stmt_execute($stmtDeleteVariant)) {
                    } else {
                        echo '<script>alert("Error deleting variant: ' . mysqli_error($conn) . '");</script>';
                    }

                    mysqli_stmt_close($stmtDeleteVariant);
                }
            }

            // Use a transaction to ensure consistency when updating existing variants
            mysqli_begin_transaction($conn);

            // Update existing product variants
            if (isset($_POST['variant_names']) && isset($_POST['variant_prices']) && isset($_POST['variant_qtys'])) {
                $existingVariantNames = $_POST['variant_names'];
                $existingVariantPrices = $_POST['variant_prices'];
                $existingVariantQtys = $_POST['variant_qtys'];
                $existingVariantIds = $_POST['variant_ids']; //hidden fields.

                try {
                    for ($i = 0; $i < count($existingVariantIds); $i++) {
                        $existingVariantName = mysqli_real_escape_string($conn, $existingVariantNames[$i]);
                        $existingPrice = (float)$existingVariantPrices[$i];
                        $existingQty = (int)$existingVariantQtys[$i];
                        $variantId = $existingVariantIds[$i];

                        $stmtUpdateVariant = mysqli_prepare($conn, "UPDATE product_variant SET variant = ?, price = ?, qty = ? WHERE id = ?");
                        mysqli_stmt_bind_param($stmtUpdateVariant, 'sddi', $existingVariantName, $existingPrice, $existingQty, $variantId);

                        if (!mysqli_stmt_execute($stmtUpdateVariant)) {
                            throw new Exception("Error updating variant: " . mysqli_error($conn));
                        }

                        mysqli_stmt_close($stmtUpdateVariant);
                    }
                    mysqli_commit($conn);

                } catch (Exception $e) {
                    // Rollback the transaction if any error occurs
                    mysqli_rollback($conn);
                    echo $e->getMessage();
                }
            }

            // Handle adding new variants
            if (isset($_POST['new_variant_names'])) {
                $newVariantNames = $_POST['new_variant_names'];
                $newVariantPrices = $_POST['new_variant_prices'];
                $newVariantQtys = $_POST['new_variant_qtys'];

                for ($i = 0; $i < count($newVariantNames); $i++) {
                    $newVariantName = mysqli_real_escape_string($conn, $newVariantNames[$i]);
                    $newPrice = (float)$newVariantPrices[$i];
                    $newQty = (int)$newVariantQtys[$i];

                    // Insert the new variant into the database
                    $insertNewVariantQuery = "INSERT INTO product_variant (product_id, variant, price, qty) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $insertNewVariantQuery);
                    mysqli_stmt_bind_param($stmt, 'issd', $id, $newVariantName, $newPrice, $newQty);
                    mysqli_stmt_execute($stmt);

                    // Append the new variant to the variant array
                    $variants[] = [
                        'variant' => $newVariantName,
                        'price' => $newPrice,
                        'qty' => $newQty,
                        'id' => mysqli_insert_id($conn),
                    ];
                }
            }

            // Commit the transaction
            mysqli_commit($conn);

            // Calculate the total quantity and price range for all variants, including the newly added ones
            $totalQty = 0;
            $minPrice = PHP_FLOAT_MAX;
            $maxPrice = -1;
            $variantString = '';

            foreach ($variants as $variant) {
                $price = $variant['price'];
                if ($price < $minPrice) {
                    $minPrice = $price;
                }
                if ($price > $maxPrice) {
                    $maxPrice = $price;
                }

                $variantString .= $variant['variant'] . ': ' . $variant['qty'] . ' ';
                $totalQty += (int)$variant['qty'];
            }

            $priceRange = ($minPrice != PHP_FLOAT_MAX) ? $minPrice . '-' . $maxPrice : "N/A";

            // Update the product table with the updated total quantity, price range, and variant
            $updateProductQuery = "UPDATE product SET qty = ?, price = ?, variant = ? WHERE id = ?";
            $stmtUpdateProduct = mysqli_prepare($conn, $updateProductQuery);
            mysqli_stmt_bind_param($stmtUpdateProduct, 'sssi', $totalQty, $priceRange, $variantString, $id);

            if (mysqli_stmt_execute($stmtUpdateProduct)) {
                echo "<script>alert('Product Details Updated Successfully'); document.location.href = 'add-product.php';</script>";
            } else {
                echo '<script>alert("Error updating product details: ' . mysqli_error($conn) . '");</script>';
            }            
            mysqli_stmt_close($stmtUpdateProduct);
        }
    } else {
        echo '<script>alert("Product not found with ID: ' . $id . '");</script>';
    }
} else {
    echo '<script>alert("Product ID not provided");</script>';
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>EDIT PRODUCT</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }
        
        .all{
            margin-top: 15px;
            margin-left: 20%;
            background-color: white;
            width: 78.7vw;
            height: 88vh;
            overflow: auto;
            border-radius: 5px;
        }

        h1{
            margin: 0;
            margin-top: 20px;
            margin-left: 20%;
            letter-spacing: 5px;
        }

        .label {
            position: relative;
            top: 30px;
            left: 30px;
            font-size: 22px;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
        }
        
        .input{
            position: relative;
            top: 30px;
            left: 30px;
            font-size: 22px;
            font-family: Arial, Helvetica, sans-serif;
            width: 25vw;
        }

        .cate {
            position: relative;
            top: 29%;
            left: 30px;
            font-size: 22px;
            font-weight: bold ;
            font-family: Arial, Helvetica, sans-serif;
        }
        #category{
            position: relative;
            top: 28.9%;
            left: 32px;
            padding-right: 20px;
            padding-left: 20px;
            font-size: 20px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .prod-image{
            position: absolute;
            top: 13.5%;
            left: 68%;
            height: 420px;
            width: 420px;
        }

        h2{
            position: relative;
            top: 29%;
            left: 30px;
            font-size: 22px;
            font-weight: bold ;
            font-family: Arial, Helvetica, sans-serif;
        }

        th{
            position: relative;
            top: 29%;
            left: 30px;
            font-size: 22px;
            font-weight: bold ;
            font-family: Arial, Helvetica, sans-serif;
        }

        .inputTD input[type="text"]{
            position: relative;
            top: 29%;
            left: 30px;
            font-size: 16px;
            padding: 5px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .del{
            position: relative;
            top: 29%;
            left: 30px;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
            padding: 8px 10px 8px 10px;
            border: none;
            background-color: black;
            color: white;
            border-radius: 5px;
        }

        .del:hover{
            background-color: #ffca00;
            color:black;
        }

        .add{
            position: relative;
            bottom: 12px;
            left: 33px;
            padding: 7px 20px 7px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .edit{
            position: relative;
            bottom: 12px;
            left: 33px;
            padding: 7px 20px 7px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1> UPDATE PRODUCT INFORMATION </h1>
    <div class="all">
    <form class="" action="" method="post" name="product_form" autocomplete="off" enctype="multipart/form-data">
        <label for="name" class="label">PRODUCT NAME: </label>
        <input type="text" class="input" name="name" id="name" required autocomplete="name" value="<?php echo $product['name']; ?>"> <br> <br>
        <label for="image" class="label">PRODUCT IMAGE: </label>
        <input type="file" class="input" name="image" id="image" accept=".jpg, .jpeg, .png" autocomplete="file" value="">
        <br><br>
        <?php
        // Display the existing image if it exists
        if (!empty($product['image'])) {
            echo '<img class="prod-image" src="img/' . $product['image'] . '" title="' . $product['image'] . '"><br>';
        }
        ?>
        <br>

        <label for="category" class="cate" >CATEGORY: </label>
        <select name="category" id="category" required>
            <?php
            $categoryQuery = mysqli_query($conn, "SELECT DISTINCT category FROM category");
            while ($categoryRow = mysqli_fetch_assoc($categoryQuery)) {
                $selected = ($categoryRow['category'] == $product['category']) ? "selected" : "";
                echo "<option value='" . $categoryRow['category'] . "' $selected>" . $categoryRow['category'] . "</option>";
            }
            ?>
        </select>
        <br> <br>

        <h2>PRODUCT VARIANT</h2>
        <table>
            <thead>
                <tr>
                    <th>Variant</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display existing product variants
                foreach ($variants as $index => $variant) {
                    echo '<tr data-variant-id="' . $variant['id'] . '">';
                    echo '<td class="inputTD"><input type="text" name="variant_names[' . $index . ']" required autocomplete="text" value="' . $variant['variant'] . '"></td>';
                    echo '<td class="inputTD"><input type="text" name="variant_prices[' . $index . ']" required autocomplete="number" value="' . $variant['price'] . '"></td>';
                    echo '<td class="inputTD"><input type="text" name="variant_qtys[' . $index . ']" required autocomplete="number" value="' . $variant['qty'] . '"></td>';
                    echo '<td><button class="del" type="button" onclick="confirmDeleteVariant(this)">DELETE </button></td>';
                    // Add hidden input fields for existing variant IDs
                    echo '<input type="hidden" name="variant_ids[]" value="' . $variant['id'] . '">';
                    echo '</tr>';
                }                
                ?>

                <!-- Add a new row for adding new variants -->
                <tr>
                    <td class="inputTD"><input type="text" name="new_variant_names[]" autocomplete="text"></td>
                    <td class="inputTD"><input type="text" name="new_variant_prices[]" autocomplete="number"></td>
                    <td class="inputTD"><input type="text" name="new_variant_qtys[]" autocomplete="number"></td>
                    <td><button class="del" type="button" onclick="deleteRow(this.parentNode.parentNode)">DELETE</button></td>
                </tr>
            </tbody>
        </table>
        <br>

        <input type="hidden" name="deleted_variant_ids[]" id="deleted_variant_ids" value="">

        <button class="add" type="button" onclick="addVariant()">ADD VARIANT</button>

        <button class="edit" type="submit" name="submit">UPDATE</button>
    </form> <br>
    </div>

    <script>

    function deleteRow(row) {
        row.parentNode.removeChild(row);
    }

    function addVariant() {
        var newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="inputTD"><input type="text" name="new_variant_names[]" autocomplete="text"></td>
            <td class="inputTD"><input type="text" name="new_variant_prices[]" autocomplete="number"></td>
            <td class="inputTD"><input type="text" name="new_variant_qtys[]" autocomplete="number"></td>
            <td><button class="del" type="button" onclick="deleteRow(this.parentNode.parentNode)">DELETE</button></td>
        `;
        document.querySelector('table tbody').appendChild(newRow);
    }

    function confirmDeleteVariant(button) {
        var row = button.parentNode.parentNode;
        var variantId = row.getAttribute('data-variant-id');
        var confirmation = confirm("Are you sure you want to delete this variant? It will automatically be deleted after you click the update button.");
        if (confirmation) {
            // Set the variant ID to the hidden input field
            document.getElementById('deleted_variant_ids').value = variantId;
            document.forms['product_form'].submit();
        } else {
            // Clear the hidden input field value if the user cancels the confirmation
            document.getElementById('deleted_variant_ids').value = '';
        }
    }
</script>
</body>
</html>