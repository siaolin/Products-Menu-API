<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineLoginController;

Route::get('/', function () {
    return view('welcome');
});
// // 導向 Line 登入頁面的路由
// Route::get('/login/line', [LineLoginController::class, 'redirectToLine']);

// // Line 登入後的回調路由
// Route::get('/login/line/callback', [LineLoginController::class, 'handleLineCallback']);

Route::middleware(['web'])->group(function () {
    Route::get('/login/line', [LineLoginController::class, 'redirectToLine']);
    Route::get('/login/line/callback', [LineLoginController::class, 'handleLineCallback']);
});
