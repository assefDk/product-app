<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$dataFile = __DIR__ . '/../data/products.json';

$products = json_decode(file_get_contents($dataFile), true);
if (!is_array($products)) {
    $products = [];
}

function getProductById($products, $id) {
    foreach ($products as $index => $product) {
        if (isset($product['id']) && $product['id'] == $id) {
            return [$product, $index];
        }
    }
    return [null, null];
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die('لم يتم تحديد المنتج.');
}

list($product, $productIndex) = getProductById($products, $id);
if (!$product) {
    die('المنتج غير موجود.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    // تحويل نص features إلى مصفوفة حسب كل سطر جديد
    $featuresText = $_POST['features'] ?? '';
    $features = array_filter(array_map('trim', explode("\n", $featuresText)));

    // تحويل نص usage إلى مصفوفة حسب كل سطر جديد
    $usageText = $_POST['usage'] ?? '';
    $usage = array_filter(array_map('trim', explode("\n", $usageText)));

    $products[$productIndex]['name'] = $name;
    $products[$productIndex]['description'] = $description;
    $products[$productIndex]['features'] = $features;
    $products[$productIndex]['usage'] = $usage;

    file_put_contents($dataFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>تعديل المنتج</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
  <style>
    body { direction: rtl; background: #f0f0f0; font-family: Arial; padding: 20px; }
    form { background: #fff; padding: 20px; border-radius: 8px; max-width: 700px; margin: auto; }
    label { font-weight: bold; }
  </style>
</head>
<body>

<h2 class="text-center mb-4">تعديل المنتج</h2>

<form method="POST">
  <div class="mb-3">
    <label for="name" class="form-label">اسم المنتج:</label>
    <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>" />
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">الوصف:</label>
    <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars(is_array($product['description']) ? implode("\n", $product['description']) : $product['description']) ?></textarea>
    <small class="form-text text-muted">يمكنك كتابة أكثر من سطر.</small>
  </div>

  <div class="mb-3">
    <label for="features" class="form-label">المميزات (كل ميزة بسطر جديد):</label>
    <textarea id="features" name="features" class="form-control" rows="5"><?= htmlspecialchars(implode("\n", $product['features'] ?? [])) ?></textarea>
  </div>

  <div class="mb-3">
    <label for="usage" class="form-label">طريقة الاستخدام (كل نقطة بسطر جديد):</label>
    <textarea id="usage" name="usage" class="form-control" rows="5"><?= htmlspecialchars(implode("\n", $product['usage'] ?? [])) ?></textarea>
  </div>

  <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
  <a href="index.php" class="btn btn-secondary ms-2">إلغاء</a>
</form>

</body>
</html>
