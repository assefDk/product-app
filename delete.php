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

// حذف صورة المنتج من المجلد (اختياري)
$imagePath = $products[$index]['image'];
if (file_exists($imagePath)) {
    unlink($imagePath);
}

// حذف المنتج من المصفوفة
array_splice($products, $index, 1);

// حفظ التغييرات
file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header("Location: index.php");
exit;


