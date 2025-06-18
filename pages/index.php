<?php
require_once __DIR__ . '/../classes/ProductLoader.php';

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$loader = new ProductLoader('C:/xampp/data/products.json');
$products = $loader->loadProducts();

?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>قائمة المنتجات</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body {
      direction: rtl;
      background: #f0f0f0;
      font-family: Arial;
    }
    .product {
      background: #fff;
      border: 1px solid #ccc;
      padding: 10px;
      margin: 10px;
      width: 220px;
      border-radius: 10px;
      display: inline-block;
      vertical-align: top;
    }
    .product img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 5px;
    }
    .product h3 {
      margin: 5px 0;
      font-size: 18px;
    }
    .product p {
      color: green;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .product ul {
      padding-right: 18px;
      list-style-type: disc;
      margin-bottom: 5px;
    }
    .product ul li {
      font-size: 13px;
      color: #555;
      line-height: 1.4;
    }
    .btn-group {
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0 text-center flex-grow-1">عرض المنتجات</h1>
    <a href="logout.php" class="btn btn-danger btn-sm ms-3">تسجيل خروج</a>
  </div>

  <!-- <div class="d-flex justify-content-center mb-4">
    <a href="add.php" class="btn btn-success">➕ إضافة منتج جديد</a>
  </div> -->

  <?php if (count($products) === 0): ?>
    <p class="text-center">لا توجد منتجات للعرض حالياً.</p>
  <?php else: ?>
    <?php foreach ($products as $product): ?>
      <div class="product">
        <!-- <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($product['name']) ?>"> -->
        <img src="../assets/images/img1.jpeg">
        <h3><?= htmlspecialchars($product['name']) ?></h3>

        <?php if (isset($product['price'])): ?>
          <p><?= htmlspecialchars($product['price']) ?> ريال</p>
        <?php endif; ?>

        <ul>
          <?php
          if (isset($product['description'])) {
              if (is_array($product['description'])) {
                  $count = 0;
                  foreach ($product['description'] as $descItem) {
                      echo "<li>" . htmlspecialchars(trim($descItem)) . "</li>";
                      if (++$count >= 3) break;
                  }
              } else {
                  $sentences = preg_split('/[\.\n\r]+/', $product['description']);
                  $count = 0;
                  foreach ($sentences as $sentence) {
                      $trimmed = trim($sentence);
                      if ($trimmed !== '') {
                          echo "<li>" . htmlspecialchars($trimmed) . "</li>";
                          if (++$count >= 3) break;
                      }
                  }
              }
          }
          ?>
        </ul>

        <div class="btn-group d-flex gap-2">
          <a href="edit.php?id=<?= urlencode($product['id']) ?>" class="btn btn-primary btn-sm flex-fill">تعديل</a>
          <a href="delete.php?id=<?= urlencode($product['id']) ?>" class="btn btn-danger btn-sm flex-fill" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">حذف</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>

</body>
</html>
