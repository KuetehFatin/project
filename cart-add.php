<?php
session_start();
include 'config.php';

if (!empty($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Check if the product is already in the cart
    if (empty($_SESSION['cart'][$product_id])) {
        // If the product is not in the cart, add it with a quantity of 1
        $_SESSION['cart'][$product_id] = 1;
    } else {
        // If the product is already in the cart, increase the quantity by 1
        $_SESSION['cart'][$product_id] += 1;
    }

    $_SESSION['message'] = 'cart add successfully';
}
 
// Redirect to the product list page
header('Location: ' . $base_url . '/product-list.php');
exit;
