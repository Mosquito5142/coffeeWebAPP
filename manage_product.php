<?php
include 'config/config.php';
include 'function/get_coffeeapp.php';

// ดึงข้อมูลสินค้ากาแฟ
$coffees = CoffeeApp::getCoffees();
$coffeeTypes = CoffeeApp::getCoffeeTypes();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้ากาแฟ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>จัดการสินค้ากาแฟ</h1>
        <!-- ปุ่มเปิด Modal เพิ่มสินค้า -->
        <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addModal">เพิ่มสินค้า</button>

        <!-- ตารางแสดงสินค้ากาแฟ -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา</th>
                    <th>หมวดหมู่</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coffees as $coffee): ?>
                    <tr>
                        <td><?php echo $coffee['coffee_name']; ?></td>
                        <td><?php echo $coffee['coffee_price']; ?> บาท</td>
                        <td><?php echo $coffee['type_id']; ?></td>
                        <td>
                            <!-- ปุ่มเปิด Modal แก้ไข -->
                            <button class="btn btn-outline-warning" onclick="openEditModal('<?php echo $coffee['coffee_id']; ?>', '<?php echo $coffee['coffee_name']; ?>', '<?php echo $coffee['coffee_price']; ?>', '<?php echo $coffee['coffee_image']; ?>', '<?php echo $coffee['type_id']; ?>')">แก้ไข</button>
                            <!-- ปุ่มลบสินค้า -->
                            <button class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $coffee['coffee_id']; ?>)">ลบ</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal เพิ่มสินค้า -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">เพิ่มสินค้ากาแฟ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-coffee-form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="coffee_name" class="form-label">ชื่อสินค้า</label>
                            <input type="text" name="coffee_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="coffee_price" class="form-label">ราคา</label>
                            <input type="number" name="coffee_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="coffee_image" class="form-label">เลือกรูปภาพ</label>
                            <input type="file" name="coffee_image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="type_id" class="form-label">หมวดหมู่</label>
                            <select name="type_id" class="form-control">
                                <?php foreach ($coffeeTypes as $type): ?>
                                    <option value="<?php echo $type['type_id']; ?>"><?php echo $type['type_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-dark">เพิ่มสินค้า</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal แก้ไขสินค้า -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขสินค้ากาแฟ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-coffee-form" enctype="multipart/form-data">
                        <input type="hidden" name="coffee_id" id="edit-coffee-id">
                        <div class="mb-3">
                            <label for="coffee_name" class="form-label">ชื่อสินค้า</label>
                            <input type="text" name="coffee_name" id="edit-coffee-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="coffee_price" class="form-label">ราคา</label>
                            <input type="number" name="coffee_price" id="edit-coffee-price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="coffee_image" class="form-label">เลือกรูปภาพใหม่ (ถ้าต้องการเปลี่ยน)</label>
                            <input type="file" name="coffee_image" id="edit-coffee-image" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="type_id" class="form-label">หมวดหมู่</label>
                            <select name="type_id" id="edit-type-id" class="form-control">
                                <?php foreach ($coffeeTypes as $type): ?>
                                    <option value="<?php echo $type['type_id']; ?>"><?php echo $type['type_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-dark">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- รวม Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openEditModal(coffeeId, coffeeName, coffeePrice, coffeeImage, typeId) {
            $('#edit-coffee-id').val(coffeeId);
            $('#edit-coffee-name').val(coffeeName);
            $('#edit-coffee-price').val(coffeePrice);
            $('#edit-type-id').val(typeId);
            $('#editModal').modal('show');
        }

        $('#add-coffee-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('add', '1');
            $.ajax({
                type: 'POST',
                url: 'AC/manage_coffees_post.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มสินค้าสำเร็จ!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });

        $('#edit-coffee-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('edit', '1');
            $.ajax({
                type: 'POST',
                url: 'AC/manage_coffees_post.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขสินค้าสำเร็จ!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });

        function confirmDelete(coffeeId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบสินค้านี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'GET',
                        url: 'AC/manage_coffees_post.php',
                        data: {
                            delete: coffeeId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสินค้าสำเร็จ!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            })
        }
    </script>
</body>

</html>