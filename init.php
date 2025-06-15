<?php
require_once __DIR__ . '/includes/insert_once.php';

insertInitialProducts();

// إعادة توجيه بعد الإدخال لمرة واحدة
header("Location: pages/index.php");
exit;



