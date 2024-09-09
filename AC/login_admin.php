<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // เชื่อมต่อฐานข้อมูล
    $pdo = Config::connect();

    if ($pdo) {
        // ตรวจสอบข้อมูลผู้ใช้
        $stmt = $pdo->prepare("SELECT id, name, password FROM admin WHERE name = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) { // เปรียบเทียบรหัสผ่านโดยตรง
            // ตั้งค่า session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            header('Location: ../Dasboard.php');
            exit();
        } else {
            // ข้อความแจ้งเตือนเมื่อข้อมูลไม่ถูกต้อง
            echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'); window.location.href='../login.php';</script>";
        }
    } else {
        // ข้อความแจ้งเตือนเมื่อไม่สามารถเชื่อมต่อฐานข้อมูลได้
        echo "<script>alert('ไม่สามารถเชื่อมต่อฐานข้อมูลได้'); window.location.href='../login.php';</script>";
    }
}
?>