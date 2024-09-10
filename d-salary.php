<?php
session_start(); // เริ่มต้น session เพื่อใช้ในการจัดเก็บข้อมูลชั่วคราวระหว่างการเรียกใช้งานหน้าเว็บ
include("config.php"); // นำเข้าไฟล์ config.php ซึ่งน่าจะมีการตั้งค่าการเชื่อมต่อฐานข้อมูล

// เงินเดือนทั้งหมด
$query = mysqli_query($conn, "SELECT * FROM salary"); // ดึงข้อมูลทั้งหมดจากตาราง salary
$rows = mysqli_num_rows($query); // นับจำนวนแถวที่ได้จากการ query

// แบบฟอร์มเงินเดือน 
$result = [
    'salary_id' => '',
    'employee_id' => '',
    'salary_date' => '',
    'salary_amount' => '',
];

// เลือกแก้ไขเงินเดือน 
if (!empty($_GET['id'])) { // ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
    $query_salary = mysqli_query($conn, "SELECT * FROM salary WHERE salary_id = {$_GET['id']}"); // ดึงข้อมูล salary ที่มี id ตรงกับที่ส่งมา
    $row_salary = mysqli_num_rows($query_salary); // นับจำนวนแถวที่ได้จากการ query

    if ($row_salary == 0) { // ถ้าไม่มีข้อมูล
        header('Location:' . $base_url . '/d-salary.php'); // เปลี่ยนเส้นทางไปที่หน้า d-salary.php
    }

    $result = mysqli_fetch_assoc($query_salary); // เก็บข้อมูลที่ได้จากการ query ลงในตัวแปร $result
}

// ตรวจสอบว่ามีการคลิกยกเลิกหรือไม่
if (isset($_GET['cancel']) && $_GET['cancel'] == 'true') { // ตรวจสอบว่ามีการส่งค่า cancel มาหรือไม่
    $result = [
        'salary_id' => '',
        'employee_id' => '',
        'salary_date' => '',
        'salary_amount' => '',
    ];
}

// จัดการส่งแบบฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") { // ตรวจสอบว่ามีการส่งข้อมูลผ่าน method POST หรือไม่
    $salary_id = mysqli_real_escape_string($conn, $_POST['salary_id']); // กรองข้อมูล salary_id เพื่อป้องกัน SQL Injection
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']); // กรองข้อมูล employee_id เพื่อป้องกัน SQL Injection
    $salary_date = mysqli_real_escape_string($conn, $_POST['salary_date']); // กรองข้อมูล salary_date เพื่อป้องกัน SQL Injection
    $salary_amount = mysqli_real_escape_string($conn, $_POST['salary_amount']); // กรองข้อมูล salary_amount เพื่อป้องกัน SQL Injection

    // ตรวจสอบว่ามี Employee_id อยู่หรือไม่
    $employee_check_query = mysqli_query($conn, "SELECT * FROM employee WHERE employee_id = '$employee_id'"); // ตรวจสอบว่า employee_id มีอยู่ในตาราง employee หรือไม่
    if (mysqli_num_rows($employee_check_query) == 0) { // ถ้าไม่มีข้อมูล
        $_SESSION['message'] = 'Employee ID not found!'; // ข้อความแจ้งเตือนใน session
        header('Location: ' . $base_url . '/d-salary.php'); // เปลี่ยนเส้นทางไปที่หน้า d-salary.php
        exit();
    }

    if (empty($salary_id)) { // ถ้า salary_id ว่าง
        // ใส่เงินเดือนใหม่
        $sql = "INSERT INTO salary (employee_id, salary_date, salary_amount) VALUES ('$employee_id', '$salary_date', '$salary_amount')"; // สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูล
        if (mysqli_query($conn, $sql)) { // ถ้าการเพิ่มข้อมูลสำเร็จ
            $_SESSION['message'] = 'Salary added successfully!'; // เก็บข้อความแจ้งเตือนใน session
        } else {
            $_SESSION['message'] = 'Error: ' . mysqli_error($conn); // เก็บข้อความแจ้งเตือนข้อผิดพลาดใน session
        }
    } else {
        // อัพเดทเงินเดือนที่มีอยู่
        $sql = "UPDATE salary SET employee_id='$employee_id', salary_date='$salary_date', salary_amount='$salary_amount' WHERE salary_id='$salary_id'"; // สร้างคำสั่ง SQL สำหรับแก้ไขข้อมูล
        if (mysqli_query($conn, $sql)) { // ถ้าการแก้ไขข้อมูลสำเร็จ
            $_SESSION['message'] = 'Salary updated successfully!'; // เก็บข้อความแจ้งเตือนใน session
        } else {
            $_SESSION['message'] = 'Error: ' . mysqli_error($conn); // เก็บข้อความแจ้งเตือนข้อผิดพลาดใน session
        }
    }
    header('Location: ' . $base_url . '/d-salary.php'); // เปลี่ยนเส้นทางไปที่หน้า d-salary.php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Management</title>

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
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .btn-outline-dark, .btn-outline-danger {
            margin-right: 5px;
        }
    </style>
