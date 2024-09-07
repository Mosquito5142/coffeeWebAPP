<?php
include '../config/config.php';
include '../function/get_coffeeapp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $coffee_name = $_POST['coffee_name'];
        $coffee_price = $_POST['coffee_price'];
        $type_id = $_POST['type_id'];

        // เพิ่มสินค้าลงในฐานข้อมูลก่อนเพื่อให้ได้ coffee_id
        $coffee_id = CoffeeApp::addCoffee($coffee_name, $coffee_price, '', $type_id);

        if ($coffee_id) {
            // จัดการการอัปโหลดไฟล์ภาพ
            if (isset($_FILES['coffee_image']) && $_FILES['coffee_image']['error'] == UPLOAD_ERR_OK) {
                $image_name = 'img/' . $coffee_id . '.jpg';
                $target_path = '../' . $image_name;

                // ตรวจสอบและย้ายไฟล์ภาพไปยังโฟลเดอร์ img
                $image = imagecreatefromstring(file_get_contents($_FILES['coffee_image']['tmp_name']));
                if ($image !== false && imagejpeg($image, $target_path)) {
                    imagedestroy($image); // ทำลายภาพจากหน่วยความจำ

                    // อัปเดตชื่อไฟล์รูปภาพในฐานข้อมูล
                    CoffeeApp::updateCoffeeImage($coffee_id, $image_name);
                    echo 'success';
                } else {
                    echo 'Failed to upload image.';
                }
            } else {
                echo 'No image uploaded.';
            }
        } else {
            echo 'Failed to add coffee.';
        }
    } elseif (isset($_POST['edit'])) {
        $coffee_id = $_POST['coffee_id'];
        $coffee_name = $_POST['coffee_name'];
        $coffee_price = $_POST['coffee_price'];
        $type_id = $_POST['type_id'];
        $coffee_image = null; // ใช้ภาพเดิมก่อน

        // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
        if (isset($_FILES['coffee_image']) && $_FILES['coffee_image']['error'] == UPLOAD_ERR_OK) {
            $image_name = 'img/' . $coffee_id . '.jpg';
            $target_path = '../' . $image_name;

            // ลบรูปภาพเก่า
            $old_image = CoffeeApp::getCoffeeImage($coffee_id);
            if ($old_image && file_exists('../' . $old_image)) {
                unlink('../' . $old_image);
            }

            // ตรวจสอบและย้ายไฟล์ภาพไปยังโฟลเดอร์ img
            $image = imagecreatefromstring(file_get_contents($_FILES['coffee_image']['tmp_name']));
            if ($image !== false && imagejpeg($image, $target_path)) {
                imagedestroy($image); // ทำลายภาพจากหน่วยความจำ
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