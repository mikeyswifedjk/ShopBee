<?php
require 'connection.php';

if (isset($_GET['ids']) && !empty($_GET['ids'])) {
    $selectedIds = explode(',', $_GET['ids']);

    // Get the categories of the deleted products along with the count
    $deletedProductCategories = [];
    foreach ($selectedIds as $id) {
        $categoryQuery = mysqli_query($conn, "SELECT category_id FROM product WHERE id = '$id'");
        $categoryRow = mysqli_fetch_assoc($categoryQuery);
        $category_id = $categoryRow['category_id'];
        $deletedProductCategories[$category_id] = isset($deletedProductCategories[$category_id]) ? $deletedProductCategories[$category_id] + 1 : 1;
    }

    foreach ($selectedIds as $id) {
        // Use prepared statements to prevent SQL injection
        $deleteProductQuery = "DELETE FROM product WHERE id = ?";
        $stmt = mysqli_prepare($conn, $deleteProductQuery);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            // Product deleted successfully
            mysqli_stmt_close($stmt);

            // Delete product variants from the product_variant table
            mysqli_query($conn, "DELETE FROM product_variant WHERE product_id = '$id'");
        } else {
            echo "Error deleting product with ID $id: " . mysqli_error($conn);
            // Handle the error appropriately
        }
    }

    // Update product_count in the category table based on the count of deleted products
    foreach ($deletedProductCategories as $category_id => $deletedCount) {
        $updateQuery = "UPDATE category SET product_count = product_count - $deletedCount WHERE id = '$category_id'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Category $category_id updated successfully. ";
        } else {
            echo "Error updating category $category_id: " . mysqli_error($conn);
        }
    }

    // Redirect back to the product list page or perform additional actions
    header('Location: add-product.php');
    exit();
} else {
    echo "No product IDs provided for deletion.";
}
?>