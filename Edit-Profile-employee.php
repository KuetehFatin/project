<?php
session_start();
include("config.php");

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (empty($_SESSION['checklogin']) || $_SESSION['checklogin'] !== true) {
    $_SESSION['message'] = 'You are not authorized!';
    header("Location: {$base_url}/employee-login.php");
    exit();
}

// ดึงข้อมูลของพนักงานจากฐานข้อมูล
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
$result = mysqli_query($conn, $sql);
$employee = mysqli_fetch_assoc($result);

// จัดการการส่งแบบฟอร์มเพื่ออัปเดต
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    $employee_email = mysqli_real_escape_string($conn, $_POST['employee_email']);
    $employee_tel = mysqli_real_escape_string($conn, $_POST['employee_tel']);
    $employee_address = mysqli_real_escape_string($conn, $_POST['employee_address']);

    $sql = "UPDATE employee SET employee_name='$employee_name', employee_email='$employee_email', employee_tel='$employee_tel', employee_address='$employee_address' WHERE employee_id='$employee_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Profile updated successfully!';
        header("Location: {$base_url}/employee.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// จัดการการส่งแบบฟอร์มเพื่อลบ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $sql = "DELETE FROM employee WHERE employee_id='$employee_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Profile deleted successfully!';
        session_destroy();
        header("Location: {$base_url}/employee-login.php");
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
    <title>Edit Profile</title>
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
            height: calc(100vh - 40px); /* ลดความสูงลงเพื่อเพิ่มระยะห่างจากขอบ */
            position: fixed;
            top: 20px; /* เพิ่มระยะห่างจากขอบบน */
            left: 20px; /* เพิ่มระยะห่างจากขอบซ้าย */
            background-color: #d8cfc4; /* สีพื้นหลังของ sidebar */
            color: #5d3a1a; /* สีตัวอักษร */
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: background-color 0.3s; /* เพิ่มการเปลี่ยนสีพื้นหลังเมื่อ hover */
            border-radius: 10px; /* เพิ่มความโค้งของขอบพื้นหลัง */
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #5d3a1a; /* สีตัวอักษร */
            display: block;
            border-bottom: 1px solid rgba(93,58,26,0.3); /* สีเส้นขอบ */
            transition: background-color 0.3s, padding-left 0.3s; /* เพิ่มลูกเล่นเมื่อ hover */
            background-color: #a67c52; /* สีพื้นหลังของปุ่ม */
            border-color: #a67c52; /* สีขอบของปุ่ม */
            color: #fff; /* สีตัวอักษรของปุ่ม */
            text-align: center;
            margin: 10px 20px; /* เพิ่ม margin เพื่อให้มีระยะห่าง */
            border-radius: 10px; /* เพิ่มขอบโค้ง */
        }
        .sidebar a:hover {
            background-color: #8a5c40; /* สีพื้นหลังเมื่อ hover */
            border-color: #8a5c40; /* สีขอบเมื่อ hover */
            padding-left: 30px; /* เพิ่ม padding เมื่อ hover เพื่อให้ดูโดดเด่น */
        }
        .btn-danger {
            background-color: #dc3545; /* สีแดงเข้ม */
            border-color: #dc3545;
            border-radius: 10px; /* เพิ่มขอบโค้ง */
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .logout a {
            background-color: #dc3545; /* สีแดงเข้ม */
            border-color: #dc3545; /* สีขอบของปุ่ม */
            color: #fff; /* สีตัวอักษรของปุ่ม */
            text-align: center;
            display: block;
            padding: 10px;
            margin: 20px;
            text-decoration: none;
            border-radius: 10px; /* เพิ่มขอบโค้ง */
        }
        .logout a:hover {
            background-color: #c82333; /* สีพื้นหลังเมื่อ hover */
            border-color: #bd2130; /* สีขอบเมื่อ hover */
        }
        .content {
            margin-left: 290px; /* เพิ่มระยะห่างจากขอบของ sidebar */
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        } 
        .profile-card {
            width: 100%;
            max-width: 800px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-card .card-header {
            background: linear-gradient(to right, #5d3a1a, #a67c52); /* สีเกลี่ยมแนวนอน */
            color: #fff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
            text-align: center;
            padding: 20px; /* เพิ่ม padding เพื่อให้หัวข้อใหญ่ขึ้น */
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
            border-radius: 10px; /* เพิ่มขอบโค้ง */
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .menu .profile-link {
            margin-bottom: 20px; /* เพิ่มระยะห่างระหว่างเมนูหน้าโปรไฟล์กับข้อมูลลูกค้า */
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="menu">
            <a href="employee-profile.php" class="profile-link"><i class="fas fa-user"></i> หน้าโปรไฟล์</a>
            <a href="d-customer.php"><i class="fas fa-users"></i> ข้อมูลลูกค้า</a>
            <a href="d-employee.php"><i class="fas fa-user-tie"></i> ข้อมูลพนักงาน</a>
            <a href="d-salary.php"><i class="fas fa-money-bill-wave"></i> ข้อมูลเงินเดือนพนักงาน</a>
            <a href="d-product-categories.php"><i class="fas fa-tags"></i> ข้อมูลประเภทสินค้า</a>
            <a href="d-products.php"><i class="fas fa-box"></i> ข้อมูลสินค้า</a>
            <a href="d-sales.php"><i class="fas fa-chart-line"></i> ข้อมูลการขาย</a>
            <a href="d-salesdetails.php"><i class="fas fa-file-alt"></i> ข้อมูลรายละเอียดการขาย</a>
            <a href="d-material-orders.php"><i class="fas fa-truck"></i> ข้อมูลการสั่งซื้อวัสดุอุปกรณ์</a>
            <a href="d-material-order-details.php"><i class="fas fa-clipboard-list"></i> ข้อมูลรายละเอียดการสั่งซื้อวัสดุอุปกรณ์</a>
            <a href="d-materials.php"><i class="fas fa-tools"></i> ข้อมูลวัสดุอุปกรณ์</a>
            <a href="d-production.php"><i class="fas fa-industry"></i> ข้อมูลการผลิต</a>
            <a href="d-production-details.php"><i class="fas fa-info-circle"></i> ข้อมูลรายละเอียดการผลิต</a>
            <a href="d-supplier.php"><i class="fas fa-truck-loading"></i> ข้อมูลซัพพลาย</a>
        </div>
        <div class="logout">
            <a href="employee-login.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="content">
        <div class="profile-card card">
            <div class="card-header text-center">
                <h2>Edit Profile</h2>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info">
                        <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="employee_name">Name</label>
                        <input type="text" class="form-control" id="employee_name" name="employee_name" value="<?php echo htmlspecialchars($employee['employee_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_email">Email</label>
                        <input type="email" class="form-control" id="employee_email" name="employee_email" value="<?php echo htmlspecialchars($employee['employee_email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_tel">Tel</label>
                        <input type="text" class="form-control" id="employee_tel" name="employee_tel" value="<?php echo htmlspecialchars($employee['employee_tel']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_address">Address</label>
                        <input type="text" class="form-control" id="employee_address" name="employee_address" value="<?php echo htmlspecialchars($employee['employee_address']); ?>" required>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
                    <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your profile?');">Delete Profile</button>
                    <a href="employee.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>