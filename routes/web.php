<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', [ProductController::class, 'index']); // load products with data

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);


Route::get('categories/{category}/activate', [CategoryController::class, 'activate'])->name('categories.activate');
Route::get('categories/{category}/deactivate', [CategoryController::class, 'deactivate'])->name('categories.deactivate');


