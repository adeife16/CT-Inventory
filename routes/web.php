<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
 * Define the routes for the web application.
 * Each route has a URL pattern and a controller method to call when the URL is accessed.
 */
Route::get('/', [ProductController::class, 'index']); // Route for the index page
Route::post('/products', [ProductController::class, 'store']); // Route for storing a new product
Route::get('/products', [ProductController::class, 'getProducts']); // Route for getting all products


