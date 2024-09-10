<?php
session_start();
include("config.php");
?> 

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>employee sign</title>
    <link rel="stylesheet" href="sign-in.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <main class="form-signin w-100 m-auto">
        <form action="<?php echo htmlspecialchars($base_url . '/employee-form.php'); ?>" method="post">
            <h1 class="h3 mb-3 fw-normal">Please Sign Up</h1>

            <div class="form-floating mb-3">
                <input type="text" class="form-control my-2" name="employee_name" placeholder="Enter your name..." required>
                <label for="employee_name">Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control my-2" name="employee_email" id="employee_email" placeholder="name@example.com" required>
                <label for="employee_email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="tel" class="form-control my-2" name="employee_tel" id="employee_tel" placeholder="Tel" required>
                <label for="employee_tel">Tel</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control my-2" name="employee_address" id="employee_address" placeholder="Address" required>
                <label for="employee_address">Address</label>
            </div> 
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="employee_password" id="employee_password" placeholder="Password" required>
                <label for="employee_password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="employee_confirm_password" id="employee_confirm_password" placeholder="Confirm Password" required>
                <label for="employee_confirm_password">Confirm Password</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Sign Up</button>
            <p class="mt-5 mb-3 text-body-secondary">
            หากมีบัญชีอยู่แล้ว คลิ้กที่นี่ <a href="employee-login.php">เพื่อเข้าสู่ระบบ</a>
            </p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
