<?php
include 'config/config.php';
include 'function/get_coffeeapp.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// รับค่าวันที่จากฟอร์ม ถ้าไม่มีให้ใช้วันที่ปัจจุบัน
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$result = CoffeeApp::getSalesReport($startDate, $endDate);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายงานยอดขาย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar">
                <?php include 'components/sideBar.php'; ?>
            </div>

            <div class="col-md-9">
                <h1 class="my-4">รายงานยอดขาย</h1>

                <!-- ฟอร์มเลือกช่วงวันที่ -->
                <form method="GET" class="mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($startDate); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($endDate); ?>">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-outline-dark">ดูรายงาน</button>
                        </div>
                    </div>
                </form>

                <!-- แสดงผลตารางรายงานยอดขาย -->
                <?php if ($result['status'] === 'success') : ?>
                    <div class="table-responsive">
                        <table id="sales-report" class="table table-bordered">
                            <thead class="table">
                                <tr>
                                    <th>รหัสออเดอร์</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>ยอดขายรวม (บาท)</th>
                                    <th>รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $currentOrderId = null;
                                foreach ($result['data'] as $row) :
                                    if ($currentOrderId !== $row['order_id']) :
                                        if ($currentOrderId !== null) : ?>
                            </tbody>
                        </table>
                    </div>
                    </td>
                    </tr>
                <?php endif;
                                        $currentOrderId = $row['order_id']; ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['total_amount'])); ?> บาท</td>
                    <td>
                        <button class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#order-<?php echo $row['order_id']; ?>" aria-expanded="false" aria-controls="order-<?php echo $row['order_id']; ?>">
                            ดูรายละเอียด
                        </button>
                        <div class="collapse mt-2" id="order-<?php echo $row['order_id']; ?>">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ชื่อสินค้า</th>
                                        <th>ราคา(ชิ้น)</th>
                                        <th>จำนวนที่ขายได้</th>
                                        <th>ยอดขายรวม (บาท)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php endif; ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['coffee_name']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($row['unit_price'], 2)); ?> บาท</td>
                                    <td><?php echo htmlspecialchars($row['total_quantity']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($row['total_sales'], 2)); ?> บาท</td>
                                </tr>

                            <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <?php if ($currentOrderId !== null) : ?>
                    </tbody>
                    </table>
            </div>
            </td>
            </tr>
        <?php endif; ?>
        </tbody>
        </table>
        </div>
    <?php else : ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($result['message']); ?>
        </div>
    <?php endif; ?>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sales-report').DataTable();
        });
    </script>
</body>

</html>