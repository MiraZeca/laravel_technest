<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Generalni scraping
Route::get('/scrape', [ProductController::class, 'scrapeAndStore'])->name('products.scrape');

// Prikaz proizvoda
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Forma za import proizvoda
Route::get('products/import', [ProductController::class, 'showImportForm'])->name('products.import.form');

// Import proizvoda iz CSV
Route::post('products/import', [ProductController::class, 'importProductsFromCSV'])->name('products.import');

// Scraping specifičan za Gigatron proizvode (Apple tablete)
Route::get('/scrape-gigatron', [ProductController::class, 'scrapeGigatronAppleTablets'])->name('scrape.gigatron');
