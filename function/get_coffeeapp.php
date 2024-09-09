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
            $stmt->execute();
            return $pdo->lastInsertId(); // คืนค่า coffee_id ที่เพิ่มใหม่
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    // อัปเดตรูปภาพของกาแฟ
    public static function updateCoffeeImage($coffee_id, $coffee_image)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return false;
        }

        try {
            $stmt = $pdo->prepare("UPDATE coffees SET coffee_image = :coffee_image WHERE coffee_id = :coffee_id");
            $stmt->bindParam(':coffee_image', $coffee_image);
            $stmt->bindParam(':coffee_id', $coffee_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
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
            // อัปเดตข้อมูลในฐานข้อมูล
            if ($coffee_image) {
                $stmt = $pdo->prepare("UPDATE coffees SET coffee_name = :coffee_name, coffee_price = :coffee_price, coffee_image = :coffee_image, type_id = :type_id WHERE coffee_id = :coffee_id");
                $stmt->bindParam(':coffee_image', $coffee_image);
            } else {
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

    // ดึงข้อมูลรูปภาพของกาแฟ
    public static function getCoffeeImage($coffee_id)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return null;
        }

        try {
            $stmt = $pdo->prepare("SELECT coffee_image FROM coffees WHERE coffee_id = :coffee_id");
            $stmt->bindParam(':coffee_id', $coffee_id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return null;
        }
    }
    public static function processOrder($total_amount, $received_amount, $change_amount, $cart_items)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }

        try {
            // เริ่มต้น transaction
            $pdo->beginTransaction();

            // บันทึกข้อมูลในตาราง orders
            $stmt = $pdo->prepare("
                INSERT INTO orders (order_date, total_amount, tax_amount, discount_amount)
                VALUES (NOW(), :total_amount, 0, 0)
            ");
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->execute();
            $order_id = $pdo->lastInsertId(); // ดึง order_id ที่พึ่งบันทึก

            // บันทึกข้อมูลสินค้าในตาราง order_items
            $stmt_items = $pdo->prepare("
                INSERT INTO order_items (order_id, coffee_id, quantity, unit_price, total_price)
                VALUES (:order_id, :coffee_id, :quantity, :unit_price, :total_price)
            ");
            foreach ($cart_items as $item) {
                $stmt_items->bindParam(':order_id', $order_id);
                $stmt_items->bindParam(':coffee_id', $item['coffee_id']);
                $stmt_items->bindParam(':quantity', $item['quantity']);
                $stmt_items->bindParam(':unit_price', $item['unit_price']);
                $stmt_items->bindParam(':total_price', $item['total_price']);
                $stmt_items->execute();
            }

            // บันทึกข้อมูลสำเร็จ
            $pdo->commit();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            // มีข้อผิดพลาด
            $pdo->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ฟังก์ชันดึงข้อมูลยอดขาย
    public static function getSalesReport($startDate = null, $endDate = null)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }

        try {
            if ($startDate === null) {
                $startDate = date('Y-m-d');
            }
            if ($endDate === null) {
                $endDate = date('Y-m-d');
            }

            $stmt = $pdo->prepare("
                SELECT 
                    o.order_id,
                    o.order_date,
                    p.coffee_name,
                    o.total_amount,
                    oi.unit_price,
                    SUM(oi.quantity) AS total_quantity,
                    SUM(oi.total_price) AS total_sales
                FROM order_items oi
                JOIN coffees p ON oi.coffee_id = p.coffee_id
                JOIN orders o ON oi.order_id = o.order_id
                WHERE DATE(o.order_date) BETWEEN :startDate AND :endDate
                GROUP BY o.order_id, o.order_date, p.coffee_name
                ORDER BY o.order_date DESC, total_sales DESC
            ");
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();
            $salesReport = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['status' => 'success', 'data' => $salesReport];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ฟังก์ชันดึงจำนวนคำสั่งซื้อ
    public static function getOrderCount($startDate = null, $endDate = null)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return 0;
        }

        try {
            if ($startDate === null) {
                $startDate = date('Y-m-d');
            }
            if ($endDate === null) {
                $endDate = date('Y-m-d');
            }

            $stmt = $pdo->prepare("
                SELECT COUNT(*) AS order_count
                FROM orders
                WHERE DATE(order_date) BETWEEN :startDate AND :endDate
            ");
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // ฟังก์ชันดึงสินค้าที่ขายดี
    public static function getBestSellingProduct($startDate = null, $endDate = null)
    {
        $pdo = self::connect();
        if ($pdo === null) {
            return null;
        }

        try {
            if ($startDate === null) {
                $startDate = date('Y-m-d');
            }
            if ($endDate === null) {
                $endDate = date('Y-m-d');
            }

            $stmt = $pdo->prepare("
                SELECT 
                    p.coffee_name,
                    SUM(oi.quantity) AS total_quantity
                FROM order_items oi
                JOIN coffees p ON oi.coffee_id = p.coffee_id
                JOIN orders o ON oi.order_id = o.order_id
                WHERE DATE(o.order_date) BETWEEN :startDate AND :endDate
                GROUP BY p.coffee_name
                ORDER BY total_quantity DESC
                LIMIT 1
            ");
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>