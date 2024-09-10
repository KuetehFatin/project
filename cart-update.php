<?php
session_start();
include 'config.php';

foreach($_SESSION['cart'] as $productId => $productcartQty) {
    $_SESSION['cart'][$productId] = $_POST['product'][$productId]['quantity'];
}

 $_SESSION['message'] = 'cart update successfully';
header('Location: ' . $base_url . '/cart.php');

 