<?php
session_start();

// ลบข้อมูล session ทั้งหมด
session_unset();
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
header('Location: login.php');
exit();
?>