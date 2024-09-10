<?php
session_start();
include 'config.php';

// Ensure the database connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve and sanitize form data
$customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
$customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
$customer_tel = mysqli_real_escape_string($conn, $_POST['customer_tel']);
$customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
$customer_password = mysqli_real_escape_string($conn, $_POST['customer_password']);
$customer_confirm_password = mysqli_real_escape_string($conn, $_POST['customer_confirm_password']);

// ตรวจสอบว่ากรอกข้อมูลครบทุกช่องแล้วหรือไม่
if (!empty($customer_name) && !empty($customer_email) && !empty($customer_tel) && !empty($customer_address) && !empty($customer_password) && !empty($customer_confirm_password)) {
    $hashed_password = password_hash($customer_password, PASSWORD_DEFAULT); 
    // Insert data into the database
    $query = mysqli_query($conn, "INSERT INTO customers (customer_name, customer_email, customer_tel, customer_address, customer_password)
    VALUES ('{$customer_name}', '{$customer_email}', '{$customer_tel}', '{$customer_address}', '{$hashed_password}')") or die('Query failed: ' . mysqli_error($conn));

    // ตรวจสอบว่าแบบสอบถามสำเร็จหรือไม่
    if ($query) {
        $_SESSION['message'] = 'Sign Complete!';
        header("Location: " . $base_url . "/customer-login.php");
    } else {
        $_SESSION['message'] = 'Sign could not be saved!';
        header("Location: " . $base_url . "/customer-sign.php");
    }
} else {
    $_SESSION['message'] = 'Input is required!';
    header("Location: " . $base_url . "/customer-sign.php");
}
