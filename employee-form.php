<?php
session_start();
include 'config.php';

$employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
$employee_email = mysqli_real_escape_string($conn, $_POST['employee_email']);
$employee_tel = mysqli_real_escape_string($conn, $_POST['employee_tel']);
$employee_address = mysqli_real_escape_string($conn, $_POST['employee_address']);
$employee_password = mysqli_real_escape_string($conn, $_POST['employee_password']);
$employee_confirm_password = mysqli_real_escape_string($conn, $_POST['employee_confirm_password']);

if (!empty($employee_name) && !empty($employee_email) && !empty($employee_tel) && !empty($employee_address) && !empty($employee_password) && !empty($employee_confirm_password)) {
    // Ensure passwords match
    if ($employee_password !== $employee_confirm_password) {
        $_SESSION['message'] = 'Passwords do not match!';
        header("Location: " . $base_url . "/employee-sign.php");
        exit();
    }

    $hashed_password = password_hash($employee_password, PASSWORD_DEFAULT); 

    $query = mysqli_query($conn, "INSERT INTO employee (employee_name, employee_email, employee_tel, employee_address, employee_password)
    VALUES ('{$employee_name}', '{$employee_email}', '{$employee_tel}', '{$employee_address}' , '{$hashed_password}')") or die('query failed!');

    if ($query) {
        $_SESSION['message'] = 'Sign Complete!';
        header("Location: " . $base_url . "/employee-login.php");
    } else {
        $_SESSION['message'] = 'Sign could not be saved!';
        header("Location: " . $base_url . "/employee-sign.php");
    }
} else {
    $_SESSION['message'] = 'Input is required!';
    header("Location: " . $base_url . "/employee-sign.php");
}
exit();
?>
