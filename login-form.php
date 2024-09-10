<?php
session_start();
include 'config.php';

$customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
$customer_password = mysqli_real_escape_string($conn, $_POST['customer_password']);

if (!empty($customer_email) && !empty($customer_password)) {
    $query = mysqli_query($conn, "SELECT * FROM customers WHERE customer_email='{$customer_email}'");
    $row = mysqli_num_rows($query);
    
    if ($row === 1) {
        $customer = mysqli_fetch_assoc($query);

        // Check if the password from the database matches the login password
        if (password_verify($customer_password, $customer['customer_password'])) {
            $_SESSION['checklogin'] = true;
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_name'] = $customer['customer_name'];
            $_SESSION['customer_tel'] = $customer['customer_tel']; 
            $_SESSION['customer_address'] = $customer['customer_address'];  

            header("Location: {$base_url}/customer.php"); // If the password is correct
            exit();
        } else {
            $_SESSION['message'] = 'Password is incorrect!';
            header("Location: {$base_url}/customer-login.php"); // If the password is incorrect
            exit();
        }
    } else {
        $_SESSION['message'] = 'Email not found!';
        header("Location: {$base_url}/customer-login.php"); // If the email is not found
        exit();
    }

} else {
    $_SESSION['message'] = 'Email and password cannot be empty!';
    header("Location: {$base_url}/customer-login.php"); // If email and password fields are empty
    exit();
}
?>