<?php
include '../config/config.php';
include '../function/get_coffeeapp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $coffee_name = $_POST['coffee_name'];
        $coffee_price = $_POST['coffee_price'];
        $type_id = $_POST['type_id'];

        // จัดการการอัปโหลดไฟล์ภาพ
        if (isset($_FILES['coffee_image']) && $_FILES['coffee_image']['error'] == UPLOAD_ERR_OK) {
            $image_name = basename($_FILES['coffee_image']['name']);
            $target_path = '../img/' . $image_name;

            // ตรวจสอบและย้ายไฟล์ภาพไปยังโฟลเดอร์ img
            if (move_uploaded_file($_FILES['coffee_image']['tmp_name'], $target_path)) {
                CoffeeApp::addCoffee($coffee_name, $coffee_price, $image_name, $type_id);
                echo 'success';
            } else {
                echo 'Failed to upload image.';
            }
        } else {
            echo 'No image uploaded.';
        }
    } elseif (isset($_POST['edit'])) {
        $coffee_id = $_POST['coffee_id'];
        $coffee_name = $_POST['coffee_name'];
        $coffee_price = $_POST['coffee_price'];
        $type_id = $_POST['type_id'];
        $coffee_image = null; // ใช้ภาพเดิมก่อน

        // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
        if (isset($_FILES['coffee_image']) && $_FILES['coffee_image']['error'] == UPLOAD_ERR_OK) {
            $image_name = basename($_FILES['coffee_image']['name']);
            $target_path = '../img/' . $image_name;

            // ตรวจสอบและย้ายไฟล์ภาพไปยังโฟลเดอร์ img
            if (move_uploaded_file($_FILES['coffee_image']['tmp_name'], $target_path)) {
                $coffee_image = $image_name; // ใช้ภาพใหม่
            }
        }

        CoffeeApp::updateCoffee($coffee_id, $coffee_name, $coffee_price, $coffee_image, $type_id);
        echo 'success';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $coffee_id = $_GET['delete'];
    CoffeeApp::deleteCoffee($coffee_id);
    echo 'success';
}
?>