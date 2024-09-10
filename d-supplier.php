<?php
session_start(); // เริ่มต้น session เพื่อใช้ในการจัดเก็บข้อมูลชั่วคราวระหว่างการเรียกใช้งานหน้าเว็บ
include("config.php"); // นำเข้าไฟล์ config.php ซึ่งน่าจะมีการตั้งค่าการเชื่อมต่อฐานข้อมูล

// ข้อมูล supplier ทั้งหมด
$query = mysqli_query($conn, "SELECT * FROM supplier"); // ดึงข้อมูลทั้งหมดจากตาราง supplier
$rows = mysqli_num_rows($query); // นับจำนวนแถวที่ได้จากการ query

// แบบฟอร์ม supplier 
$result = [
    'supplier_id' => '',
    'supplier_name' => '',
    'supplier_tel' => '',
    'supplier_address' => '',
];

// เลือกแก้ไข supplier 
if (!empty($_GET['id'])) { // ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
    $query_supplier = mysqli_query($conn, "SELECT * FROM supplier WHERE supplier_id = {$_GET['id']}"); // ดึงข้อมูล supplier ที่มี id ตรงกับที่ส่งมา
    $row_supplier = mysqli_num_rows($query_supplier); // นับจำนวนแถวที่ได้จากการ query

    if ($row_supplier == 0) { // ถ้าไม่มีข้อมูล
        header('Location:' . $base_url . '/d-supplier.php'); // เปลี่ยนเส้นทางไปที่หน้า d-supplier.php
    }

    $result = mysqli_fetch_assoc($query_supplier); // เก็บข้อมูลที่ได้จากการ query ลงในตัวแปร $result
}

// ตรวจสอบว่ามีการคลิกยกเลิกหรือไม่
if (isset($_GET['cancel']) && $_GET['cancel'] == 'true') { // ตรวจสอบว่ามีการส่งค่า cancel มาหรือไม่
    $result = [
        'supplier_id' => '',
        'supplier_name' => '',
        'supplier_tel' => '',
        'supplier_address' => '',
    ];
}

// จัดการส่งแบบฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") { // ตรวจสอบว่ามีการส่งข้อมูลผ่าน method POST หรือไม่
    $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']); // กรองข้อมูล supplier_id เพื่อป้องกัน SQL Injection
    $supplier_name = mysqli_real_escape_string($conn, $_POST['supplier_name']); // กรองข้อมูล supplier_name เพื่อป้องกัน SQL Injection
    $supplier_tel = mysqli_real_escape_string($conn, $_POST['supplier_tel']); // กรองข้อมูล supplier_tel เพื่อป้องกัน SQL Injection
    $supplier_address = mysqli_real_escape_string($conn, $_POST['supplier_address']); // กรองข้อมูล supplier_address เพื่อป้องกัน SQL Injection

    if (empty($supplier_id)) { // ถ้า supplier_id ว่าง
        // ใส่ supplier ใหม่
        $sql = "INSERT INTO supplier (supplier_name, supplier_tel, supplier_address) VALUES ('$supplier_name', '$supplier_tel', '$supplier_address')"; // สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูล
        if (mysqli_query($conn, $sql)) { // ถ้าการเพิ่มข้อมูลสำเร็จ
            $_SESSION['message'] = 'Supplier added successfully!'; // เก็บข้อความแจ้งเตือนใน session
        } else {
            $_SESSION['message'] = 'Error: ' . mysqli_error($conn); // เก็บข้อความแจ้งเตือนข้อผิดพลาดใน session
        }
    } else {
        // อัพเดท supplier ที่มีอยู่
        $sql = "UPDATE supplier SET supplier_name='$supplier_name', supplier_tel='$supplier_tel', supplier_address='$supplier_address' WHERE supplier_id='$supplier_id'"; // สร้างคำสั่ง SQL สำหรับแก้ไขข้อมูล
        if (mysqli_query($conn, $sql)) { // ถ้าการแก้ไขข้อมูลสำเร็จ
            $_SESSION['message'] = 'Supplier updated successfully!'; // เก็บข้อความแจ้งเตือนใน session
        } else {
            $_SESSION['message'] = 'Error: ' . mysqli_error($conn); // เก็บข้อความแจ้งเตือนข้อผิดพลาดใน session
        }
    }
    header('Location: ' . $base_url . '/d-supplier.php'); // เปลี่ยนเส้นทางไปที่หน้า d-supplier.php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.min.css" rel="stylesheet">
    <style>
        .document-style {
            font-family: 'Times New Roman', Times, serif;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #6c757d; /* สีเทาเข้ม */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-body-tertiary">
    <div class="container document-style mt-5">
        <?php if (!empty($_SESSION['message'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h4>Manage Supplier</h4>
        <div class="row g-5">
            <div class="col-md-8 col-sm-12">
                <form action="<?php echo $base_url; ?>/d-supplier.php" method="post">
                    <input type="hidden" name="supplier_id" value="<?php echo $result['supplier_id']; ?>"> <!-- ID เป็นค่าว่าง = Create ID มีตัวเลข = Update-->
                    <div class="row g-3 md-3">
                        <div class="col-sm-6">
                            <label class="form-label">Supplier Name</label>
                            <input type="text" name="supplier_name" class="form-control" value="<?php echo $result['supplier_name']; ?>" required>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Supplier Tel</label>
                            <input type="text" name="supplier_tel" class="form-control" value="<?php echo $result['supplier_tel']; ?>" required>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Supplier Address</label>
                            <input type="text" name="supplier_address" class="form-control" value="<?php echo $result['supplier_address']; ?>" required>
                        </div>

                        <div class="col-12">
                            <?php if (empty($result['supplier_id'])) : ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-regular fa-floppy-disk m-1"></i>Create</button>
                            <?php else : ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-regular fa-floppy-disk m-1"></i>Update</button>
                            <?php endif; ?>
                            <a role="button" class="btn btn-secondary btn-sm" href="<?php echo $base_url; ?>/d-supplier.php?cancel=true" type="button"><i class="fa-solid fa-rectangle-xmark m-1"></i>Cancel</a>
                        </div>
                        <hr class="my-4">
                    </div>
                </form>
            </div>
        </div>

        <!-- ตารางแสดงข้อมูล supplier -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered border-secondary">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Supplier ID</th>
                            <th style="width: 100px;">Supplier Name</th>
                            <th style="width: 200px;">Supplier Tel</th>
                            <th style="width: 200px;">Supplier Address</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rows > 0) : ?>
                            <?php while ($supplier = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td><?php echo $supplier['supplier_id']; ?></td>
                                    <td><?php echo $supplier['supplier_name']; ?></td>
                                    <td><?php echo $supplier['supplier_tel']; ?></td>
                                    <td><?php echo $supplier['supplier_address']; ?></td>
                                    <td class="d-flex justify-content-between">
                                        <a role="button" href="<?php echo $base_url; ?>/d-supplier.php?id=<?php echo $supplier['supplier_id']; ?>" class="btn btn-outline-dark btn-sm"><i class="fa-regular fa-pen-to-square m-1"></i>Edit</a>
                                        <a onclick="return confirm('คุณต้องการลบข้อมูลใช่หรือไม่');" role="button" href="<?php echo $base_url; ?>/d-supplier-delete.php?id=<?php echo $supplier['supplier_id']; ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-delete-left m-1"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                        <tr>
                                <td colspan="5" class="text-center text-danger">ไม่มีรายการ supplier</td>
                            </tr>
                        <?php endif; ?>
                        <div class="row mt-3">
                    </tbody>
                </table>
                <div class="col-12 text-left">
                    <a href="employee.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
