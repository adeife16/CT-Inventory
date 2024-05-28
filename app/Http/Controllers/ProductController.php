<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

/**
 * Class ProductController
 *
 * This controller class is responsible for handling the Product related requests.
 * It uses the ProductService to interact with the Products data.
 */
class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * ProductController constructor.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display the index page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Store a new product.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function store(Request $request)
    {
        // Validate the request data
        $data = $request->only(['name', 'quantity', 'price']);
        $data['quantity'] = (int)$data['quantity'];
        $data['price'] = (float)$data['price'];
        $data['total_value'] = $data['quantity'] * $data['price'];

        // Save the product
        $product = $this->productService->save($data);

        return response()->json(['status' => 'success', 'product' => $product]);
    }

    /**
     * Get all products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts()
    {
        // Get all products
        $products = $this->productService->getAll();

        return response()->json(['status' => 'success', 'products' => $products]);
    }
}