</head>

<body class="bg-body-tertiary">
    <div class="container document-style" style="margin-top: 30px;">
        <?php if (!empty($_SESSION['message'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h4>Manage Salary</h4>
        <div class="row g-5">
            <div class="col-md-8 col-sm-12">
                <form action="<?php echo $base_url; ?>/d-salary.php" method="post">
                    <input type="hidden" name="salary_id" value="<?php echo $result['salary_id']; ?>"> <!-- ID เป็นค่าว่าง = Create ID มีตัวเลข = Update-->
                    <div class="row g-3 md-3">
                        <div class="col-sm-6">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" value="<?php echo $result['employee_id']; ?>">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Salary Date</label>
                            <input type="date" name="salary_date" class="form-control" value="<?php echo $result['salary_date']; ?>">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Salary Amount</label>
                            <input type="text" name="salary_amount" class="form-control" value="<?php echo $result['salary_amount']; ?>">
                        </div>

                        <div class="col-12">
                            <?php if (empty($result['salary_id'])) : ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-regular fa-floppy-disk m-1"></i>Create</button>
                            <?php else : ?>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa-regular fa-floppy-disk m-1"></i>Update</button>
                            <?php endif; ?>
                            <a role="button" class="btn btn-secondary btn-sm" href="<?php echo $base_url; ?>/d-salary.php?cancel=true" type="button"><i class="fa-solid fa-rectangle-xmark m-1"></i>Cancel</a>
                        </div>
                        <hr class="my-4">
                    </div>
                </form>
            </div>
        </div>

        <!-- ตารางแสดงข้อมูลเงินเดือน -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered border-secondary ">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Salary ID</th>
                            <th style="width: 50px;">Employee ID</th>
                            <th style="width: 100px;">Salary Date</th>
                            <th style="width: 200px;">Salary Amount</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rows > 0) : ?>
                            <?php while ($salary = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td><?php echo $salary['salary_id']; ?></td>
                                    <td><?php echo $salary['employee_id']; ?></td>
                                    <td><?php echo $salary['salary_date']; ?></td>
                                    <td><?php echo number_format($salary['salary_amount'], 2); ?></td>
                                    <td>
                                        <a role="button" href="<?php echo $base_url; ?>/d-salary.php?id=<?php echo $salary['salary_id']; ?>" class="btn btn-outline-dark btn-sm"><i class="fa-regular fa-pen-to-square m-1"></i>Edit</a>
                                        <a onclick="return confirm('คุณต้องการลบข้อมูลใช่หรือไม่');" role="button" href="<?php echo $base_url; ?>/salary-delete.php?id=<?php echo $salary['salary_id']; ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-delete-left m-1"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center text-danger">ไม่มีรายการเงินเดือน</td>
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