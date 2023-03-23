<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('products', \App\Http\Controllers\ProductController::class)
    ->only(['index', 'store', 'update', 'destroy']);

Route::apiResource('category', \App\Http\Controllers\CategoryController::class)
    ->only(['store', 'destroy']);
