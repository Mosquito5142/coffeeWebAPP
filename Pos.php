<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="pos.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar">
                <?php include 'components/sideBar.php'; ?>
            </div>
            <div class="col-sm">
                <div class="b-example-divider"></div>
                <div class="mt-5">
                    <h1 class="d-inline">ระบบ POS</h1>
                    <h4 class="d-inline"> [สำหรับแคชเชียร์]</h4>

                    <div class="input-group m-4">
                        <input type="text" id="search-input" class="form-control" placeholder="ค้นหาสินค้า..." aria-label="Search">
                    </div>

                    <!-- Table Header for Category: Coffee -->
                    <?php
                    include 'config/config.php';
                    include 'function/get_coffeeapp.php';

                    $coffeeTypes = CoffeeApp::getCoffeeTypes();
                    foreach ($coffeeTypes as $type) {
                        echo "<h3 class='mt-4'>หมวดหมู่: " . $type['type_name'] . "</h3>";
                        echo "<div class='row mt-3'>";

                        $coffees = CoffeeApp::getCoffees();
                        $hasProducts = false; // ตัวแปรเพื่อตรวจสอบว่ามีสินค้าหรือไม่
                        foreach ($coffees as $coffee) {
                            if ($coffee['type_id'] == $type['type_id']) {
                                $hasProducts = true; // พบสินค้าที่ตรงกับประเภทนี้
                                $product_name = $coffee['coffee_name'];
                                $product_price = $coffee['coffee_price'];
                                $product_image = $coffee['coffee_image'];
                    ?>
                                <div class="col-md-4 mb-4 add-to-cart search-item" data-name="<?php echo $product_name; ?>" data-price="<?php echo $product_price; ?>" data-image="<?php echo $product_image; ?>">
                                    <div class="card product-card">
                                        <img src="<?php echo $product_image; ?>" class="card-img-top" alt="<?php echo $product_name; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $product_name; ?></h5>
                                            <p class="card-text">ราคา: <?php echo $product_price; ?> บาท</p>
                                        </div>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                        if (!$hasProducts) {
                            echo "<p class='text-muted'>ไม่มีสินค้าในหมวดหมู่นี้</p>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-sm">
                <div class="mt-5 ms-5">
                    <h3>สรุปรายการ</h3>
                    <div id="cart-items">
                        <!-- รายการสินค้าที่เลือกจะถูกเพิ่มที่นี่ -->
                    </div>
                    <div class="mt-3">
                        <h4>รวม: <span id="total-price">0</span> บาท</h4>
                        <div class="mt-3 text-center">
                            <button class="btn btn-outline-dark w-100" id="checkout-button">ชำระเงิน</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">ชำระเงิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="total-amount" class="form-label">ยอดรวมที่ต้องชำระ</label>
                        <input type="text" class="form-control" id="total-amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="received-amount" class="form-label">รับเงินมา</label>
                        <input type="number" class="form-control" id="received-amount" placeholder="กรอกจำนวนเงินที่รับมา">
                    </div>
                    <div class="mb-3">
                        <label for="change-amount" class="form-label">เงินทอน</label>
                        <input type="text" class="form-control" id="change-amount" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="confirm-payment">ยืนยันการชำระเงิน</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let totalPrice = 0;

            function createCartCard(name, price, image) {
                return $(`
                <div class="card cart-card" data-name="${name}">
                    <span class="remove-btn"><i class="fas fa-times"></i></span>
                    <img src="${image}" alt="${name}">
                    <p class="cart-title">${name}</p>
                    <p class="cart-text">${price} บาท</p>
                    <div class="quantity-controls">
                        <button class="btn btn-sm btn-danger">-</button>
                        <span>1</span>
                        <button class="btn btn-sm btn-success">+</button>
                    </div>
                </div>
            `);
            }

            function updateTotalPrice(amount) {
                totalPrice += amount;
                $('#total-price').text(totalPrice);
            }

            $('.add-to-cart').on('click', function() {
                const name = $(this).data('name');
                const price = parseInt($(this).data('price'));
                const image = $(this).data('image');
                let quantity = 1;

                let existingCartItem = $(`.cart-card[data-name="${name}"]`);

                if (existingCartItem.length > 0) {
                    let quantityText = existingCartItem.find('.quantity-controls span');
                    let currentQuantity = parseInt(quantityText.text());
                    currentQuantity++;
                    quantityText.text(currentQuantity);
                    existingCartItem.find('.cart-text').text(price * currentQuantity + ' บาท');
                    updateTotalPrice(price);
                } else {
                    let cartCard = createCartCard(name, price, image);
                    cartCard.find('.remove-btn').on('click', function() {
                        let currentQuantity = parseInt(cartCard.find('.quantity-controls span').text());
                        updateTotalPrice(-price * currentQuantity);
                        cartCard.remove();
                    });

                    cartCard.find('.btn-success').on('click', function() {
                        quantity++;
                        cartCard.find('.quantity-controls span').text(quantity);
                        cartCard.find('.cart-text').text(price * quantity + ' บาท');
                        updateTotalPrice(price);
                    });

                    cartCard.find('.btn-danger').on('click', function() {
                        if (quantity > 1) {
                            quantity--;
                            cartCard.find('.quantity-controls span').text(quantity);
                            cartCard.find('.cart-text').text(price * quantity + ' บาท');
                            updateTotalPrice(-price);
                        }
                    });

                    $('#cart-items').append(cartCard);
                    updateTotalPrice(price);
                }
            });

            // แสดง Modal เมื่อกดปุ่ม "ชำระเงิน"
            $('#checkout-button').on('click', function() {
                $('#total-amount').val(totalPrice + ' บาท'); // แสดงยอดรวมใน modal
                $('#paymentModal').modal('show');
            });

            // คำนวณเงินทอนเมื่อกรอกจำนวนเงินที่รับมา
            $('#received-amount').on('input', function() {
                const receivedAmount = parseFloat($(this).val());
                const changeAmount = receivedAmount - totalPrice;
                $('#change-amount').val(changeAmount.toFixed(2));
            });

            // ยืนยันการชำระเงิน
            $('#confirm-payment').on('click', function() {
                // แสดง SweetAlert แทนการใช้ alert
                Swal.fire({
                    icon: 'success',
                    title: 'การชำระเงินสำเร็จ!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    // ล้างข้อมูลตะกร้าและยอดรวมหลังการชำระเงิน
                    $('#cart-items').empty();
                    $('#total-price').text('0');
                    totalPrice = 0;
                    $('#received-amount').val('');
                    $('#change-amount').val('');
                    $('#paymentModal').modal('hide'); // ปิด modal หลังการชำระเงินสำเร็จ
                });
            });

        });
    </script>
</body>

</html>