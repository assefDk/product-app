<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['index'])) {
    header("Location: index.php");
    exit;
}

$index = (int) $_GET['index'];
$products = json_decode(file_get_contents('products.json'), true);

if (!is_array($products) || !isset($products[$index])) {
    header("Location: index.php");
    exit;
}

$product = $products[$index];

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // إذا تم رفع صورة جديدة
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'images/';
        $imageName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // حذف الصورة القديمة
            if (file_exists($product['image'])) {
                unlink($product['image']);
            }
            $product['image'] = $targetPath;
        } else {
            echo "<p>❌ فشل رفع الصورة الجديدة.</p>";
        }
    }

    // تحديث البيانات
    $product['name'] = $name;
    $product['price'] = $price;

    $products[$index] = $product;
    file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>تعديل المنتج</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-center">تعديل المنتج</h2>
    <div class="d-flex justify-content-center mb-3">
      <a href="index.php" class="btn btn-secondary btn-sm" style="min-width: 120px; white-space: nowrap;">⬅️ العودة إلى قائمة المنتجات</a>
    </div>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">اسم المنتج:</label>
        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">السعر:</label>
        <input type="number" name="price" class="form-control" required value="<?= htmlspecialchars($product['price']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">الصورة الحالية:</label><br>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="صورة المنتج" style="max-width: 300px; border-radius: 5px;">
      </div>

      <div class="mb-3">
        <label class="form-label">تحميل صورة جديدة (اختياري):</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>

      <button type="submit" class="btn btn-primary w-100">💾 حفظ التعديلات</button>
    </form>
  </div>
</div>
</body>
</html>
