<?php
class CoffeeApp extends Config
{
    public static function getCoffees()
    {
        $pdo = self::connect();

        if ($pdo === null) {
            return [];
        }

        try {
            $stmt = $pdo->prepare("SELECT `coffee_id`, `coffee_name`, `coffee_price`, `coffee_image`, `type_id` FROM `coffees`");
            $stmt->execute();
            $coffees = $stmt->fetchAll();
            return $coffees;
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return [];
        }
    }

    // เพิ่มสินค้ากาแฟใหม่
    public static function addCoffee($coffee_name, $coffee_price, $coffee_image, $type_id)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO coffees (coffee_name, coffee_price, coffee_image, type_id) VALUES (:coffee_name, :coffee_price, :coffee_image, :type_id)");
            $stmt->bindParam(':coffee_name', $coffee_name);
            $stmt->bindParam(':coffee_price', $coffee_price);
            $stmt->bindParam(':coffee_image', $coffee_image);
            $stmt->bindParam(':type_id', $type_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    // แก้ไขข้อมูลสินค้ากาแฟ
    public static function updateCoffee($coffee_id, $coffee_name, $coffee_price, $coffee_image, $type_id)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            // ดึงข้อมูลรูปภาพเก่าจากฐานข้อมูล
            $stmt = $pdo->prepare("SELECT coffee_image FROM coffees WHERE coffee_id = :coffee_id");
            $stmt->bindParam(':coffee_id', $coffee_id);
            $stmt->execute();
            $old_image = $stmt->fetchColumn(); // ชื่อไฟล์รูปภาพเก่า

            // ถ้ามีการอัปโหลดรูปใหม่
            if ($coffee_image) {
                $new_image_name = $coffee_id . '.' . pathinfo($coffee_image, PATHINFO_EXTENSION); // ตั้งชื่อรูปตาม coffee_id

                // ตรวจสอบว่ามีรูปเก่าอยู่และลบรูปเก่าออก
                if ($old_image && file_exists('../img/' . $old_image)) {
                    unlink('../img/' . $old_image);
                }

                // ย้ายไฟล์รูปใหม่ไปที่โฟลเดอร์ img
                move_uploaded_file($coffee_image['tmp_name'], '../img/' . $new_image_name);

                // อัปเดตข้อมูลในฐานข้อมูลด้วยชื่อไฟล์ใหม่
                $stmt = $pdo->prepare("UPDATE coffees SET coffee_name = :coffee_name, coffee_price = :coffee_price, coffee_image = :coffee_image, type_id = :type_id WHERE coffee_id = :coffee_id");
                $stmt->bindParam(':coffee_image', $new_image_name);
            } else {
                // ถ้าไม่มีการอัปโหลดรูปใหม่ให้อัปเดตข้อมูลยกเว้นรูปภาพ
                $stmt = $pdo->prepare("UPDATE coffees SET coffee_name = :coffee_name, coffee_price = :coffee_price, type_id = :type_id WHERE coffee_id = :coffee_id");
            }

            // อัปเดตข้อมูลอื่นๆ
            $stmt->bindParam(':coffee_name', $coffee_name);
            $stmt->bindParam(':coffee_price', $coffee_price);
            $stmt->bindParam(':type_id', $type_id);
            $stmt->bindParam(':coffee_id', $coffee_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
            return false;
        }
    }



    // ลบสินค้ากาแฟ
    public static function deleteCoffee($coffee_id)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM coffees WHERE coffee_id = :coffee_id");
            $stmt->bindParam(':coffee_id', $coffee_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
            return false;
        }
    }


    // ดึงข้อมูลหมวดหมู่ทั้งหมด
    public static function getCoffeeTypes()
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return [];
        }

        try {
            $stmt = $pdo->prepare("SELECT `type_id`, `type_name` FROM `coffee_types`");
            $stmt->execute();
            $coffeeTypes = $stmt->fetchAll();
            return $coffeeTypes;
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return [];
        }
    }

    // เพิ่มหมวดหมู่ใหม่
    public static function addCoffeeType($type_name)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO coffee_types (type_name) VALUES (:type_name)");
            $stmt->bindParam(':type_name', $type_name);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    // แก้ไขหมวดหมู่
    public static function updateCoffeeType($type_id, $type_name)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            $stmt = $pdo->prepare("UPDATE coffee_types SET type_name = :type_name WHERE type_id = :type_id");
            $stmt->bindParam(':type_name', $type_name);
            $stmt->bindParam(':type_id', $type_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
            return false;
        }
    }

    // ลบหมวดหมู่
    public static function deleteCoffeeType($type_id)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM coffee_types WHERE type_id = :type_id");
            $stmt->bindParam(':type_id', $type_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
            return false;
        }
    }
}
