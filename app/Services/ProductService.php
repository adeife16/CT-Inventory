<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

/**
 * Class ProductService
 *
 * This class handles the operations related to the Products data.
 * It provides methods to get all products, save a new product, and generate an id.
 */
class ProductService
{
    /**
     * The path to the products.json file.
     *
     * @var string
     */
    protected $filePath;

    /**
     * ProductService constructor.
     *
     * Initializes the file path if the file doesn't exist.
     */
    public function __construct()
    {
        $this->filePath = storage_path('app/products.json');
        if (!File::exists($this->filePath)) {
            File::put($this->filePath, json_encode([]));
        }
    }

    /**
     * Get all products.
     *
     * @return array The products data.
     */
    public function getAll()
    {
        return json_decode(File::get($this->filePath), true);
    }

    /**
     * Save a new product.
     *
     * @param array $data The product data.
     * @return array The saved product data.
     */
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

    /**
     * Generate an id for a new product.
     *
     * @param array $products The existing products data.
     * @return int The generated id.
     */
    protected function generateId($products)
    {
        if (empty($products)) {
            return 1;
        }

        $lastProduct = end($products);
        return $lastProduct['id'] + 1;
    }
}

