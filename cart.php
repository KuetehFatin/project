<?php
session_start();
include("config.php");

$productIds = [];
foreach(($_SESSION['cart'] ?? []) as $cartId => $cartValue) {
  $productIds[] = $cartId;
} 

$ids = 0;
if(count($productIds) > 0) {
    $ids = implode(', ',$productIds);
}

//product all
$query = mysqli_query($conn, "SELECT * FROM product WHERE product_id IN ($ids)");
$rows = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">
    <?php include 'include/menu.php'; ?>
    <div class="container mt-4">
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h4>Cart</h4>
        <form action="<?php echo $base_url; ?>/cart-update.php" method="post">
            <table class="table table-bordered border-info">
                <thead>
                    <tr>
                        <th style="width: 100px;">Image</th>
                        <th>Product Name</th>
                        <th style="width: 200px;">Price</th>
                        <th style="width: 150px;">Quantity</th>
                        <th style="width: 200px;">Total</th>
                        <th style="width: 200px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($rows > 0): ?>
                        <?php while ($product = mysqli_fetch_assoc($query)): ?>
                            <?php
                            $quantity = $_SESSION['cart'][$product['product_id']] ?? 1;
                            $totalPrice = $product['product_price'] * $quantity;
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo !empty($product['product_image']) ? $base_url . '/upload_image/' . $product['product_image'] : $base_url . '/assets/no_image.png'; ?>" width="100" alt="product image">
                                </td>
                                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td><?php echo number_format($product['product_price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $product['product_id']; ?>]" value="<?php echo $quantity; ?>" class="form-control" min="1" required>
                                </td>
                                <td><?php echo number_format($totalPrice, 2); ?></td>
                                <td>
                                    <a onclick="return confirm('คุณต้องการลบข้อมูลใช่หรือไม่');" href="<?php echo $base_url; ?>/cart-delete.php?id=<?php echo $product['product_id']; ?>" class="btn btn-outline-danger">
                                        <i class="fa-solid fa-delete-left m-1"></i>Delete 
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <tr>
                            <td colspan="7" class="text-end">
                                <button type="submit" class="btn btn-lg btn-success">Update Cart</button>
                                <a href="<?php echo $base_url; ?>/checkout.php" class="btn btn-lg btn-primary">Checkout</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-danger">ไม่มีรายการสินค้า</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>

    <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
</body>
</html>
