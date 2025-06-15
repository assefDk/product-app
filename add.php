<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // معالجة رفع الصورة
    $uploadDir = 'images/';
    $imageName = basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $newProduct = [
            'name' => $name,
            'price' => $price,
            'image' => $targetPath
        ];

        $products = json_decode(file_get_contents('products.json'), true);
        if (!is_array($products)) {
            $products = [];
        }

        $products[] = $newProduct;
        file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header("Location: index.php");
        exit;
    } else {
        echo "<p>❌ فشل رفع الصورة.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>إضافة منتج</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-center">إضافة منتج جديد</h2>
     <!-- زر العودة -->
    <div class="d-flex justify-content-center mb-3">
      <a href="index.php" class="btn btn-secondary btn-sm" style="min-width: 120px; white-space: nowrap;">
        ⬅️ العودة إلى قائمة المنتجات
      </a>
    </div>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">اسم المنتج:</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">السعر:</label>
        <input type="number" name="price" class="form-control" required>
      </div>

     <div class="mb-3">
      <label class="form-label">تحميل الصورة:</label>
      <input type="file" name="image" class="form-control" accept="image/*" required>
      <div class="form-text">اختر صورة من جهازك.</div>
    </div>

      <div class="mb-3">
        <img id="preview" src="#" alt="معاينة الصورة" class="img-thumbnail d-none" style="max-width: 300px; cursor: pointer;">
      </div>

      <button type="submit" class="btn btn-success w-100">➕ إضافة المنتج</button>
    </form>
  </div>
</div>



</body>
</html>
