<?php
// เชื่อมต่อฐานข้อมูล
include '../config/config.php';
include '../function/get_coffeeapp.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $total_amount = $_POST['total_amount'];
    $received_amount = $_POST['received_amount'];
    $change_amount = $_POST['change_amount'];
    $cart_items = json_decode($_POST['cart_items'], true); // แปลงเป็น array

    // เรียกใช้ฟังก์ชัน processOrder
    $result = CoffeeApp::processOrder($total_amount, $received_amount, $change_amount, $cart_items);

    // ตอบกลับเป็น JSON
    echo json_encode($result);
}
?>