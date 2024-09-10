<?php
session_start();
include 'config.php';

$product_name = trim($_POST['product_name']);
$product_quantity = isset($_POST['product_quantity']) ? (int)$_POST['product_quantity'] : 0;
$product_price = isset($_POST['product_price']) ? (float)$_POST['product_price'] : 0.0;
$image_name = $_FILES['product_image']['name'];

$image_tmp = $_FILES['product_image']['tmp_name'];
$folder = 'upload_image/';
$image_Location = $folder . $image_name;

// ID เป็นค่าว่าง = Create ID มีตัวเลข = Update
if (empty($_POST['product_id'])) {
    $query = mysqli_query($conn, "INSERT INTO product (product_name,  product_quantity, product_price, product_image) VALUES ('{$product_name}', '{$product_quantity}', '{$product_price}', '{$image_name}')") or die('query failed');
} else {
    $product_id = (int)$_POST['product_id']; // Sanitize input

    $query_product = mysqli_query($conn, "SELECT * FROM product WHERE product_id = {$product_id}");
    $result = mysqli_fetch_assoc($query_product);

    //รูปภาพเป็นค่าว่างให้ รูปภาพ = ชื่อรูปภาพในฐานข้อมูล
    if (empty($image_name)) {
        $image_name = $result['product_image'];
    } else {
        //รูปภาพไม่เป็นค่าว่าง (มีการอัปโหลดใหม่) ให้ลบรูปภาพเก่าออก
        @unlink($folder . $result['product_image']);
    }

    $query = mysqli_query($conn, "UPDATE product SET product_name='{$product_name}',  product_quantity='{$product_quantity}', product_price='{$product_price}', product_image='{$image_name}' WHERE product_id='{$product_id}'") or die('query failed');
} 

if ($query) {
    move_uploaded_file($image_tmp, $image_Location);

    $_SESSION['message'] = 'Product saved successfully';
    header('Location: ' . $base_url . '/product-Stock.php');
} else {
    $_SESSION['message'] = 'Product could not be saved!';
    header('Location: ' . $base_url . '/product-Stock.php');
}
?>



