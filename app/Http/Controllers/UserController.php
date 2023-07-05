<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends BaseController
{

    public function __construct()
    {
        $this->middleware("user_login")->only("store");
    }

    /**
     * 查詢用戶資訊
     */
    public function index(Request $request)
    {
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
        $users=new UserCollection($users);
        return $this->same(200, "查詢成功",[$users]);
    }

    /**
     * 顯示單一用戶數據
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $user=new UserResource($user);
        return $this->same(200, "查詢成功",[$user]);
    }

    /**
     * 用戶資訊修改
     */
    public function store(Request $request)
    {
        $user_data = Session::get('user_data');
        //更新數據庫
        $user=User::find($user_data["id"]);
        $user->name=$request->input("name")?$request->input("name"):$user_data["name"];
        $user->email=$request->input("email")?$request->input("email"):$user_data["email"];
        $user->save();
        //更新Session
        Session::pull('user_data');
        Session::put("user_data", [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "is_locked" => $user->is_locked
        ]);
        $user=new UserResource($user);
        return $this->same(204, "更新成功",[$user]);

    }

    /**
     * 黑名單用戶(給後台用,使用權限還沒設定)
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->is_locked=1;
        $user->save();
        $user=new UserResource($user);
        return $this->same(200, "禁用成功",[$user]);
    }
}
