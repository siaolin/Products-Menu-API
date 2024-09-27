<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 查詢所有類別
        $types = Type::get();
        return response($types, Response::HTTP_OK);
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
        $rules = [
            'name' => 'required|max:50|unique:types,name',
            'sort' => 'nullable|integer',
        ];
        // 驗證請求資料
        $validatedData = $request->validate($rules);

        if (empty($request->sort)) {
            $max = Type::get()->max('sort');
            $request['sort'] = $max+1;
        }

        $type = Type::create($validatedData);
        return response($type, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        // 查詢單一類別
        return response($type, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        // 更新類別資料
        $type->update($request->all());
        return response($type, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        // 刪除類別資料
        $type->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
