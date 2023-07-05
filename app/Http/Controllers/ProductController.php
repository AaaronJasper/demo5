<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Products;
use Illuminate\Http\Response;

class ProductController extends BaseController
{

    public function __construct()
    {
        $this->middleware("user_login")->except("show", "index");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * 創建商品
     */
    public function store(ProductRequest $request)
    {
        //獲取上傳頭像
        $file = $request->file("picture");
        //存儲於public(第二個參數是指定磁碟)
        $path = $file->store('picture', "public");
        //取得登入資訊
        $session = Session::get("user_data");
        //執行資料輸入
        $res = DB::table("products")->insert([
            "user_id" => $session['id'],
            "name" => $request->input("name"),
            "picture" => $path,
            "comment" => $request->input("comment"),
            "created_at" => date('Y-m-d H:i:s')
        ]);
        return $res;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Products::where("id", $id)->get();
        return $res;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product=Products::where("id",$id)->first();
        $product->name=$request->input("name");
        $product->comment=$request->input("comment");
        
    }

    /**
     * 刪除商品
     */
    public function destroy(string $id)
    {
        //取得登入資訊
        $session = Session::get("user_data");
        $res = DB::table("products")->where("id", $id)->where("user_id", $session["id"])->delete();
        return $res;
    }
}
