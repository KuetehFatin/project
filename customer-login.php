<?php
session_start();
include("config.php");
?>
 
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>customer login</title>
    <link rel="stylesheet" href="sign-in.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .alert-position {
            position: absolute;
            top: 10px;
            width: 100%;
        }  
        .full-height {
            min-height: 100vh;
        } 
    </style>
</head>
<body> 
<div class="container full-height position-relative">
    <?php if(!empty($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show alert-position my-5" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <div class="row d-flex align-items-center justify-content-center full-height">
        <div class="col-sm-4">
            <main class="form-signin w-100 m-auto">
                <form action="<?php echo $base_url . '/login-form.php'; ?>" method="post">
                    <h1 class="h3 mb-3 fw-normal">Please log in</h1>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="customer_email" placeholder="name@example.com" required>
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" name="customer_password" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Log In</button>
                    <p class="mt-1 mb-3 text-body-secondary">
                        สำหรับลูกค้าที่ยังไม่ลงทะเบียน คลิ๊กที่นี่เพื่อ <a href="customer-sign.php">ลงทะเบียน</a>
                    </p>
                </form>
            </main>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
