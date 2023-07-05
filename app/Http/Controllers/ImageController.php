<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\products;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends BaseController
{
    /* //用戶上傳多型態一對一圖片
    public function userPostFile(Request $request)
    {
        $user=User::find($request->id);
        $file = $request->file("picture");
        $path = $file->store('user', "public");
        $user->image()->create([
            "picture"=>$path
        ]);
        return $this->same(200 ,[$path], "上傳成功");
    }   */
    //用戶更新多型態一對一圖片(不特別設立發布api，避免重複上傳)
    public function userUpdateFile(Request $request)
    {
        $user=User::find($request->id);
        $file = $request->file("picture");
        //取得舊圖片
        $oldpicture = Image::where("imageable_id", $user->id)->value("picture");
        //刪除資料庫圖片
        Image::where("imageable_id",$user->id)->delete();
        //刪除資料夾圖片
        Storage::disk("public")->delete($oldpicture);
        //上傳新圖片
        $path = $file->store('user', "public");
        $user->image()->create([
            "picture" => $path
        ]);
        return $this->same(200,[$path], "上傳成功"); 
    }  
    /* //用戶上傳多型態一對一圖片
    public function productPostFile(Request $request)
    {
        $user=Products::find($request->id);
        $file = $request->file("picture");
        $path = $file->store('product', "public");
        $user->image()->create([
            "picture"=>$path
        ]);
        return $this->same(200,[$path], "上傳成功");
    }   */
    //用戶更新多型態一對一圖片
    public function productUpdateFile(Request $request)
    {
        $user=Products::find($request->id);
        $file = $request->file("picture");
        //取得舊圖片
        $oldpicture = Image::where("imageable_id", $user->id)->value("picture");
        //刪除資料庫圖片
        Image::where("imageable_id",$user->id)->delete();
        //刪除資料夾圖片
        Storage::disk("public")->delete($oldpicture);
        //上傳新圖片
        $path = $file->store('product', "public");
        $user->image()->create([
            "picture" => $path
        ]);
        return $this->same(200,[$path], "上傳成功"); 
    }  
}
