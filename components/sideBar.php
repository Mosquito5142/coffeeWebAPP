<style>
.sidebar {
    height: 100vh; /* ความสูงเท่ากับหน้าจอ */

}
.insidebar {
  position: fixed; /* ตรึงตำแหน่ง Sidebar */

}
    </style>
<div class="insidebar d-flex flex-column flex-shrink-0 p-3 bg-light pt-5 " style="width: 280px; height: 100%;">
    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
      <i class="fas fa-coffee me-2" style="font-size: 32px;"></i>
      <span class="fs-4">กาแฟหรือแกฟะ</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li>
        <a href="#" class="nav-link link-dark">
          <i class="fas fa-tachometer-alt me-2"></i>
          Dashboard
        </a>
      </li>
      <li>
        <a href="manage_categories.php" class="nav-link link-dark">
          <i class="fas fa-table me-2"></i>
          จัดการหมวดหมู่
        </a>
      </li>
      <li>
        <a href="manage_product.php" class="nav-link link-dark">
          <i class="fas fa-boxes me-2"></i>
          จัดการสินค้า
        </a>
      </li>
      <li>
        <a href="pos.php" class="nav-link link-dark">
          <i class="fas fa-cash-register me-2"></i>
          ระบบ POS
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link link-dark" aria-current="page">
          <i class="fas fa-file-invoice-dollar me-2"></i>
          รายงานรายรับเงิน
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link link-dark" aria-current="page">
          <i class="fas fa-sign-out-alt me-2"></i>
          ออกจากระบบ
        </a>
      </li>
    </ul>
    <hr>
  </div>
