<?php
session_start();
include("config.php");

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (empty($_SESSION['checklogin']) || $_SESSION['checklogin'] !== true) {
    $_SESSION['message'] = 'You are not authorized!';
    header("Location: {$base_url}/customer-login.php");
    exit();
}

// ดึงข้อมูลของลูกค้าจากฐานข้อมูล
$sql = "SELECT * FROM customers";
$result = mysqli_query($conn, $sql);
$customers = mysqli_fetch_all($result, MYSQLI_ASSOC);

// จัดการการส่งแบบฟอร์มเพื่ออัปเดต
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $customer_tel = mysqli_real_escape_string($conn, $_POST['customer_tel']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);

    $sql = "UPDATE customers SET customer_name='$customer_name', customer_email='$customer_email', customer_tel='$customer_tel', customer_address='$customer_address' WHERE customer_id='$customer_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Profile updated successfully!';
        header("Location: {$base_url}/d-customer.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// จัดการการส่งแบบฟอร์มเพื่อลบ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $sql = "DELETE FROM customers WHERE customer_id='$customer_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Profile deleted successfully!';
        header("Location: {$base_url}/d-customer.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #d8cfc4; /* สีพื้นหลังของ sidebar */
            color: #5d3a1a; /* สีตัวอักษร */
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: background-color 0.3s; /* เพิ่มการเปลี่ยนสีพื้นหลังเมื่อ hover */
        }
        .sidebar-header {
            text-align: center;
            padding: 10px 0;
            font-size: 22px;
            font-weight: bold;
            border-bottom: 1px solid #5d3a1a; /* เส้นขอบด้านล่าง */
            background-color: #f5e7e1; /* สีพื้นหลังของ header */
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #5d3a1a; /* สีตัวอักษร */
            display: block;
            border-bottom: 1px solid rgba(93,58,26,0.3); /* สีเส้นขอบ */
            transition: background-color 0.3s, padding-left 0.3s; /* เพิ่มลูกเล่นเมื่อ hover */
        }
        .sidebar a:hover {
            background-color: #bfa89e; /* สีพื้นหลังเมื่อ hover */
            padding-left: 30px; /* เพิ่ม padding เมื่อ hover เพื่อให้ดูโดดเด่น */
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .logout a {
            background-color: #a67c52; /* สีพื้นหลังของปุ่ม */
            border-color: #a67c52; /* สีขอบของปุ่ม */
            color: #fff; /* สีตัวอักษรของปุ่ม */
            text-align: center;
            display: block;
            padding: 10px;
            margin: 20px;
            text-decoration: none;
        }
        .logout a:hover {
            background-color: #8a5c40; /* สีพื้นหลังเมื่อ hover */
            border-color: #8a5c40; /* สีขอบเมื่อ hover */
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }
        .profile-card {
            width: 48%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .profile-card .card-header {
            background: #5d3a1a; /* เปลี่ยนสีเป็นสีน้ำตาลเข้ม */
            color: #fff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .profile-card .card-footer {
            background: #f5f5f5;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .profile-card .card-body {
            padding: 20px;
        }
        .profile-card .card-body p {
            margin-bottom: 10px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            ระบบจัดการข้อมูล
        </div>
        <div class="menu">
            <a href="d-customer.php">ข้อมูลลูกค้า</a>
            <a href="d-employee.php">ข้อมูลพนักงาน</a>
            <a href="d-salary.php">ข้อมูลเงินเดือนพนักงาน</a>
            <a href="d-product-categories.php">ข้อมูลประเภทสินค้า</a>
            <a href="d-products.php">ข้อมูลสินค้า</a>
            <a href="d-sales.php">ข้อมูลการขาย</a>
            <a href="d-salesdetails.php">ข้อมูลรายละเอียดการขาย</a>
            <a href="d-material-orders.php">ข้อมูลการสั่งซื้อวัสดุอุปกรณ์</a>
            <a href="d-material-order-details.php">ข้อมูลรายละเอียดการสั่งซื้อวัสดุอุปกรณ์</a>
            <a href="d-materials.php">ข้อมูลวัสดุอุปกรณ์</a>
            <a href="d-production.php">ข้อมูลการผลิต</a>
            <a href="d-production-details.php">ข้อมูลรายละเอียดการผลิต</a>
            <a href="d-supplier.php">ข้อมูลซัพพลาย</a>
        </div>
        <div class="logout">
            <a href="employee-login.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="content">
        <div class="profile-card card">
            <div class="card-header text-center">
                <h2>Customer Management</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($_SESSION['message'])) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered border-info">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Id</th>
                                    <th>Name</th>
                                    <th style="width: 200px;">Email</th>
                                    <th style="width: 200px;">Tel</th>
                                    <th style="width: 200px;">Address</th>
                                    <th style="width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($customers) > 0) : ?>
                                    <?php foreach ($customers as $customer) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['customer_tel']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['customer_address']); ?></td>
                                            <td>
                                                <a role="button" href="<?php echo $base_url; ?>/d-customer.php?edit=<?php echo $customer['customer_id']; ?>" class="btn btn-outline-dark"><i class="fas fa-edit"></i> Edit</a>
                                                <form method="POST" action="" style="display:inline;">
                                                    <input type="hidden" name="customer_id" value="<?php echo $customer['customer_id']; ?>">
                                                    <button type="submit" name="delete" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this customer?');"><i class="fas fa-trash-alt"></i> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-danger">ไม่มีข้อมูลลูกค้า</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-left">
                        <a href="employee.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['edit'])): ?>
            <?php
            $customer_id = mysqli_real_escape_string($conn, $_GET['edit']);
            $sql = "SELECT * FROM customers WHERE customer_id = '$customer_id'";
            $result = mysqli_query($conn, $sql);
            $customer = mysqli_fetch_assoc($result);
            ?>
            <div class="profile-card card mt-5">
                <div class="card-header text-center">
                    <h2>Edit Customer</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer['customer_id']); ?>">
                        <div class="form-group">
                            <label for="customer_name">Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($customer['customer_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_email">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?php echo htmlspecialchars($customer['customer_email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_tel">Tel</label>
                            <input type="text" class="form-control" id="customer_tel" name="customer_tel" value="<?php echo htmlspecialchars($customer['customer_tel']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_address">Address</label>
                            <input type="text" class="form-control" id="customer_address" name="customer_address" value="<?php echo htmlspecialchars($customer['customer_address']); ?>" required>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary btn-sm btn-block"><i class="fas fa-save"></i> Update</button>
                        <a href="d-customer.php" class="btn btn-secondary btn-sm btn-block"><i class="fas fa-times"></i> Cancel</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>