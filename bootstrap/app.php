<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // //設定全局異常處理
        // $exceptions->render(function (Exception $e, $request) {
        //      if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
        //          return response()->json(['error' => '找不到此商品!'], Response::HTTP_NOT_FOUND);
        //      }

        //      return response()->json([
        //          'error' => 'Server Error'
        //      ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // });
    })->create();
