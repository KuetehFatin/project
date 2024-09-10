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
$sql = "SELECT * FROM employee";
$result = mysqli_query($conn, $sql);

// จัดการการส่งแบบฟอร์มเพื่ออัปเดต
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    $employee_email = mysqli_real_escape_string($conn, $_POST['employee_email']);
    $employee_tel = mysqli_real_escape_string($conn, $_POST['employee_tel']);
    $employee_address = mysqli_real_escape_string($conn, $_POST['employee_address']);

    $sql = "UPDATE employee SET employee_name='$employee_name', employee_email='$employee_email', employee_tel='$employee_tel', employee_address='$employee_address' WHERE employee_id='$employee_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Profile updated successfully!';
        header("Location: {$base_url}/d-employee.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// จัดการการส่งแบบฟอร์มเพื่อลบ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $sql = "DELETE FROM employee WHERE employee_id='$employee_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Profile deleted successfully!';
        header("Location: {$base_url}/d-employee.php");
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
    <title>Employee Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .document-style {
            font-family: 'Times New Roman', Times, serif;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #6c757d; /* สีเทาเข้ม */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-bordered th,
        .table-bordered td {
            border: 2px solid #343a40 !important; /* สีเข้ม */
        }
    </style>
</head>
<body class="bg-light">
<div class="container document-style mt-5">
    <?php if (!empty($_SESSION['message'])) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <h2 class="text-center">Employee Management</h2>
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
                    <?php if (mysqli_num_rows($result) > 0) : ?>
                        <?php while ($employee = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($employee['employee_name']); ?></td>
                                <td><?php echo htmlspecialchars($employee['employee_email']); ?></td>
                                <td><?php echo htmlspecialchars($employee['employee_tel']); ?></td>
                                <td><?php echo htmlspecialchars($employee['employee_address']); ?></td>
                                <td>
                                    <a role="button" href="<?php echo $base_url; ?>/d-employee.php?edit=<?php echo $employee['employee_id']; ?>" class="btn btn-outline-dark"><i class="fas fa-edit"></i> Edit</a>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this employee?');"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center text-danger">No employee found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (isset($_GET['edit'])): ?>
        <?php
        $employee_id = mysqli_real_escape_string($conn, $_GET['edit']);
        $sql = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
        $result = mysqli_query($conn, $sql);
        $employee = mysqli_fetch_assoc($result);
        ?>
        <div class="row justify-content-end mt-5">
            <div class="col-md-5">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h2>Edit Employee</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
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
                            <button type="submit" name="update" class="btn btn-primary btn-sm btn-block"><i class="fas fa-save"></i> Update</button>
                            <a href="d-employee.php" class="btn btn-secondary btn-sm btn-block"><i class="fas fa-times"></i> Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row mt-3">
        <div class="col-12 text-left">
            <a href="employee.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>