<?php
session_start();

// التأكد من تسجيل الدخول
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// التأكد من وجود ID في الرابط
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// مسار ملف المنتجات
$productsFile = __DIR__ . '/../data/products.json';

// قراءة المنتجات
$products = json_decode(file_get_contents($productsFile), true);
if (!is_array($products)) {
    $products = [];
}

// دالة للعثور على المنتج باستخدام ID وإرجاع المنتج والفهرس
function getProductById($products, $id) {
    foreach ($products as $index => $product) {
        if (isset($product['id']) && $product['id'] == $id) {
            return ['product' => $product, 'index' => $index];
        }
    }
    return null;
}

$result = getProductById($products, $id);

// التحقق من وجود المنتج
if ($result === null) {
    header('Location: index.php');
    exit;
}

// حذف المنتج من المصفوفة
unset($products[$result['index']]);
$products = array_values($products); // إعادة ترتيب الفهارس لتجنب وجود فهارس مفقودة

// حفظ البيانات المحدثة
file_put_contents($productsFile, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// إعادة التوجيه إلى الصفحة الرئيسية
header('Location: index.php');
exit;
