<?php
session_start();
include"config.php";

$now = date('Y-m-d H:i:s');
$query = mysqli_query($conn, "INSERT INTO sale (customer_id, sale_date, totalprice, payment_method, shipping_address) VALUES  ('{$now}', '{$_POST['customer_id,']}', '{$_POST['sale_date']}', '{$_POST['totalprice']}', '{$_POST['payment_method']}', '{$_POST['shipping_address']}')")or die('query failed');

if($query){
    $last_id = mysqli_insert_id($conn);
    foreach($_SESSION['cart'] as $productId => $productcartQty) {
        $price = $_POST['product'][$productId]['price'];
        $totalprice = $price * $productcartQty;
        
        mysqli_query ($conn, "INSERT INTO salesdetails (sale_id, product_id, price, quantity, totalprice) VALUES ('{$last_id}', '{$_POST['sale_id,']}', '{$_POST['product_id']}', '{$_POST['price']}', '{$_POST['productcartQty']}', '{$_POST['totalprice']}')")or die('query failed');

    } 
       
    unset($_SESSION['cart']);
    $_SESSION['message'] = 'checkout sale successfully';
    header('Location: ' . $base_url . '/checkout-successfully.php');
}else{
    $_SESSION['message'] = 'checkout sale failed';
    header('Location: ' . $base_url . '/checkout-successfully.php');
} 