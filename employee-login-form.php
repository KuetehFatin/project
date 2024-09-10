<?php
session_start();
include 'config.php';

$employee_email = mysqli_real_escape_string($conn, $_POST['employee_email']);
$employee_password = mysqli_real_escape_string($conn, $_POST['employee_password']);

if (!empty($employee_email) && !empty($employee_password)) {
    $query = mysqli_query($conn, "SELECT * FROM employee WHERE employee_email='{$employee_email}'");
    $row = mysqli_num_rows($query);
    
    if ($row === 1) {
        $employee = mysqli_fetch_assoc($query);

        // Check if the password from the database matches the login password
        if (password_verify($employee_password, $employee['employee_password'])) {
            $_SESSION['checklogin'] = true;
            $_SESSION['employee_id'] = $employee['employee_id'];
            $_SESSION['employee_name'] = $employee['employee_name'];
            $_SESSION['employee_tel'] = $employee['employee_tel']; 
            $_SESSION['employee_address'] = $employee['employee_address'];  

            header("Location: {$base_url}/employee.php"); // If the password is correct
            exit();
        } else {
            $_SESSION['message'] = 'Password is incorrect!';
            header("Location: {$base_url}/employee-login.php"); // If the password is incorrect
            exit();
        }
    } else {
        $_SESSION['message'] = 'Email not found!';
        header("Location: {$base_url}/employee-login.php"); // If the email is not found
        exit();
    }

} else {
    $_SESSION['message'] = 'Email and password cannot be empty!';
    header("Location: {$base_url}/employee-login.php"); // If email and password fields are empty
    exit();
}
?>