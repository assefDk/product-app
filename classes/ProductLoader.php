<?php

class ProductLoader
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function loadProducts(): array
    {
        if (!file_exists($this->filePath)) {
            $msg = "ملف المنتجات غير موجود: {$this->filePath}";
            error_log($msg);
            throw new RuntimeException($msg);
        }

        $jsonContent = file_get_contents($this->filePath);

        if ($jsonContent === false) {
            $msg = "فشل في قراءة ملف المنتجات: {$this->filePath}";
            error_log($msg);
            throw new RuntimeException($msg);
        }

        $products = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $msg = "خطأ في تحويل JSON: " . json_last_error_msg();
            error_log($msg);
            throw new RuntimeException($msg);
        }

        if (!is_array($products)) {
            $msg = "بيانات المنتجات ليست مصفوفة صحيحة.";
            error_log($msg);
            throw new RuntimeException($msg);
        }

        return $products;
    }
}
