<?php
session_start();
include('config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = (int)$_GET['id']; // Sanitize input

    // Check if product exists
    $query_product = mysqli_query($conn, "SELECT * FROM product WHERE product_id = {$product_id}");
    if (mysqli_num_rows($query_product) > 0) {
        $result = mysqli_fetch_assoc($query_product);

        // Delete the image file from the server
        if (!empty($result['product_image'])) {
            $image_path = 'upload_image/' . $result['product_image'];
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
        }

        // Delete the product from the database
        $query_delete = mysqli_query($conn, "DELETE FROM product WHERE product_id = {$product_id}");

        if ($query_delete) {
            $_SESSION['message'] = 'Product deleted successfully';
        } else {
            $_SESSION['message'] = 'Product could not be deleted!';
        }
    } else {
        $_SESSION['message'] = 'Product not found!';
    }
} else {
    $_SESSION['message'] = 'Invalid product ID!';
}

mysqli_close($conn);
header('Location: ' . $base_url . '/product-Stock.php');
exit;
?>
