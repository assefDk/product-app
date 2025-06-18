<?php
require_once '../../../classes/ProductLoader.php';

$loader = new ProductLoader('C:/xampp/data/products.json');
$products = $loader->loadProducts();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$product = null;
foreach ($products as $item) {
    if ($item['id'] === $id) {
        $product = $item;
        break;
    }
}

if (!$product) {
    echo "<h2 class='text-center mt-5'>المنتج غير موجود.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">الرجوع</a>
    <div class="card">
        <!-- <img src="<?= htmlspecialchars($product['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"> -->
        <!-- <img src="../../../assets/images/img1.jpeg" class="card-img-top "> -->

        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($product['name']) ?></h3>
            <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <h5>المميزات:</h5>
            <ul>
                <?php foreach ($product['features'] as $feature): ?>
                    <li><?= htmlspecialchars($feature) ?></li>
                <?php endforeach; ?>
            </ul>
            <h5>طريقة الاستخدام:</h5>
            <ul>
                <?php foreach ($product['usage'] as $use): ?>
                    <li><?= htmlspecialchars($use) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
