<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class TestController extends BaseController
{
    //測試緩存
    public function index(Request $request)
    {
        //檢查緩存
        $value = Cache::get("key");
        if ($value != null) {
            return "已有緩存";
        }
        //獲取搜尋的參數
        $name = $request->input('name');
        $email = $request->input('email');
        //執行查詢
        $users = User::when($name, function ($query, $name) {
            $query->where('name', 'like', "%$name%");
        })
            ->when($email, function ($query, $email) {
                $query->where('email', $email);
            })->get();

        Cache::put('key', $users, $seconds = 30);
        return $this->same(200, "查詢成功", [$users]);
    }
    public function index1(Request $request){
        Session::put('name',2222);
        Session::put("user",3333);
        return Session::get('user_data');

        $data=$request->input();
        $request->session()->put("user_id",$data["name"]);
        return session(('user_id'));
    }
}
