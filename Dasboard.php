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
$salesReport = CoffeeApp::getSalesReport($startDate, $endDate);
$orderCount = CoffeeApp::getOrderCount($startDate, $endDate);
$bestSellingProduct = CoffeeApp::getBestSellingProduct($startDate, $endDate);
$coffeeTypes = CoffeeApp::getCoffeeTypes();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar">
                <?php include 'components/sideBar.php'; ?>
            </div>
            <div class="col-md-9">
                <div class="container mt-5">
                    <h1>Dashboard</h1>

                    <!-- ฟอร์มเลือกช่วงวันที่ -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($startDate); ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($endDate); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">ดูรายงาน</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <!-- ยอดขายรายวัน -->
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">ยอดขายช่วงวันที่</h5>
                                    <p class="card-text">
                                        <?php 
                                        $totalSales = 0;
                                        if ($salesReport['status'] === 'success') {
                                            foreach ($salesReport['data'] as $report) {
                                                $totalSales += $report['total_sales'];
                                            }
                                        }
                                        echo number_format($totalSales, 2) . ' บาท';
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- จำนวนคำสั่งซื้อ -->
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">จำนวนคำสั่งซื้อ</h5>
                                    <p class="card-text"><?php echo $orderCount; ?> รายการ</p>
                                </div>
                            </div>
                        </div>

                        <!-- สินค้าที่ขายดี -->
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">สินค้าที่ขายดี</h5>
                                    <p class="card-text">
                                        <?php 
                                        if ($bestSellingProduct) {
                                            echo $bestSellingProduct['coffee_name'] . ' (' . $bestSellingProduct['total_quantity'] . ' รายการ)';
                                        } else {
                                            echo 'ไม่มีข้อมูล';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- จำนวนหมวดหมู่สินค้า -->
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">หมวดหมู่สินค้า</h5>
                                    <p class="card-text"><?php echo count($coffeeTypes); ?> หมวดหมู่</p>
                                </div>
                            </div>
                        </div>

                        <!-- กราฟยอดขายรายวัน -->
                        <div class="col-md-12">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // สร้างกราฟยอดขายรายวัน
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    <?php 
                    if ($salesReport['status'] === 'success') {
                        foreach ($salesReport['data'] as $report) {
                            echo '"' . $report['order_date'] . '",';
                        }
                    }
                    ?>
                ],
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: [
                        <?php 
                        if ($salesReport['status'] === 'success') {
                            foreach ($salesReport['data'] as $report) {
                                echo $report['total_sales'] . ',';
                            }
                        }
                        ?>
                    ],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>