<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 查詢系統目前所有商品列表
     */
    public function index(Request $request)
    {
        // 取得 limit 查詢參數
        $limit = $request->query('limit', 10); // 預設回傳 10 個商品
        // 取得 filters 參數
        $filters = $request->query('filters', '');
        // 取得 sorts 參數
        $sorts = $request->query('sorts', '');

        $query = Product::query();

        // 篩選關鍵字機制
        if ($filters) {
            // 將 filters 字串按逗號分隔為多個條件
            $filterPairs = explode(';', $filters);
            foreach ($filterPairs as $pair) {
                // 將每個條件對按冒號分隔為欄位名稱和篩選關鍵字
                list($field, $value) = explode(':', $pair);
                // 將篩選條件應用到查詢中，使用 like 運算符進行部分匹配
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        // 排序機制
        if ($sorts) {
            $sortPairs = explode(',', $sorts);
            foreach ($sortPairs as $pair) {
                list($field, $direction) = explode(':', $pair);
                $query->orderBy($field, $direction);
            }
        }

        // 使用 paginate 查詢資料
        $products = $query->paginate($limit);

        // 回傳商品資料列表
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * 查詢特定 ID 的商品資料
     */
    public function show(Product $product)
    {
        // 回傳特定商品資料
        return response(new ProductResource($product), Response::HTTP_OK);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 修改指定 ID 的商品
     */
    public function update(Request $request, Product $product)
    {
        // 驗證規則
        $rules = [
            'type_id' => 'sometimes|integer|min:0',
            'product_name' => 'sometimes|string|max:255',
            'product_description' => 'nullable',
            'price' => 'sometimes|integer|min:0',
        ];

        // 驗證請求資料
        $validatedData = $request->validate($rules);
        // 更新商品資料
        $product->update($validatedData);

        $product->refresh();

        // 回傳更新後的資料
        return response()->json([
            'message' => '商品資料更新完成!',
            'product' => $product
        ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     * 刪除指定 ID 的商品
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        // 如果找不到就顯示 404 狀態碼及其錯誤訊息
        if (!$product) {
            return response()->json(['error' => '找不到此商品!'], Response::HTTP_NOT_FOUND);
        }
        // 捕捉可能的例外
        try {
            // 刪除商品
            $product->delete();
            // 如果刪除失敗就顯示 500 狀態碼及錯誤訊息
        } catch (Exception $e) {
            return response()->json(['error' => '刪除此商品失敗!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        // 如果成功刪除就回傳空白內容並給 204 狀態碼
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
