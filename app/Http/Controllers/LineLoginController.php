<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;

class LineLoginController extends Controller
{
    // 導向到 Line 登入頁面
    public function redirectToLine()
    {
        // 使用 Socialite 驅動的 'line' 來處理重定向到 Line 的登入頁面
        return Socialite::driver('line')->redirect();
    }

    // Line 登入後的回調邏輯
    public function handleLineCallback(Request $request)
    {
        $state = request('state'); // 從請求中取得 state 参数
        Log::info('Callback state parameter:', ['state' => $state]);
        // 從 Session 中獲取之前保存的 state 參數
        $savedState = session('oauth_state');
        Log::info('Saved state parameter:', ['state' => $savedState]);

        // 驗證 state 參數是否匹配
        if ($state !== $savedState) {
            // 處理 state 不匹配的情況
            Log::error('Invalid state parameter detected.');
            abort(403, 'Invalid state parameter.');
        }
        try {
            // 從 Line 回調中取得使用者資料
            $lineUser = Socialite::driver('line')->user();
            
            dd($lineUser);

            // 根據 Line ID 查找是否已有會員帳號
            $user = User::where('line_id', $lineUser->id)->first();

            if (!$user) {
                // 如果會員帳號不存在，則創建新會員帳號
                $user = User::create([
                    'name' => $lineUser->name, // 使用 Line 的名稱
                    'email' => $lineUser->email, // 使用 Line 提供的電子郵件
                    'line_id' => $lineUser->id, // 將 Line 的 ID 存入資料庫
                    'permission' => '一般會員', // 預設為一般會員
                ]);
            }

            // 使用 JWT 生成與該用戶對應的 token 來進行登入
            $token = JWTAuth::fromUser($user);

            // 返回包含 token 的 JSON 回應
            return response()->json(['token' => $token]);

        } catch (\Exception $e) {
            // 如果有任何錯誤，返回錯誤訊息和錯誤狀態碼
            return response()->json(['error' => 'Line 登入失敗', 'message' => $e->getMessage()], 500);
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }
}
