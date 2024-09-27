<?php

use App\Http\Controllers\TypeController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\LineLoginController;

// Route::apiResource('products', ProductController::class);

Route::apiResource('types', TypeController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {

    // 所有登入的用戶都可以查看產品
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    // 只有管理員可以進行新增操作
    Route::post('products', [ProductController::class, 'store'])->middleware(\App\Http\Middleware\RoleMiddleware::class . ':管理員');

    // 只有管理員可以進行更新操作
    Route::patch('products/{product}', [ProductController::class, 'update'])->middleware(\App\Http\Middleware\RoleMiddleware::class . ':管理員');

    // 只有管理員可以進行刪除操作
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->middleware(\App\Http\Middleware\RoleMiddleware::class . ':管理員');
});

