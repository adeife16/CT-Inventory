<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ProductService
{
    protected $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('app/products.json');
        if (!File::exists($this->filePath)) {
            File::put($this->filePath, json_encode([]));
        }
    }

    public function getAll()
    {
        return json_decode(File::get($this->filePath), true);
    }

    public function save(array $data)
    {
        $date = date('Y-m-d h:i:s A');
        $products = $this->getAll();
        $data['id'] = $this->generateId($products);
        $data['created_at'] = $date;
        $products[] = $data;
        File::put($this->filePath, json_encode($products, JSON_PRETTY_PRINT));
        return $data;
    }

    protected function generateId($products)
    {
        if (empty($products)) {
            return 1;
        }

        $lastProduct = end($products);
        return $lastProduct['id'] + 1;
    }
}
