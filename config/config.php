<?php 
class Config {
    public static $DB_HOST = 'localhost';
    public static $DB_NAME = 'coffeeapp';
    public static $DB_USER = 'root';
    public static $DB_PASSWORD = 'rootroot';

    // สร้างเมธอดสำหรับเชื่อมต่อฐานข้อมูล
    public static function connect() {
        try {
            // สร้าง Data Source Name (DSN)
            $dsn = 'mysql:host=' . self::$DB_HOST . ';dbname=' . self::$DB_NAME;
            // ตัวเลือกการตั้งค่าการเชื่อมต่อ PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            // เชื่อมต่อฐานข้อมูล
            $pdo = new PDO($dsn, self::$DB_USER, self::$DB_PASSWORD, $options);
            return $pdo; // ส่งกลับ object PDO เพื่อใช้งานต่อ
        } catch (PDOException $e) {
            // จัดการข้อผิดพลาด
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}
?>