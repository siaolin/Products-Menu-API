<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 註冊方法
    public function register(Request $request)
    {
        // 使用 Validator 對請求資料進行驗證
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',  // 名字必填，字串類型，最大長度255
            'email' => 'required|string|email|max:255|unique:users',  // email必填，字串類型，格式為email，最大長度255，且在 users 表中必須唯一
            'password' => 'required|string|min:6|confirmed',  // 密碼必填，字串類型，最小長度為6，且需要確認
        ]);

        // 如果驗證失敗，回傳錯誤訊息
        if($validator->fails()){
            return response()->json($validator->errors(), 400);  // 回傳400狀態碼和錯誤訊息
        }

        // 創建新的使用者
        $user = User::create([
            'name' => $request->name,  // 從請求中取得名字
            'email' => $request->email,  // 從請求中取得email
            'password' => Hash::make($request->password),  // 將密碼進行亂碼處理
        ]);

        // 為新創建的使用者生成 JWT
        $token = JWTAuth::fromUser($user);

        // 回傳新使用者資料和 JWT，狀態碼為201
        return response()->json(compact('user','token'), 201);
    }

    // 登入方法
    public function login(Request $request)
    {
        // 只從請求中取得 email 和 password 欄位
        $credentials = $request->only('email', 'password');

        // 使用 JWTAuth 進行驗證
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);  // 驗證失敗，回傳401未授權
        }

        // 驗證成功，回傳 JWT
        return response()->json(compact('token'));
    }

    // 登出方法
    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => '需要提供token'], 401);
        }
        auth()->logout($token);
        return response()->json(['message' => '您已登出成功！'], 200);
    }
    
}