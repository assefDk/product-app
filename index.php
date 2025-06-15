<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// قراءة الملف وتحويل JSON إلى مصفوفة
$products = json_decode(file_get_contents('products.json'), true);

if (!is_array($products)) {
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>قائمة المنتجات</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { direction: rtl; background: #f0f0f0; font-family: Arial; }
    .product { background: #fff; border: 1px solid #ccc; padding: 10px; margin: 10px; width: 200px; border-radius: 10px; display: inline-block; vertical-align: top; }
    .product img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; }
    .product h3 { margin: 5px 0; }
    .product p { color: green; font-weight: bold; }
    .btn-group { margin-top: 10px; }
  </style>
</head>
<body>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0 text-center flex-grow-1">عرض المنتجات</h1>
    <a href="logout.php" class="btn btn-danger btn-sm ms-3">تسجيل خروج</a>
  </div>

  <div class="d-flex justify-content-center mb-4">
    <a href="add.php" class="btn btn-success">➕ إضافة منتج جديد</a>
  </div>

  <?php if (count($products) === 0): ?>
    <p class="text-center">لا توجد منتجات للعرض حالياً.</p>
  <?php else: ?>
    <?php foreach ($products as $index => $product): ?>
      <div class="product">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p><?= htmlspecialchars($product['price']) ?> ريال</p>
        <div class="btn-group d-flex gap-2">
          <a href="edit.php?index=<?= $index ?>" class="btn btn-primary btn-sm flex-fill">تعديل</a>
          <a href="delete.php?index=<?= $index ?>" class="btn btn-danger btn-sm flex-fill" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">حذف</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
