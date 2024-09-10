<?php
session_start();
include("config.php");

// ดึงข้อมูลสินค้าทั้งหมด
$query = mysqli_query($conn, "SELECT * FROM employee");
$rows = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>employee</title>

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

 

<div class="row">
            <div class="col-12">
                <table class="table table-bordered border-info">
                    <thead>
                        <tr>
                            <th>employee Name</th>
                            <th style="width: 100px;">Name/th>
                            <th style="width: 100px;">tel</th>
                            <th style="width: 200px;">address</th>
                            <th style="width: 200px;">email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rows > 0) : ?>
                            <?php while ($employee = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td>
                                        <?php echo $employee['employee_name']; ?>
                                    </td>
                                    <td><?php echo $employee['employee_tel']; ?></td>
                                    <td><?php echo $employee['employee_address']; ?></td>
                                    <td><?php echo number_format($product['employee_email'], 2); ?></td>
                                    <td>
                                        <a role="button" href="<?php echo $base_url; ?>/index.php?id=<?php echo $employee['employee_id']; ?>" class="btn btn-outline-dark"><i class="fa-regular fa-pen-to-square m-1"></i>Edit</a>
                                        <a onclick="return confirm('คุณต้องการลบข้อมูลใช่หรือไม่');" role="button" href="<?php echo $base_url; ?>/employee-delete.php?id=<?php echo $employee['employee_id']; ?>" class="btn btn-outline-danger"><i class="fa-solid fa-delete-left m-1"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center text-danger">ไม่มี</td>
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