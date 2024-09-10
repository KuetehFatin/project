<?php

session_start();

include("config.php");

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (empty($_SESSION['checklogin']) || $_SESSION['checklogin'] !== true) {
    // หากยังไม่ได้เข้าสู่ระบบ ให้ตั้งค่าข้อความแจ้งเตือนในเซสชัน
    $_SESSION['message'] = 'You are not authorized!';
    
    // ทำการรีไดเรกต์ไปยังหน้าเข้าสู่ระบบ
    header("Location: {$base_url}/customer-login.php");
    exit(); // หยุดการทำงานของสคริปต์หลังจากการรีไดเรกต์
}
?>  

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home </title>
</head>
<body>
    <header>
        <h1>Welcome to the Home Page</h1>
        <!-- คุณสามารถเพิ่มลิงก์เมนูหรือข้อมูลอื่นๆที่ต้องการแสดงในส่วนหัวที่นี่ -->
    </header>

    <main>
        <!-- เนื้อหาหลักของหน้า -->
        <p>This is a protected area. You are logged in!</p>
    </main>

    <footer>
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </footer>
</body>
</html>
