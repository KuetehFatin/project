<?php
session_start();
include("config.php");

// product all
$query = mysqli_query($conn, "SELECT * FROM product");
$rows = mysqli_num_rows($query);

// var product form
$result = [
    'product_id' => '',
    'product_name' => '',
    'product_quantity' => '',
    'product_price' => '',
    'product_image' => '',
];

// product select edit
if (!empty($_GET['id'])) {
    $query_product = mysqli_query($conn, "SELECT * FROM product WHERE product_id = {$_GET['id']}");
    $row_product = mysqli_num_rows($query_product);

    if ($row_product == 0) {
        header('Location:' . $base_url . '/index.php');
    }

    $result = mysqli_fetch_assoc($query_product);
}

// Check if cancel was clicked
if (isset($_GET['cancel']) && $_GET['cancel'] == 'true') {
    $result = [
        'product_id' => '',
        'product_name' => '',
        'product_quantity' => '',
        'product_price' => '',
        'product_image' => '',
    ];
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.min.css" rel="stylesheet">
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

        <h4>Home - Manage Product</h4>
        <div class="row g-5">
            <div class="col-md-8 col-sm-12">
                <form action="<?php echo $base_url; ?>/product-form.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo $result['product_id']; ?>"> <!-- ID เป็นค่าว่าง = Create ID มีตัวเลข = Update-->
                    <div class="row g-3 md-3">
                        <div class="col-sm-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" value="<?php echo $result['product_name']; ?>">
                        </div>

            
                        <div class="col-sm-6">
                            <label class="form-label">Product quantity</label>
                            <input type="text" name="product_quantity" class="form-control" value="<?php echo $result['product_quantity']; ?>">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Product Price</label>
                            <input type="text" name="product_price" class="form-control" value="<?php echo $result['product_price']; ?>">
                        </div>

                        <div class="col-sm-6">
                            <?php if (!empty($result['product_id'])) : ?> <!-- แสดงรูปภาพ -->
                                <div>
                                    <img src="<?php echo $base_url; ?>/upload_image/<?php echo $result['product_image']; ?>" width="100" alt="product Image">
                                </div>
                            <?php endif; ?>
                            <label for="formFile" class="form-label">Image</label>
                            <input type="file" name="product_image" class="form-control" accept="image/png, image/jpg, image/jpeg">
                        </div>

                        <div class="col-12">
                            <?php if (empty($result['product_id'])) : ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-regular fa-floppy-disk m-1"></i>Create</button>
                            <?php else : ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-regular fa-floppy-disk m-1"></i>Update</button>
                            <?php endif; ?>
                            <a role="button" class="btn btn-secondary btn-sm" href="<?php echo $base_url; ?>/index.php?cancel=true" type="button"><i class="fa-solid fa-rectangle-xmark m-1"></i>Cancel</a>
                        </div>
                        <hr class="my-4">
                    </div>
                </form>
            </div>
        </div>

        <!-- ตารางแสดงข้อมูลสินค้า -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered border-info">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Image</th>
                            <th>Product Name</th>
                            <th style="width: 100px;">Quantity</th>
                            <th style="width: 200px;">Price</th>
                            <th style="width: 200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rows > 0) : ?>
                            <?php while ($product = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($product['product_image'])) : ?>
                                            <img src="<?php echo $base_url; ?>/upload_image/<?php echo $product['product_image']; ?>" width="100" alt="product image">
                                        <?php else : ?>
                                            <img src="<?php echo $base_url; ?>/assets/no_image.png" width="100" alt="no image">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $product['product_name']; ?>
                                    </td>
                                  
                                    <td><?php echo $product['product_quantity']; ?></td>
                                    <td><?php echo number_format($product['product_price'], 2); ?></td>
                                    <td>
                                        <a role="button" href="<?php echo $base_url; ?>/index.php?id=<?php echo $product['product_id']; ?>" class="btn btn-outline-dark"><i class="fa-regular fa-pen-to-square m-1"></i>Edit</a>
                                        <a onclick="return confirm('คุณต้องการลบข้อมูลใช่หรือไม่');" role="button" href="<?php echo $base_url; ?>/product-delete.php?id=<?php echo $product['product_id']; ?>" class="btn btn-outline-danger"><i class="fa-solid fa-delete-left m-1"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center text-danger">ไม่มีรายการสินค้า</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>