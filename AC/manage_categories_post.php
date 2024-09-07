<?php
require_once '../config/config.php';
require_once '../function/get_coffeeapp.php';

// ถ้ามีการเพิ่มหมวดหมู่ใหม่
if (isset($_POST['add'])) {
    $type_name = $_POST['type_name'];
    CoffeeApp::addCoffeeType($type_name);
    echo json_encode(['status' => 'success', 'message' => 'เพิ่มหมวดหมู่สำเร็จ!']);
    exit;
}

// ถ้ามีการแก้ไขหมวดหมู่
if (isset($_POST['edit'])) {
    $type_id = $_POST['type_id'];
    $type_name = $_POST['type_name'];
    CoffeeApp::updateCoffeeType($type_id, $type_name);
    echo json_encode(['status' => 'success', 'message' => 'แก้ไขหมวดหมู่สำเร็จ!']);
    exit;
}

// ถ้ามีการลบหมวดหมู่
if (isset($_GET['delete'])) {
    $type_id = $_GET['delete'];
    CoffeeApp::deleteCoffeeType($type_id);
    echo json_encode(['status' => 'success', 'message' => 'ลบหมวดหมู่สำเร็จ!']);
    exit;
}
?>