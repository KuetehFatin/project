<?php
session_start();
include("config.php");

// ดึงข้อมูลสินค้าทั้งหมด
$query = mysqli_query($conn, "SELECT * FROM product");
$rows = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลิตภัณฑ์</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.min.css" rel="stylesheet">

    <!-- Custom styles for the size dropdown -->
    <style>
        .size-select {
            width: 100%;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .card-title, .card-text {
            margin-bottom: 8px;
        }
    </style>
</head>

<body class="bg-body-tertiary">
    <?php include 'include/menu.php'; ?>
    <div class="container" style="margin-top: 30px;">
        <?php if (!empty($_SESSION['message'])) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h4>ผลิตภัณฑ์</h4>
        <div class="d-flex justify-content-center row row-cols-2 row-cols-md-5 g-4">
            <?php if ($rows > 0) : ?>
                <?php while ($product = mysqli_fetch_assoc($query)) : ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if (!empty($product['product_image'])) : ?>
                                <img src="<?php echo $base_url; ?>/upload_image/<?php echo $product['product_image']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                            <?php else : ?>
                                <img src="<?php echo $base_url; ?>/assets/no_image.png" class="card-img-top" alt="ไม่มีรูปภาพ">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                                <td>
                                <p class="card-text fw-bold text-muted">฿<?php echo number_format($product['product_price'], 2); ?></p>
                                </td>
                                
                                <a href="<?php echo $base_url; ?>/cart-add.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-primary w-100"><i class="fa-solid fa-cart-plus"></i> เพิ่มในรถเข็น</a>
                            </div>
                        </div>        
                    </div>
                <?php endwhile; ?>
            <?php else : ?> 
                <div class="col-4">
                    <h4 class="text-danger">ไม่มีรายการสินค้า</h4>  
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
