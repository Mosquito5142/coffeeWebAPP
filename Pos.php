<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .product-card {
            max-width: 300px;
            margin: 0 auto;
            cursor: pointer;
        }

        .product-card img {
            height: 150px;
            object-fit: cover;
        }

        #cart-items {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .cart-card {
            width: 180px;
            height: 230px;
            margin: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 10px;
            box-sizing: border-box;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        .cart-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 5px;
        }

        .quantity-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .quantity-controls button {
            margin: 0 5px;
            width: 30px;
            height: 30px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
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
                        <button class="btn btn-outline-dark w-100">ชำระเงิน</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // ฟังก์ชันการค้นหา
            $('#search-input').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.search-item').filter(function() {
                    $(this).toggle($(this).data('name').toLowerCase().indexOf(value) > -1);
                });
            });

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
        });
    </script>
</body>

</html>