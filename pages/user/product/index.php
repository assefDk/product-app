<?php
require_once '../../../classes/ProductLoader.php';

$loader = new ProductLoader('C:/xampp/data/products.json');
$products = $loader->loadProducts();
?>

<!DOCTYPE html >
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4 text-center">قائمة المنتجات</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <!-- <img src="<?= htmlspecialchars($product['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"> -->
                    <img src="../../../assets/images/img1.jpeg">

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <a href="details.php?id=<?= $product['id'] ?>" class="btn btn-primary">عرض التفاصيل</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<?php include '../layout/footer.php'; ?>


</body>
</html>
