<?php
session_start();
include("config.php");

$productIds = [];
foreach(($_SESSION['cart'] ?? []) as $cartId => $cartValue) {
    $productIds[] = $cartId;
}
$ids = 0;
if (count($productIds) > 0) {
    $ids = implode(', ', $productIds);
}

// Query to fetch products in the cart
$query = mysqli_query($conn, "SELECT * FROM product WHERE product_id IN ($ids)");
$rows = mysqli_num_rows($query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: Insert order data into the database, process payment, etc.
    // Assume checkout process is successful

    // Redirect to checkout-successfully.php
    header("Location: checkout-successfully.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.min.css" rel="stylesheet">
    <style>
        .checkout-form-container {
            border: 2px solid #dee2e6;
            border-radius: .375rem;
            padding: 5rem;
            background-color: #fff;
        }
    </style>
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

        <div class="row">
            <div class="col-md-8">
                <div class="checkout-form-container">
                    <h4 class="mb-3">Checkout</h4>
                    <form class="needs-validation" action="" method="POST" novalidate="">
                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-md-12">
                                <label for="firstName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="firstName" name="full_name" placeholder="" value="" required="">
                                <div class="invalid-feedback">Please enter a valid name.</div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email <span class="text-body-secondary">(Optional)</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
                                <div class="invalid-feedback">Please enter a valid email address to receive shipping updates.</div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-12">
                                <label for="tel" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="tel" name="phone" placeholder="">
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required="">
                                <div class="invalid-feedback">Please enter your shipping address.</div>
                            </div>

                            <h4 class="mb-3">Payment</h4>
                            <div class="form-check">
                                <input id="credit" name="paymentMethod" type="radio" class="form-check-input" value="COD" required="">
                                <label class="form-check-label" for="credit">Cash on Delivery</label>
                            </div>
                            <div class="form-check">
                                <input id="debit" name="paymentMethod" type="radio" class="form-check-input" value="QR" required="">
                                <label class="form-check-label" for="debit">QR Code Payment</label>
                            </div>
                            <hr class="my-4">
                            <div class="text-end">
                                <a href="<?php echo $base_url; ?>/product-list.php" class="btn btn-secondary btn-lg">Back to Product List</a>
                                <button class="btn btn-primary btn-lg" type="submit">Continue to Checkout</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Your Cart</span>
                    <span class="badge bg-primary rounded-pill"><?php echo $rows; ?></span>
                </h4>
                <?php if ($rows > 0): ?>
                    <ul class="list-group mb-3">
                        <?php $totalprice = 0; ?>
                        <?php while($product = mysqli_fetch_assoc($query)): ?>
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0"><?php echo $product['product_name']; ?> (<?php echo $_SESSION['cart'][$product['product_id']]; ?>)</h6>
                                    <input type="hidden" name="product[<?php echo $product['product_id']; ?>][product_price]" value="<?php echo $product['product_price']; ?>">
                                    <input type="hidden" name="product[<?php echo $product['product_id']; ?>][product_name]" value="<?php echo $product['product_name']; ?>">
                                </div>
                                <span class="text-body-secondary">฿<?php echo number_format($_SESSION['cart'][$product['product_id']] * $product['product_price'], 2); ?></span>
                            </li>
                            <?php $totalprice += $_SESSION['cart'][$product['product_id']] * $product['product_price']; ?>
                        <?php endwhile; ?>
                        <li class="list-group-item d-flex justify-content-between bg-body-tertiary">
                            <div class="text-success">
                                <h6 class="my-0">Grand Total</h6>
                                <small>Amount</small>
                            </div>
                            <span class="text-success"><strong>฿<?php echo number_format($totalprice, 2); ?></strong></span>
                        </li>
                    </ul>
                    <input type="hidden" name="totalprice" value="<?php echo $totalprice; ?>">
                <?php else: ?>
                    <p>Your cart is empty</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
</body>
</html>
