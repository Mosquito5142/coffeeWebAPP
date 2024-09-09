<?php
include 'config/config.php';
include 'function/get_coffeeapp.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// ดึงข้อมูลหมวดหมู่
$coffeeTypes = CoffeeApp::getCoffeeTypes();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการหมวดหมู่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar">
                <?php include 'components/sideBar.php'; ?>
            </div>
            <div class="col-md-9">
                <h1 class="mt-5">จัดการหมวดหมู่</h1>

                <!-- ปุ่มเปิด Modal เพิ่มหมวดหมู่ -->
                <div class="text-end me-5">
                    <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addModal">เพิ่มหมวดหมู่</button>
                </div>

                <!-- แสดงข้อมูลหมวดหมู่ -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>ชื่อหมวดหมู่</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coffeeTypes as $type): ?>
                            <tr>
                                <td><?php echo $type['type_name']; ?></td>
                                <td>
                                    <!-- ปุ่มแก้ไข เปิด Modal -->
                                    <button class="btn btn-outline-warning" onclick="openEditModal('<?php echo $type['type_id']; ?>', '<?php echo $type['type_name']; ?>')">แก้ไข</button>

                                    <!-- ปุ่มลบหมวดหมู่ -->
                                    <button class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $type['type_id']; ?>)">ลบ</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal เพิ่มหมวดหมู่ -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">เพิ่มหมวดหมู่</h5>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-category-form">
                        <div class="mb-3">
                            <label for="type_name" class="form-label">ชื่อหมวดหมู่</label>
                            <input type="text" name="type_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-outline-dark">เพิ่มหมวดหมู่</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal แก้ไขหมวดหมู่ -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขหมวดหมู่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-category-form">
                        <input type="hidden" name="type_id" id="edit-type-id">
                        <div class="mb-3">
                            <label for="type_name" class="form-label">ชื่อหมวดหมู่</label>
                            <input type="text" name="type_name" id="edit-type-name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">แก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ฟังก์ชันเปิด Modal แก้ไขหมวดหมู่
        function openEditModal(typeId, typeName) {
            $('#edit-type-id').val(typeId);
            $('#edit-type-name').val(typeName);
            $('#editModal').modal('show');
        }

        // ฟอร์มเพิ่มหมวดหมู่
        $('#add-category-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'AC/manage_categories_post.php',
                data: $(this).serialize() + '&add=1',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มหมวดหมู่สำเร็จ!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'small-toast'
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });

        // ฟอร์มแก้ไขหมวดหมู่
        $('#edit-category-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'AC/manage_categories_post.php',
                data: $(this).serialize() + '&edit=1',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขหมวดหมู่สำเร็จ!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'small-toast'
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });

        function confirmDelete(typeId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบหมวดหมู่นี้หรือไม่?",
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
                        url: 'AC/manage_categories_post.php',
                        data: { delete: typeId },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบหมวดหมู่สำเร็จ!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'small-toast'
                                }
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
