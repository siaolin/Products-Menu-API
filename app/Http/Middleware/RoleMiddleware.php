<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 檢查用戶是否已登入，並檢查權限是否符合
        if (!$request->user() || $request->user()->permission !== $role) {
            // 如果用戶未登入或權限不符合，回傳 403 錯誤回應
            return response()->json(['error' => '未經授權'], 403);
        }

        // 如果權限符合，繼續處理請求
        return $next($request);
    }
}
