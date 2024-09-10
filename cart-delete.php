<?php
session_start();
include 'config.php';

if (!empty($_GET['id'])) { // Change 'product_id' to 'id'
    // Unset the item from the cart array
    unset($_SESSION['cart'][$_GET['id']]); // Change 'product_id' to 'id'
    
    // Set a success message
    $_SESSION['message'] = 'Cart item deleted successfully';
}

// Redirect to the cart page
header('Location: ' . $base_url . '/cart.php');
exit();
 
